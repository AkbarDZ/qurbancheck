<?php

namespace App\Http\Controllers;

use App\Models\Ternak;
use App\Models\KriteriaKurban;
use App\Models\PemeriksaanSyariat;
use App\Models\DetailPemeriksaan;
use App\Models\DokumenSkkh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SyariatController extends Controller
{
    public function index(Request $request)
    {
        $pemeriksaans = PemeriksaanSyariat::with(['ternak', 'penanggungJawab', 'dokumenSkkh'])
                            ->latest('tanggal_pemeriksaan')
                            ->paginate(5, ['*'], 'pemeriksaan_page');

        $dokumenSkkhs = DokumenSkkh::latest('tanggal_terbit')
                            ->paginate(5, ['*'], 'skkh_page');

        $kriterias = KriteriaKurban::all();
        $ternakBelumDiperiksa = Ternak::with(['ras.tipeTernak', 'kandang'])->whereDoesntHave('pemeriksaanSyariat')->get();

        // TAMBAHAN BARU: Ambil sapi yang LAYAK tapi BELUM PUNYA SKKH
        $pemeriksaanTanpaSkkh = PemeriksaanSyariat::with('ternak')
                                ->where('status', 'layak_qurban')
                                ->whereNull('dokumen_skkh_id')
                                ->get();

        // Jika AJAX, kembalikan JSON agar frontend bisa render tanpa reload
        if ($request->ajax()) {
            $pemeriksaanHtml = '';
            foreach ($pemeriksaans as $cek) {
                $pemeriksaanHtml .= view('dashboards.syariat.components.row-pemeriksaan', compact('cek'))->render();
            }
            if ($pemeriksaans->isEmpty()) {
                $pemeriksaanHtml = '<tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-2 d-block mb-3 text-muted opacity-50"></i>
                                        <h6 class="mb-0 fw-semibold text-secondary">Belum Ada Data Pemeriksaan Syariat</h6>
                                        <p class="small text-muted mb-0">Klik tombol "Mulai Pemeriksaan Baru" untuk memulai.</p>
                                    </td>
                                </tr>';
            }

            $skkhHtml = '';
            foreach ($dokumenSkkhs as $doc) {
                $skkhHtml .= view('dashboards.syariat.components.row-skkh', compact('doc'))->render();
            }
            if ($dokumenSkkhs->isEmpty()) {
                $skkhHtml = '<tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-2 d-block mb-3 text-muted opacity-50"></i>
                                        <h6 class="mb-0 fw-semibold text-secondary">Belum Ada Dokumen SKKH</h6>
                                        <p class="small text-muted mb-0">Klik tombol "Upload SKKH Kolektif" untuk mengunggah dokumen baru.</p>
                                    </td>
                                </tr>';
            }

            return response()->json([
                'success' => true,
                'pemeriksaanHtml' => $pemeriksaanHtml,
                'pemeriksaanPagination' => (string) $pemeriksaans->appends(request()->except('pemeriksaan_page'))->links(),
                'skkhHtml' => $skkhHtml,
                'skkhPagination' => (string) $dokumenSkkhs->appends(request()->except('skkh_page'))->links(),
            ]);
        }

        return view('dashboards.syariat.index', compact(
            'pemeriksaans', 
            'dokumenSkkhs', 
            'kriterias', 
            'ternakBelumDiperiksa',
            'pemeriksaanTanpaSkkh' // Jangan lupa tambahkan di sini
        ));
    }

    // Fungsi Menyimpan Pemeriksaan (Auto-Calculate Layak/Tidak)
    public function storePemeriksaan(Request $request)
    {
        $request->validate([
            'ternak_id'           => 'required|array|min:1',
            'ternak_id.*'         => 'exists:ternaks,id',
            'tanggal_pemeriksaan' => 'required|date',
            'kriteria'            => 'required|array',
            // Validasi file foto di dalam array kriteria
            'kriteria.*.foto_cacat'=> 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'ternak_id.required' => 'Pilih setidaknya satu hewan untuk diperiksa.'
        ]);

        try {
            DB::beginTransaction();

            $ternakIds = $request->ternak_id;
            $jumlahTernak = count($ternakIds);

            // PROSES UPLOAD FOTO (Hanya 1x upload meskipun hewannya banyak)
            $uploadedPhotos = [];
            if ($request->has('kriteria')) {
                foreach ($request->kriteria as $kriteria_id => $data) {
                    if (isset($data['foto_cacat']) && $request->file("kriteria.{$kriteria_id}.foto_cacat")) {
                        $path = $request->file("kriteria.{$kriteria_id}.foto_cacat")->store('bukti_cacat_syariat', 'public');
                        $uploadedPhotos[$kriteria_id] = $path;
                    }
                }
            }

            foreach ($ternakIds as $t_id) {
                
                $pemeriksaan = PemeriksaanSyariat::create([
                    'ternak_id'           => $t_id,
                    'penanggungjawab_id'  => auth()->id() ?? 1,
                    'tanggal_pemeriksaan' => $request->tanggal_pemeriksaan,
                    'status'              => 'layak_qurban',
                ]);

                $isLayak = true;

                foreach ($request->kriteria as $kriteria_id => $data) {
                    $kriteriaMaster = KriteriaKurban::find($kriteria_id);
                    $isLolos = isset($data['is_lolos']) ? true : false;
                    
                    // Ambil path foto yang sudah diupload di atas (jika ada)
                    $fotoPath = $uploadedPhotos[$kriteria_id] ?? null;

                    DetailPemeriksaan::create([
                        'pemeriksaan_id'  => $pemeriksaan->id,
                        'kriteria_id'     => $kriteria_id,
                        'is_lolos'        => $isLolos,
                        'catatan'         => $data['catatan'] ?? null,
                        'dir_bukti_cacat' => $fotoPath // Simpan ke database
                    ]);

                    if (!$isLolos && $kriteriaMaster->is_fatal) {
                        $isLayak = false;
                    }
                }

                $pemeriksaan->update([
                    'status' => $isLayak ? 'layak_qurban' : 'tidak_layak_qurban'
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Pemeriksaan untuk $jumlahTernak ekor hewan berhasil diproses."
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeSkkh(Request $request)
    {
        $request->validate([
            'nomor_surat'           => 'nullable|string|max:255|unique:dokumen_skkhs,nomor_surat',
            'nama_dokter_pemeriksa' => 'required|string|max:255',
            'instansi_penerbit'     => 'nullable|string|max:255',
            'tanggal_terbit'        => 'required|date',
            'file_skkh'             => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // Maks 5MB
            'pemeriksaan_ids'       => 'required|array|min:1',
            'pemeriksaan_ids.*'     => 'exists:pemeriksaan_syariats,id',
        ], [
            'pemeriksaan_ids.required' => 'Anda harus memilih minimal satu hewan untuk ditautkan ke SKKH ini.'
        ]);

        try {
            DB::beginTransaction();

            // 1. Upload File SKKH
            $path = $request->file('file_skkh')->store('dokumen_skkh', 'public');

            // 2. Simpan Data Surat
            $skkh = DokumenSkkh::create([
                'nomor_surat'           => $request->nomor_surat,
                'nama_dokter_pemeriksa' => $request->nama_dokter_pemeriksa,
                'instansi_penerbit'     => $request->instansi_penerbit,
                'tanggal_terbit'        => $request->tanggal_terbit,
                'dir_bukti_skkh'        => $path,
            ]);

            // 3. Update Massal (Tautkan Surat ke Hewan yang Dicentang)
            PemeriksaanSyariat::whereIn('id', $request->pemeriksaan_ids)
                ->update(['dokumen_skkh_id' => $skkh->id]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dokumen SKKH berhasil diunggah dan ditautkan ke ' . count($request->pemeriksaan_ids) . ' ekor hewan.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengunggah SKKH: ' . $e->getMessage()
            ], 500);
        }
    }

    public function showSkkh($id)
    {
        $skkh = DokumenSkkh::with([
            'pemeriksaanSyariats.ternak.ras.tipeTernak',
            'pemeriksaanSyariats.ternak.kandang'
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $skkh
        ]);
    }

    public function showPemeriksaan($id)
    {
        $pemeriksaan = PemeriksaanSyariat::with([
            'ternak', 
            'penanggungJawab', 
            'detailPemeriksaans.kriteria' // Memuat relasi detail beserta master kriterianya
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $pemeriksaan
        ]);
    }

    public function destroyPemeriksaan($id)
    {
        $pemeriksaan = PemeriksaanSyariat::findOrFail($id);

        try {
            // Karena di migration Anda sudah menggunakan onDelete('cascade') untuk detail_pemeriksaans,
            // menghapus induknya akan otomatis membersihkan semua checklist-nya juga.
            $pemeriksaan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Pemeriksaan dibatalkan. Sapi telah dikembalikan ke daftar Belum Diperiksa.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan pemeriksaan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroySkkh($id)
    {
        $skkh = DokumenSkkh::findOrFail($id);

        try {
            DB::beginTransaction();

            // Hapus file dari storage public jika ada
            if ($skkh->dir_bukti_skkh) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($skkh->dir_bukti_skkh);
            }

            // Hapus record SKKH (Foreign keys akan terset null secara otomatis oleh DB constraint)
            $skkh->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dokumen SKKH berhasil dihapus. Hubungan dengan hewan yang tertaut telah dilepas.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus dokumen SKKH: ' . $e->getMessage()
            ], 500);
        }
    }
}
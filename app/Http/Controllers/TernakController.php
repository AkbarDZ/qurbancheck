<?php

namespace App\Http\Controllers;

use App\Models\Kandang;
use App\Models\LogBerat;
use App\Models\RasTernak;
use App\Models\Ternak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TernakController extends Controller
{
    public function index(Request $request)
    {
        // Memulai query builder
        $query = Ternak::with([
            'ras.tipeTernak', 
            'kandang' => function($q) {
                $q->withCount('ternaks');
            }, 
            'logBerats' => function($q) {
                $q->orderBy('tanggal_timbang', 'desc')->orderBy('id', 'desc');
            },
            'pemeriksaanSyariat'
        ])->withCount('logKesehatans');

        // Filter: Pencarian berdasarkan eartag atau nama panggilan
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_eartag', 'like', '%' . $search . '%')
                  ->orWhere('nama_panggilan', 'like', '%' . $search . '%');
            });
        }

        // Filter: Kandang
        if ($request->filled('kandang_id')) {
            $query->where('kandang_id', $request->kandang_id);
        }

        // Filter: Ras / Jenis Ternak
        if ($request->filled('ras_id')) {
            $query->where('ras_id', $request->ras_id);
        }

        // Filter berdasarkan rentang tanggal custom (Tanggal Masuk / created_at)
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Sorting
        if ($request->filled('sort')) {
            if ($request->sort == 'oldest') {
                $query->orderBy('created_at', 'asc');
            } elseif ($request->sort == 'az') {
                $query->orderBy(DB::raw('COALESCE(nama_panggilan, nomor_eartag)'), 'asc');
            } elseif ($request->sort == 'za') {
                $query->orderBy(DB::raw('COALESCE(nama_panggilan, nomor_eartag)'), 'desc');
            } else {
                $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Ganti ->get() menjadi ->paginate(5)
        $ternaks = $query->paginate(5);

        // Jika AJAX, kembalikan JSON agar frontend bisa render tanpa reload
        if ($request->ajax()) {
            $html = '';
            foreach ($ternaks as $ternak) {
                $html .= view('dashboards.ternak.components.card-ternak', compact('ternak'))->render();
            }
            return response()->json([
                'success'    => true,
                'html'       => $html,
                'pagination' => (string) $ternaks->withQueryString()->links(),
                'total'      => $ternaks->total(),
            ]);
        }

        // Kirim data ke view
        $rasTernaks = RasTernak::with('tipeTernak')->get();
        $kandangs = Kandang::withCount('ternaks')->get();

        return view('dashboards.ternak.index', compact('ternaks', 'rasTernaks', 'kandangs'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'nomor_eartag'    => 'required|string|max:255|unique:ternaks,nomor_eartag',
            'ras_id'          => 'required|exists:ras_ternaks,id',
            'kandang_id'      => 'required|exists:kandangs,id',
            'jenis_kelamin'   => 'required|in:jantan,betina',
            'berat_awal'      => 'required|numeric|min:1',
            'foto'            => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maksimal 2MB
            'harga_beli_awal' => 'nullable|numeric|min:0',
            'tanggal_lahir'   => 'nullable|date|before_or_equal:today',
        ], [
            // Kustomisasi pesan error agar lebih ramah dibaca
            'nomor_eartag.unique' => 'Nomor eartag ini sudah terdaftar di sistem.',
            'foto.image'          => 'File harus berupa gambar.',
            'foto.max'            => 'Ukuran foto tidak boleh lebih dari 2MB.'
        ]);

        try {
            // Check capacity first
            $kandang = Kandang::withCount('ternaks')->findOrFail($validated['kandang_id']);
            if ($kandang->ternaks_count >= $kandang->kapasitas_maksimal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kandang ' . $kandang->nama_kandang . ' sudah penuh (Kapasitas: ' . $kandang->kapasitas_maksimal . ' ekor).'
                ], 422);
            }

            // Memulai Transaksi Database
            DB::beginTransaction();

            // 2. Proses Upload Foto (Jika Ada)
            $pathFoto = null;
            if ($request->hasFile('foto')) {
                // Akan tersimpan di storage/app/public/ternak_foto
                $pathFoto = $request->file('foto')->store('ternak_foto', 'public');
            }

            $hargaBeli = $request->input('asal_hewan') === 'beli' ? $request->input('harga_beli_awal') : 0;
            $tanggalLahir = $request->input('asal_hewan') === 'lahir' ? $request->input('tanggal_lahir') : null;

            // 3. Simpan Data ke Tabel Ternak
            $ternak = Ternak::create([
                'nomor_eartag'    => $validated['nomor_eartag'],
                'ras_id'          => $validated['ras_id'],
                'kandang_id'      => $validated['kandang_id'],
                'jenis_kelamin'   => $validated['jenis_kelamin'],
                'dir_foto_hewan'  => $pathFoto,
                'harga_beli_awal' => $hargaBeli,
                'tanggal_lahir'   => $tanggalLahir,
            ]);

            // 4. Simpan Data ke Tabel Log Berat
            LogBerat::create([
                'ternak_id'       => $ternak->id,
                'berat_kg'        => $validated['berat_awal'],
                'tanggal_timbang' => now()->toDateString(), // Otomatis tanggal hari ini
            ]);

            // Commit (Setujui semua perubahan ke database)
            DB::commit();

            // 5. Eager Load Relasi untuk Response AJAX (Agar bisa langsung dirender di UI Card)
            $ternak->load(['ras.tipeTernak', 'kandang' => function($q) {
                $q->withCount('ternaks');
            }, 'logBerats' => function($query) {
                $query->latest('tanggal_timbang')->take(1);
            }, 'pemeriksaanSyariat'])->loadCount('logKesehatans');

            return response()->json([
                'success' => true,
                'message' => 'Data ternak berhasil ditambahkan!',
                'data'    => $ternak
            ]);

        } catch (\Exception $e) {
            // Rollback (Batalkan semua) jika terjadi error (misal: storage penuh atau error SQL)
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Error store Ternak: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat menyimpan data.'
            ], 500);
        }
    }

    public function updateNamaPanggilan(Request $request, $id)
    {
        // Validasi opsional (bisa kosong jika dihapus)
        $request->validate([
            'nama_panggilan' => 'nullable|string|max:255'
        ]);

        $ternak = Ternak::findOrFail($id);
        $ternak->update([
            'nama_panggilan' => $request->nama_panggilan
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Nama panggilan berhasil diperbarui',
            'data' => [
                'id' => $ternak->id,
                'nama_panggilan' => $ternak->nama_panggilan
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $ternak = Ternak::findOrFail($id);

        $validated = $request->validate([
            'nomor_eartag'    => 'required|string|max:255|unique:ternaks,nomor_eartag,' . $id,
            'ras_id'          => 'required|exists:ras_ternaks,id',
            'kandang_id'      => 'required|exists:kandangs,id',
            'jenis_kelamin'   => 'required|in:jantan,betina',
            'foto'            => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'harga_beli_awal' => 'nullable|numeric|min:0',
            'tanggal_lahir'   => 'nullable|date|before_or_equal:today',
        ], [
            // Pesan ramah user
            'nomor_eartag.unique' => 'nomor tag sudah digunakan',
            'foto.image'          => 'File yang diunggah harus berupa gambar.',
            'foto.max'            => 'gambar terlalu besar, maks 2MB'
        ]);

        try {
            // Check capacity first if changing kandang
            if ($ternak->kandang_id !== (int) $validated['kandang_id']) {
                $kandang = Kandang::withCount('ternaks')->findOrFail($validated['kandang_id']);
                if ($kandang->ternaks_count >= $kandang->kapasitas_maksimal) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Kandang ' . $kandang->nama_kandang . ' sudah penuh (Kapasitas: ' . $kandang->kapasitas_maksimal . ' ekor).'
                    ], 422);
                }
            }

            DB::beginTransaction();

            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada
                if ($ternak->dir_foto_hewan && Storage::disk('public')->exists($ternak->dir_foto_hewan)) {
                    Storage::disk('public')->delete($ternak->dir_foto_hewan);
                }
                // Simpan foto baru
                $validated['dir_foto_hewan'] = $request->file('foto')->store('ternak_foto', 'public');
            }

            $hargaBeli = $request->input('asal_hewan') === 'beli' ? $request->input('harga_beli_awal') : 0;
            $tanggalLahir = $request->input('asal_hewan') === 'lahir' ? $request->input('tanggal_lahir') : null;

            $validated['harga_beli_awal'] = $hargaBeli;
            $validated['tanggal_lahir'] = $tanggalLahir;

            $ternak->update($validated);

            DB::commit();

            // Load relasi agar data di UI langsung berganti nama ras/kandangnya
            $ternak->load(['ras.tipeTernak', 'kandang' => function($q) {
                $q->withCount('ternaks');
            }, 'logBerats' => function($query) {
                $query->orderBy('tanggal_timbang', 'desc')->orderBy('id', 'desc'); // Ambil semua secara menurun, blade/js ambil [0] atau first()
            }, 'pemeriksaanSyariat'])->loadCount('logKesehatans');

            return response()->json([
                'success' => true,
                'message' => 'Data ternak berhasil diperbarui',
                'data'    => $ternak
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Error update Ternak: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem saat memperbarui data.'], 500);
        }
    }

    public function keuangan($id)
    {
        $ternak = Ternak::with(['logKesehatans.pengobatans', 'kandang'])->findOrFail($id);

        $modalAwal = $ternak->harga_beli_awal;

        $rincianBulanan = [];
        $biayaMedisTotal = 0;

        foreach ($ternak->logKesehatans as $logKesehatan) {
            $biaya = $logKesehatan->pengobatans->sum('biaya_pengobatan');
            if ($biaya > 0) {
                $biayaMedisTotal += $biaya;
                // Group by Y-m
                $bulanTahun = \Carbon\Carbon::parse($logKesehatan->tanggal_rekam)->format('Y-m');
                if (!isset($rincianBulanan[$bulanTahun])) {
                    $rincianBulanan[$bulanTahun] = ['pakan' => 0, 'medis' => 0];
                }
                $rincianBulanan[$bulanTahun]['medis'] += $biaya;
            }
        }

        $biayaPakanTotal = 0;
        if ($ternak->kandang_id) {
            // Filter pakan hanya yang diberikan semenjak tanggal ternak dicatat/diassign
            $distribusiPakans = \App\Models\DistribusiPakan::where('kandang_id', $ternak->kandang_id)
                ->where('tanggal_pemberian', '>=', $ternak->created_at->format('Y-m-d'))
                ->get();
            $populasiKandang = \App\Models\Ternak::where('kandang_id', $ternak->kandang_id)->count();

            if ($populasiKandang > 0) {
                foreach ($distribusiPakans as $distribusi) {
                    $biayaProporsional = $distribusi->total_biaya / $populasiKandang;
                    $biayaPakanTotal += $biayaProporsional;

                    $bulanTahun = \Carbon\Carbon::parse($distribusi->tanggal_pemberian)->format('Y-m');
                    if (!isset($rincianBulanan[$bulanTahun])) {
                        $rincianBulanan[$bulanTahun] = ['pakan' => 0, 'medis' => 0];
                    }
                    $rincianBulanan[$bulanTahun]['pakan'] += $biayaProporsional;
                }
            }
        }

        // Format Rincian Bulanan untuk response
        $rincianResponse = [];
        krsort($rincianBulanan); // Urutkan descending (bulan terbaru di atas)

        foreach ($rincianBulanan as $bt => $rincian) {
            $rincianResponse[] = [
                'bulan_tahun' => \Carbon\Carbon::createFromFormat('Y-m', $bt)->translatedFormat('F Y'),
                'biaya_pakan' => $rincian['pakan'],
                'biaya_medis' => $rincian['medis'],
                'subtotal'    => $rincian['pakan'] + $rincian['medis']
            ];
        }

        $totalModal = $modalAwal + $biayaMedisTotal + $biayaPakanTotal;

        return response()->json([
            'success' => true,
            'data' => [
                'modal_awal' => $modalAwal,
                'biaya_medis' => $biayaMedisTotal,
                'biaya_pakan_proporsional' => $biayaPakanTotal,
                'total_modal' => $totalModal,
                'saran_jual' => $totalModal, // Can be adjusted in frontend
                'kandang_nama' => $ternak->kandang ? $ternak->kandang->nama_kandang : '-',
                'populasi_kandang' => isset($populasiKandang) ? $populasiKandang : 0,
                'rincian_bulanan' => $rincianResponse
            ]
        ]);
    }

    public function destroy($id)
    {
        $ternak = Ternak::findOrFail($id);

        try {
            DB::beginTransaction();

            // Hapus file foto dari storage terlebih dahulu
            if ($ternak->dir_foto_hewan && Storage::disk('public')->exists($ternak->dir_foto_hewan)) {
                Storage::disk('public')->delete($ternak->dir_foto_hewan);
            }

            // Hapus log berat terkait (jika di migrasi belum set cascade delete)
            $ternak->logBerats()->delete();
            
            $ternak->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data ternak berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Error destroy Ternak: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem saat menghapus data.'], 500);
        }
    }
}

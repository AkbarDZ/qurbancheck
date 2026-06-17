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
            'kandang', 
            'logBerats' => function($q) {
                $q->latest('tanggal_timbang')->take(1);
            }
        ]);

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

        // Ganti ->get() menjadi ->paginate(5)
        $ternaks = $query->latest()->paginate(5);

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
        $kandangs = Kandang::all();

        return view('dashboards.ternak.index', compact('ternaks', 'rasTernaks', 'kandangs'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'nomor_eartag'  => 'required|string|max:255|unique:ternaks,nomor_eartag',
            'ras_id'        => 'required|exists:ras_ternaks,id',
            'kandang_id'    => 'required|exists:kandangs,id',
            'jenis_kelamin' => 'required|in:jantan,betina',
            'berat_awal'    => 'required|numeric|min:1',
            'foto'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maksimal 2MB
        ], [
            // Kustomisasi pesan error agar lebih ramah dibaca
            'nomor_eartag.unique' => 'Nomor eartag ini sudah terdaftar di sistem.',
            'foto.image'          => 'File harus berupa gambar.',
            'foto.max'            => 'Ukuran foto tidak boleh lebih dari 2MB.'
        ]);

        try {
            // Memulai Transaksi Database
            DB::beginTransaction();

            // 2. Proses Upload Foto (Jika Ada)
            $pathFoto = null;
            if ($request->hasFile('foto')) {
                // Akan tersimpan di storage/app/public/ternak_foto
                $pathFoto = $request->file('foto')->store('ternak_foto', 'public');
            }

            // 3. Simpan Data ke Tabel Ternak
            $ternak = Ternak::create([
                'nomor_eartag'   => $validated['nomor_eartag'],
                'ras_id'         => $validated['ras_id'],
                'kandang_id'     => $validated['kandang_id'],
                'jenis_kelamin'  => $validated['jenis_kelamin'],
                'dir_foto_hewan' => $pathFoto,
                // Kolom lain (seperti harga_beli_awal, tanggal_lahir) dibiarkan null 
                // karena belum dimasukkan di form awal. Pastikan di migration diset nullable().
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
            $ternak->load(['ras.tipeTernak', 'kandang', 'logBerats' => function($query) {
                $query->latest('tanggal_timbang')->take(1);
            }]);

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
            'nomor_eartag'  => 'required|string|max:255|unique:ternaks,nomor_eartag,' . $id,
            'ras_id'        => 'required|exists:ras_ternaks,id',
            'kandang_id'    => 'required|exists:kandangs,id',
            'jenis_kelamin' => 'required|in:jantan,betina',
            'foto'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            // Pesan ramah user
            'nomor_eartag.unique' => 'nomor tag sudah digunakan',
            'foto.image'          => 'File yang diunggah harus berupa gambar.',
            'foto.max'            => 'gambar terlalu besar, maks 2MB'
        ]);

        try {
            DB::beginTransaction();

            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada
                if ($ternak->dir_foto_hewan && Storage::disk('public')->exists($ternak->dir_foto_hewan)) {
                    Storage::disk('public')->delete($ternak->dir_foto_hewan);
                }
                // Simpan foto baru
                $validated['dir_foto_hewan'] = $request->file('foto')->store('ternak_foto', 'public');
            }

            $ternak->update($validated);

            DB::commit();

            // Load relasi agar data di UI langsung berganti nama ras/kandangnya
            $ternak->load(['ras.tipeTernak', 'kandang', 'logBerats' => function($query) {
                $query->latest('tanggal_timbang')->take(1); // Ambil timbangan paling baru
            }]);

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

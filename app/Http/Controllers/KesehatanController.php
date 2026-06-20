<?php

namespace App\Http\Controllers;

use App\Models\LogKesehatan;
use App\Models\Pengobatan;
use App\Models\Ternak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KesehatanController extends Controller
{
    public function index(Request $request)
    {
        // Query untuk Log Kesehatan yang sudah di-join dengan data ternak & pengobatan (untuk performa)
        $query = LogKesehatan::with(['ternak', 'pengobatans']);

        // Filter berdasarkan pencarian Eartag
        if ($request->has('search') && $request->search != '') {
            $query->whereHas('ternak', function($q) use ($request) {
                $q->where('nomor_eartag', 'like', '%' . $request->search . '%');
            });
        }

        // Filter berdasarkan status karantina dari form index.blade.php
        if ($request->has('karantina') && $request->karantina != '') {
            $query->where('status_karantina', $request->karantina);
        }

        // Filter berdasarkan rentang tanggal custom
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_rekam', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_rekam', '<=', $request->end_date);
        }

        // Sorting
        if ($request->filled('sort')) {
            if ($request->sort == 'oldest') {
                $query->orderBy('tanggal_rekam', 'asc');
            } elseif ($request->sort == 'az') {
                $query->join('ternaks', 'log_kesehatans.ternak_id', '=', 'ternaks.id')
                      ->select('log_kesehatans.*')
                      ->orderBy('ternaks.nomor_eartag', 'asc');
            } elseif ($request->sort == 'za') {
                $query->join('ternaks', 'log_kesehatans.ternak_id', '=', 'ternaks.id')
                      ->select('log_kesehatans.*')
                      ->orderBy('ternaks.nomor_eartag', 'desc');
            } else {
                $query->orderBy('tanggal_rekam', 'desc');
            }
        } else {
            $query->orderBy('tanggal_rekam', 'desc');
        }

        $logKesehatans = $query->paginate(5);

        if ($request->ajax()) {
            return response()->json([
                'success'    => true,
                'data'       => $logKesehatans->items(),
                'pagination' => (string) $logKesehatans->withQueryString()->links(),
                'total'      => $logKesehatans->total(),
            ]);
        }
        
        // Data untuk dropdown di Modal Tambah
        $ternaks = Ternak::orderBy('nomor_eartag')->get();

        return view('dashboards.kesehatan.index', compact('logKesehatans', 'ternaks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Tabel Log Kesehatan
            'ternak_id'              => 'required|exists:ternaks,id',
            'tanggal_rekam'          => 'required|date',
            'gejala'                 => 'required|string|max:1000',
            'foto_gejala'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            
            // PERBAIKAN 2: Validasi array dikarantina dihapus, cukup validasi nama/biaya/dosis obat
            'nama_obat_tindakan'     => 'required|array|min:1',
            'nama_obat_tindakan.*'   => 'required|string|max:255',
            'biaya_pengobatan'       => 'required|array',
            'biaya_pengobatan.*'     => 'nullable|numeric|min:0',
            'dosis'                  => 'required|array',
            'dosis.*'                => 'nullable|string|max:255',
            'catatan'                => 'required|array',
            'catatan.*'              => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $pathFotoGejala = null;
            if ($request->hasFile('foto_gejala')) {
                $pathFotoGejala = $request->file('foto_gejala')->store('gejala_ternak', 'public');
            }

            // PERBAIKAN 3: Mengecek apakah checkbox status_karantina dicentang
            $isKarantina = $request->has('status_karantina');

            $log = \App\Models\LogKesehatan::create([
                'ternak_id'           => $validated['ternak_id'],
                'penanggung_jawab_id' => auth()->id(),
                'tanggal_rekam'       => $validated['tanggal_rekam'],
                'gejala'              => $validated['gejala'],
                'dir_foto_gejala'     => $pathFotoGejala,
                'status_karantina'    => $isKarantina,
            ]);

            // Looping Simpan Obat
            foreach ($request->nama_obat_tindakan as $index => $namaObat) {
                \App\Models\Pengobatan::create([
                    'log_kesehatan_id'   => $log->id,
                    'nama_obat_tindakan' => $namaObat,
                    'biaya_pengobatan'   => $request->biaya_pengobatan[$index] ?? 0,
                    'dosis'              => $request->dosis[$index] ?? null,
                    'catatan'            => $request->catatan[$index] ?? null,
                ]);
            }

            // UPDATE STATUS REAL-TIME HEWAN
            $ternak = \App\Models\Ternak::findOrFail($validated['ternak_id']);
            $ternak->update([
                'is_karantina' => $isKarantina
            ]);

            DB::commit();

            $log->load(['ternak', 'pengobatans']);

            return response()->json([
                'success' => true,
                'message' => 'Data pemeriksaan dan ' . count($request->nama_obat_tindakan) . ' tindakan berhasil disimpan!',
                'data'    => $log
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false, 
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $log = \App\Models\LogKesehatan::with(['ternak', 'pengobatans', 'penanggungJawab'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $log
        ]);
    }

    public function destroy($id)
    {
        $log = LogKesehatan::findOrFail($id);

        try {
            $log->delete();

            return response()->json([
                'success' => true,
                'message' => 'Catatan pemeriksaan berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }
}
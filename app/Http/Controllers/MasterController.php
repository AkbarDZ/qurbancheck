<?php

namespace App\Http\Controllers;

use App\Models\TipeTernak;
use App\Models\RasTernak;
use App\Models\Kandang;
use App\Models\KriteriaKurban;
use Illuminate\Http\Request;

class MasterController extends Controller
{
    public function index()
    {
        $tipeTernaks = TipeTernak::all();
        $rasTernaks = RasTernak::with('tipeTernak')->get();
        $kandangs = Kandang::withCount('ternaks')->get();
        $kriteriaKurbans = KriteriaKurban::all();
        return view('dashboards.master.index', compact('tipeTernaks', 'rasTernaks', 'kandangs', 'kriteriaKurbans'));
    }


    // store functions
    public function storeTipe(Request $request)
    {
        $validated = $request->validate([
            'nama_jenis' => 'required|string|max:50',
            'umur_minimal_qurban' => 'required|integer|min:1',
        ]);

        $tipe = TipeTernak::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tipe ternak berhasil ditambahkan',
            'data' => $tipe
        ]);
    }

    public function storeRas(Request $request)
    {
        $validated = $request->validate([
            'tipe_ternak_id' => 'required|exists:tipe_ternaks,id',
            'nama_ras' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
        ]);

        $ras = RasTernak::create($validated);
        $ras->load('tipeTernak');

        return response()->json([
            'success' => true,
            'message' => 'Ras ternak berhasil ditambahkan',
            'data' => $ras
        ]);
    }

    public function storeKandang(Request $request)
    {
        $validated = $request->validate([
            'nama_kandang' => 'required|string|max:100',
            'kapasitas_maksimal' => 'required|integer|min:1',
        ]);

        $kandang = Kandang::create($validated);
        $kandang->loadCount('ternaks');

        return response()->json([
            'success' => true,
            'message' => 'Kandang berhasil ditambahkan',
            'data' => $kandang
        ]);
    }

    public function storeKriteria(Request $request)
    {
        $validated = $request->validate([
            'nama_kriteria' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'is_fatal' => 'nullable|boolean',
        ]);

        $validated['is_fatal'] = $request->has('is_fatal');

        $kriteria = KriteriaKurban::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kriteria kurban berhasil ditambahkan',
            'data' => $kriteria
        ]);
    }


    // update functions
    public function updateTipe(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_jenis' => 'required|string|max:50',
            'umur_minimal_qurban' => 'required|integer|min:1',
        ]);

        $tipe = TipeTernak::findOrFail($id);
        $tipe->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tipe ternak berhasil diperbarui',
            'data' => $tipe
        ]);
    }

    public function updateRas(Request $request, $id)
    {
        $validated = $request->validate([
            'tipe_ternak_id' => 'required|exists:tipe_ternaks,id',
            'nama_ras' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
        ]);

        $ras = RasTernak::findOrFail($id);
        $ras->update($validated);
        $ras->load('tipeTernak');

        return response()->json([
            'success' => true,
            'message' => 'Ras ternak berhasil diperbarui',
            'data' => $ras
        ]);
    }

    public function updateKandang(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_kandang' => 'required|string|max:100',
            'kapasitas_maksimal' => 'required|integer|min:1',
        ]);

        $kandang = Kandang::findOrFail($id);
        $kandang->update($validated);
        $kandang->loadCount('ternaks');

        return response()->json([
            'success' => true,
            'message' => 'Kandang berhasil diperbarui',
            'data' => $kandang
        ]);
    }

    public function updateKriteria(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_kriteria' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'is_fatal' => 'nullable|boolean',
        ]);

        $validated['is_fatal'] = $request->has('is_fatal');

        $kriteria = KriteriaKurban::findOrFail($id);
        $kriteria->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kriteria kurban berhasil diperbarui',
            'data' => $kriteria
        ]);
    }


    // delete functions
    public function destroyTipe($id)
    {
        $tipe = TipeTernak::findOrFail($id);
        $tipe->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tipe ternak berhasil dihapus'
        ]);
    }

    public function destroyRas($id)
    {
        $ras = RasTernak::findOrFail($id);
        $ras->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ras ternak berhasil dihapus'
        ]);
    }

    public function destroyKandang($id)
    {
        $kandang = Kandang::findOrFail($id);
        $kandang->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kandang berhasil dihapus'
        ]);
    }

    public function destroyKriteria($id)
    {
        $kriteria = KriteriaKurban::findOrFail($id);
        $kriteria->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kriteria kurban berhasil dihapus'
        ]);
    }
}

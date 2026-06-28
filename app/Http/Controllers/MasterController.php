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

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tipe ternak berhasil ditambahkan',
                'data' => $tipe
            ]);
        }
        return redirect()->back()->with('success', 'Tipe ternak berhasil ditambahkan');
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

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Ras ternak berhasil ditambahkan',
                'data' => $ras
            ]);
        }
        return redirect()->back()->with('success', 'Ras ternak berhasil ditambahkan');
    }

    public function storeKandang(Request $request)
    {
        $validated = $request->validate([
            'nama_kandang' => 'required|string|max:100',
            'kapasitas_maksimal' => 'required|integer|min:1',
        ]);

        $kandang = Kandang::create($validated);
        $kandang->loadCount('ternaks');

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kandang berhasil ditambahkan',
                'data' => $kandang
            ]);
        }
        return redirect()->back()->with('success', 'Kandang berhasil ditambahkan');
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

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kriteria kurban berhasil ditambahkan',
                'data' => $kriteria
            ]);
        }
        return redirect()->back()->with('success', 'Kriteria kurban berhasil ditambahkan');
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

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tipe ternak berhasil diperbarui',
                'data' => $tipe
            ]);
        }
        return redirect()->back()->with('success', 'Tipe ternak berhasil diperbarui');
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

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Ras ternak berhasil diperbarui',
                'data' => $ras
            ]);
        }
        return redirect()->back()->with('success', 'Ras ternak berhasil diperbarui');
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

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kandang berhasil diperbarui',
                'data' => $kandang
            ]);
        }
        return redirect()->back()->with('success', 'Kandang berhasil diperbarui');
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

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kriteria kurban berhasil diperbarui',
                'data' => $kriteria
            ]);
        }
        return redirect()->back()->with('success', 'Kriteria kurban berhasil diperbarui');
    }


    // delete functions
    public function destroyTipe(Request $request, $id)
    {
        $tipe = TipeTernak::findOrFail($id);
        $tipe->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tipe ternak berhasil dihapus'
            ]);
        }
        return redirect()->back()->with('success', 'Tipe ternak berhasil dihapus');
    }

    public function destroyRas(Request $request, $id)
    {
        $ras = RasTernak::findOrFail($id);
        $ras->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Ras ternak berhasil dihapus'
            ]);
        }
        return redirect()->back()->with('success', 'Ras ternak berhasil dihapus');
    }

    public function destroyKandang(Request $request, $id)
    {
        $kandang = Kandang::findOrFail($id);
        $kandang->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kandang berhasil dihapus'
            ]);
        }
        return redirect()->back()->with('success', 'Kandang berhasil dihapus');
    }

    public function destroyKriteria(Request $request, $id)
    {
        $kriteria = KriteriaKurban::findOrFail($id);
        $kriteria->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kriteria kurban berhasil dihapus'
            ]);
        }
        return redirect()->back()->with('success', 'Kriteria kurban berhasil dihapus');
    }
}

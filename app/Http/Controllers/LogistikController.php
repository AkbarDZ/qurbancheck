<?php

namespace App\Http\Controllers;

use App\Models\InventariPakan;
use App\Models\DistribusiPakan;
use App\Models\Kandang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogistikController extends Controller
{
    public function index()
    {
        $pakans = InventariPakan::orderBy('nama_pakan')->get();
        $kandangs = Kandang::withCount('ternaks')->get(); // Ambil kandang beserta jumlah sapinya
        $distribusis = DistribusiPakan::with(['pakan', 'kandang'])->latest('tanggal_pemberian')->get();

        return view('dashboards.logistik.index', compact('pakans', 'kandangs', 'distribusis'));
    }

    // Fungsi Tambah Master/Stok Pakan Baru
    public function storePakan(Request $request)
    {
        $request->validate([
            'nama_pakan'   => 'required|string|max:255',
            'harga_per_kg' => 'required|numeric|min:0',
            'stok_kg'      => 'required|numeric|min:0',
        ]);

        $pakan = InventariPakan::create($request->all());

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Stok pakan berhasil ditambahkan ke gudang.',
                'data' => $pakan
            ]);
        }

        return redirect()->back()->with('success', 'Stok pakan berhasil ditambahkan ke gudang.');
    }

    // Fungsi Catat Distribusi Pakan ke Kandang
    public function storeDistribusi(Request $request)
    {
        $request->validate([
            'tanggal_pemberian' => 'required|date',
            'pakan_id'          => 'required|exists:inventari_pakans,id',
            'kandang_id'        => 'required|exists:kandangs,id',
            'jumlah_kg'         => 'required|numeric|min:0.1',
        ]);

        try {
            DB::beginTransaction();

            $pakan = InventariPakan::findOrFail($request->pakan_id);

            // Cek apakah stok cukup
            if ($pakan->stok_kg < $request->jumlah_kg) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok pakan tidak mencukupi! Sisa stok: ' . $pakan->stok_kg . ' Kg'
                    ], 400);
                }
                return redirect()->back()->with('error', 'Stok pakan tidak mencukupi! Sisa stok: ' . $pakan->stok_kg . ' Kg')->withInput();
            }

            // Hitung total biaya (Harga real-time saat ini)
            $totalBiaya = $pakan->harga_per_kg * $request->jumlah_kg;

            // 1. Potong Stok Gudang
            $pakan->decrement('stok_kg', $request->jumlah_kg);

            // 2. Catat Distribusi
            $distribusi = DistribusiPakan::create([
                'kandang_id'        => $request->kandang_id,
                'pakan_id'          => $request->pakan_id,
                'tanggal_pemberian' => $request->tanggal_pemberian,
                'jumlah_kg'         => $request->jumlah_kg,
                'total_biaya'       => $totalBiaya,
            ]);

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pakan berhasil didistribusikan ke kandang.',
                    'data'    => $distribusi->load(['pakan', 'kandang'])
                ]);
            }

            return redirect()->back()->with('success', 'Pakan berhasil didistribusikan ke kandang.');

        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage())->withInput();
        }
    }
}
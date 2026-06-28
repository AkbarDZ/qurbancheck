<?php

namespace App\Http\Controllers;

use App\Models\LogBerat;
use App\Models\Ternak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogBeratController extends Controller
{
    public function index($ternakId)
    {
        $ternak = Ternak::findOrFail($ternakId);
        $logs = LogBerat::query()->where('ternak_id', $ternakId)->orderBy('tanggal_timbang', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'data' => $logs
        ]);
    }

    public function store(Request $request, $ternakId)
    {
        $validated = $request->validate([
            'berat_kg' => 'required|numeric|min:1',
            'tanggal_timbang' => 'required|date|before_or_equal:today',
        ], [
            'berat_kg.required' => 'Berat badan harus diisi.',
            'berat_kg.numeric' => 'Berat badan harus berupa angka.',
            'tanggal_timbang.required' => 'Tanggal timbang harus diisi.',
            'tanggal_timbang.date' => 'Format tanggal tidak valid.',
            'tanggal_timbang.before_or_equal' => 'Tanggal timbang tidak boleh di masa depan.',
        ]);

        try {
            $ternak = Ternak::findOrFail($ternakId);
            
            $log = LogBerat::create([
                'ternak_id' => $ternakId,
                'berat_kg' => $validated['berat_kg'],
                'tanggal_timbang' => $validated['tanggal_timbang']
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data berat berhasil ditambahkan',
                    'data' => $log
                ]);
            }
            return redirect()->back()->with('success', 'Data berat berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error('Error store LogBerat: ' . $e->getMessage());
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan sistem saat menyimpan data.'
                ], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat menyimpan data.')->withInput();
        }
    }
}

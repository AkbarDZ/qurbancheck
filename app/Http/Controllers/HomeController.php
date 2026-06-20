<?php

namespace App\Http\Controllers;

use App\Models\Ternak;
use App\Models\InventariPakan;
use App\Models\PemeriksaanSyariat;
use App\Models\DistribusiPakan;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Sapi Karantina (1x1)
        $karantinaCount = Ternak::where('is_karantina', true)->count();

        // 2. Peringatan Stok Pakan (2x1)
        // Find pakan with stock < 50
        $lowStockPakan = InventariPakan::where('stok_kg', '<', 50)
            ->orderBy('stok_kg', 'asc')
            ->first();

        // 3. Layak Syariat & SKKH (2x1)
        $layakSkkhCount = PemeriksaanSyariat::where('status', 'layak_qurban')
            ->whereNotNull('dokumen_skkh_id')
            ->distinct('ternak_id')
            ->count('ternak_id');

        // 4. Belum Diperiksa (1x1)
        $belumDiperiksaCount = Ternak::whereDoesntHave('pemeriksaanSyariat')->count();

        // 5. Total Populasi & Nilai Aset (2x2)
        $cows = Ternak::with(['logKesehatans.pengobatans'])->get();
        $totalPopulasi = $cows->count();
        $totalHpp = 0;

        $distribusiByKandang = DistribusiPakan::all()->groupBy('kandang_id');
        $populasiByKandang = Ternak::select('kandang_id', DB::raw('count(*) as count'))
            ->whereNotNull('kandang_id')
            ->groupBy('kandang_id')
            ->pluck('count', 'kandang_id');

        foreach ($cows as $ternak) {
            $modalAwal = (float) $ternak->harga_beli_awal;
            
            // Medis
            $biayaMedis = 0;
            foreach ($ternak->logKesehatans as $logKesehatan) {
                $biayaMedis += (float) $logKesehatan->pengobatans->sum('biaya_pengobatan');
            }
            
            // Pakan
            $biayaPakan = 0;
            if ($ternak->kandang_id && isset($distribusiByKandang[$ternak->kandang_id])) {
                $populasi = $populasiByKandang[$ternak->kandang_id] ?? 0;
                if ($populasi > 0) {
                    $createdDate = $ternak->created_at->format('Y-m-d');
                    foreach ($distribusiByKandang[$ternak->kandang_id] as $distribusi) {
                        if ($distribusi->tanggal_pemberian->format('Y-m-d') >= $createdDate) {
                            $biayaPakan += (float) ($distribusi->total_biaya / $populasi);
                        }
                    }
                }
            }
            
            $totalHpp += ($modalAwal + $biayaMedis + $biayaPakan);
        }

        // 6. Beban Pakan Bulan Ini (2x1)
        $bebanPakanBulanIni = DistribusiPakan::whereMonth('tanggal_pemberian', now()->month)
            ->whereYear('tanggal_pemberian', now()->year)
            ->sum('total_biaya');

        $totalHppFormatted = 'Rp ' . number_format($totalHpp, 0, ',', '.');
        if ($totalHpp >= 1000000000) {
            $totalHppFormatted = 'Rp ' . number_format($totalHpp / 1000000000, 1, ',', '.') . ' Miliar';
        } elseif ($totalHpp >= 1000000) {
            $totalHppFormatted = 'Rp ' . number_format($totalHpp / 1000000, 1, ',', '.') . ' Juta';
        }

        return view('dashboards.index', compact(
            'karantinaCount',
            'lowStockPakan',
            'layakSkkhCount',
            'belumDiperiksaCount',
            'totalPopulasi',
            'totalHpp',
            'totalHppFormatted',
            'bebanPakanBulanIni'
        ));
    }
}

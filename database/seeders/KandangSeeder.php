<?php

namespace Database\Seeders;

use App\Models\Kandang;
use Illuminate\Database\Seeder;

class KandangSeeder extends Seeder
{
    public function run(): void
    {
        $kandangs = [
            ['nama_kandang' => 'Kandang A (Penggemukan Sapi)', 'kapasitas_maksimal' => 15],
            ['nama_kandang' => 'Kandang B (Karantina Sapi)', 'kapasitas_maksimal' => 5],
            ['nama_kandang' => 'Kandang C (Kambing Premium)', 'kapasitas_maksimal' => 30],
            ['nama_kandang' => 'Kandang D (Domba Garut)', 'kapasitas_maksimal' => 25],
            ['nama_kandang' => 'Kandang E (Kerbau Rawa)', 'kapasitas_maksimal' => 10],
            ['nama_kandang' => 'Kandang F (Penyaringan Awal)', 'kapasitas_maksimal' => 20],
            ['nama_kandang' => 'Kandang G (Sapi Bali)', 'kapasitas_maksimal' => 12],
            ['nama_kandang' => 'Kandang H (Kambing Kacang)', 'kapasitas_maksimal' => 40],
            ['nama_kandang' => 'Kandang I (Domba Gembel)', 'kapasitas_maksimal' => 35],
            ['nama_kandang' => 'Kandang J (VIP Qurban)', 'kapasitas_maksimal' => 8],
        ];

        foreach ($kandangs as $kandang) {
            Kandang::updateOrCreate(
                ['nama_kandang' => $kandang['nama_kandang']],
                $kandang
            );
        }
    }
}

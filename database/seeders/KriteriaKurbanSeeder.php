<?php

namespace Database\Seeders;

use App\Models\KriteriaKurban;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KriteriaKurbanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kriteria = [
            // Kriteria FATAL (Tidak Sah / Tidak Layak)
            ['nama_kriteria' => 'Al-Awra (Buta Sebelah)', 'deskripsi' => 'Buta sebelah yang jelas/tampak', 'is_fatal' => true],
            ['nama_kriteria' => 'Al-Maridhah (Sakit Parah)', 'deskripsi' => 'Sakit yang tampak jelas secara klinis', 'is_fatal' => true],
            ['nama_kriteria' => 'Al-Arja (Pincang)', 'deskripsi' => 'Pincang yang sangat jelas sampai tidak bisa berjalan ke tempat sembelihan', 'is_fatal' => true],
            ['nama_kriteria' => 'Al-Kasirah (Sangat Kurus)', 'deskripsi' => 'Sangat kurus sampai tidak punya sumsum tulang', 'is_fatal' => true],
            
            // Kriteria MAKRUH / CACAT RINGAN (Masih Sah / Layak)
            ['nama_kriteria' => 'Telinga Sobek/Berlubang', 'deskripsi' => 'Daun telinga robek atau dilubangi untuk eartag', 'is_fatal' => false],
            ['nama_kriteria' => 'Tanduk Patah', 'deskripsi' => 'Tanduk patah sebagian yang tidak mempengaruhi otak', 'is_fatal' => false],
            ['nama_kriteria' => 'Gigi Tanggal (Ompong)', 'deskripsi' => 'Gigi rontok karena faktor usia, bukan penyakit', 'is_fatal' => false],
        ];

        foreach ($kriteria as $item) {
            KriteriaKurban::create($item);
        }
    }
}

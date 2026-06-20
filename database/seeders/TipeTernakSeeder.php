<?php

namespace Database\Seeders;

use App\Models\TipeTernak;
use Illuminate\Database\Seeder;

class TipeTernakSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['nama_jenis' => 'Sapi', 'umur_minimal_qurban' => 24],
            ['nama_jenis' => 'Kambing', 'umur_minimal_qurban' => 12],
            ['nama_jenis' => 'Domba', 'umur_minimal_qurban' => 12],
            ['nama_jenis' => 'Kerbau', 'umur_minimal_qurban' => 24],
        ];

        foreach ($types as $type) {
            TipeTernak::updateOrCreate(['nama_jenis' => $type['nama_jenis']], $type);
        }
    }
}

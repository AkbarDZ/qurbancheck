<?php

namespace Database\Seeders;

use App\Models\TipeTernak;
use App\Models\RasTernak;
use Illuminate\Database\Seeder;

class RasTernakSeeder extends Seeder
{
    public function run(): void
    {
        $sapi = TipeTernak::where('nama_jenis', 'Sapi')->first();
        $kambing = TipeTernak::where('nama_jenis', 'Kambing')->first();
        $domba = TipeTernak::where('nama_jenis', 'Domba')->first();
        $kerbau = TipeTernak::where('nama_jenis', 'Kerbau')->first();

        // Fallbacks in case TipeTernakSeeder hasn't run or types are missing
        $sapiId = $sapi ? $sapi->id : 1;
        $kambingId = $kambing ? $kambing->id : 2;
        $dombaId = $domba ? $domba->id : 3;
        $kerbauId = $kerbau ? $kerbau->id : 4;

        $rasList = [
            // Sapi
            [
                'tipe_ternak_id' => $sapiId,
                'nama_ras' => 'Sapi Bali',
                'deskripsi' => 'Sapi lokal asli Indonesia dengan warna kecoklatan dan kaki putih khas.',
            ],
            [
                'tipe_ternak_id' => $sapiId,
                'nama_ras' => 'Sapi Ongole (PO)',
                'deskripsi' => 'Sapi putih berpunuk besar yang sangat tangguh di iklim tropis.',
            ],
            [
                'tipe_ternak_id' => $sapiId,
                'nama_ras' => 'Sapi Limousin',
                'deskripsi' => 'Sapi potong unggul asal Prancis dengan tubuh sangat besar dan berotot.',
            ],
            [
                'tipe_ternak_id' => $sapiId,
                'nama_ras' => 'Sapi Simmental',
                'deskripsi' => 'Sapi dwi-guna asal Swiss, bercirikan warna merah bata dengan kepala putih.',
            ],
            [
                'tipe_ternak_id' => $sapiId,
                'nama_ras' => 'Sapi Madura',
                'deskripsi' => 'Sapi lokal Madura berwarna merah bata terang dengan daya tahan tubuh luar biasa.',
            ],
            // Kambing
            [
                'tipe_ternak_id' => $kambingId,
                'nama_ras' => 'Kambing Kacang',
                'deskripsi' => 'Kambing lokal Indonesia yang sangat adaptif dan mudah berkembang biak.',
            ],
            [
                'tipe_ternak_id' => $kambingId,
                'nama_ras' => 'Kambing Peranakan Etawa (PE)',
                'deskripsi' => 'Hasil persilangan kambing Etawa dengan Kacang, bercirikan telinga panjang terkulai.',
            ],
            [
                'tipe_ternak_id' => $kambingId,
                'nama_ras' => 'Kambing Jawa Randu',
                'deskripsi' => 'Kambing lokal hasil silangan PE dengan Kacang, sangat populer untuk qurban.',
            ],
            // Domba
            [
                'tipe_ternak_id' => $dombaId,
                'nama_ras' => 'Domba Garut',
                'deskripsi' => 'Domba ekor gemuk asal Jawa Barat dengan tanduk lingkar besar yang gagah.',
            ],
            [
                'tipe_ternak_id' => $dombaId,
                'nama_ras' => 'Domba Gembel (Ekor Tipis)',
                'deskripsi' => 'Domba lokal yang banyak dipelihara di Pulau Jawa dengan bulu wol tebal.',
            ],
            // Kerbau
            [
                'tipe_ternak_id' => $kerbauId,
                'nama_ras' => 'Kerbau Rawa',
                'deskripsi' => 'Kerbau lokal yang sering dipelihara di wilayah rawa dan persawahan Indonesia.',
            ],
        ];

        foreach ($rasList as $ras) {
            RasTernak::updateOrCreate(
                ['nama_ras' => $ras['nama_ras']],
                $ras
            );
        }
    }
}

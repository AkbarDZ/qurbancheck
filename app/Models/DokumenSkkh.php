<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumenSkkh extends Model
{
    protected $fillable = [
        'nomor_surat',
        'nama_dokter_pemeriksa',
        'instansi_penerbit',
        'tanggal_terbit',
        'dir_bukti_skkh',
    ];

    protected $casts = [
        'tanggal_terbit' => 'date',
    ];

    public function pemeriksaanSyariats()
    {
        return $this->hasMany(PemeriksaanSyariat::class);
    }
}

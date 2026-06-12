<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemeriksaanSyariat extends Model
{
    protected $fillable = [
        'ternak_id',
        'penanggungjawab_id',
        'dokumen_skkh_id',
        'tanggal_pemeriksaan',
        'status',
    ];

    protected $casts = [
        'tanggal_pemeriksaan' => 'date',
    ];

    public function ternak()
    {
        return $this->belongsTo(Ternak::class);
    }

    public function penanggungjawab()
    {
        return $this->belongsTo(User::class, 'penanggungjawab_id');
    }

    public function dokumenSkkh()
    {
        return $this->belongsTo(DokumenSkkh::class);
    }

    public function detailPemeriksaans()
    {
        return $this->hasMany(DetailPemeriksaan::class, 'pemeriksaan_id');
    }
}

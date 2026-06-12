<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPemeriksaan extends Model
{
    protected $fillable = [
        'pemeriksaan_id',
        'kriteria_id',
        'is_lolos',
        'catatan',
    ];

    protected $casts = [
        'is_lolos' => 'boolean',
    ];

    public function pemeriksaanSyariat()
    {
        return $this->belongsTo(PemeriksaanSyariat::class, 'pemeriksaan_id');
    }

    public function kriteriaKurban()
    {
        return $this->belongsTo(KriteriaKurban::class, 'kriteria_id');
    }
}

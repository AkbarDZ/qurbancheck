<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KriteriaKurban extends Model
{
    protected $fillable = [
        'nama_kriteria',
        'deskripsi',
        'is_fatal',
    ];

    protected $casts = [
        'is_fatal' => 'boolean',
    ];

    public function detailPemeriksaans()
    {
        return $this->hasMany(DetailPemeriksaan::class, 'kriteria_id');
    }
}

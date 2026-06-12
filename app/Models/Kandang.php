<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kandang extends Model
{
    protected $fillable = [
        'nama_kandang',
        'kapasitas_maksimal',
    ];

    protected $casts = [
        'kapasitas_maksimal' => 'integer',
    ];

    public function distribusiPakans()
    {
        return $this->hasMany(DistribusiPakan::class);
    }
}

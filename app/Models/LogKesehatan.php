<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogKesehatan extends Model
{
    protected $fillable = [
        'ternak_id',
        'tanggal_rekam',
        'gejala',
        'dir_foto_gejala',
    ];

    protected $casts = [
        'tanggal_rekam' => 'date',
    ];

    public function ternak()
    {
        return $this->belongsTo(Ternak::class);
    }

    public function pengobatans()
    {
        return $this->hasMany(Pengobatan::class);
    }
}

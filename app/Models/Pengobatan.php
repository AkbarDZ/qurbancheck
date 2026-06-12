<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengobatan extends Model
{
    protected $fillable = [
        'log_kesehatan_id',
        'nama_obat_tindakan',
        'biaya_pengobatan',
        'dosis',
        'catatan',
        'dikarantina',
    ];

    protected $casts = [
        'biaya_pengobatan' => 'decimal:2',
        'dikarantina' => 'boolean',
    ];

    public function logKesehatan()
    {
        return $this->belongsTo(LogKesehatan::class);
    }
}

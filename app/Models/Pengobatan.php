<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengobatan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'log_kesehatan_id',
        'nama_obat_tindakan',
        'biaya_pengobatan',
        'dosis',
        'catatan',
    ];

    protected $casts = [
        'biaya_pengobatan' => 'decimal:2',
    ];

    public function logKesehatan()
    {
        return $this->belongsTo(LogKesehatan::class);
    }
}

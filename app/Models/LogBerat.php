<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogBerat extends Model
{
    protected $fillable = [
        'ternak_id',
        'berat_kg',
        'tanggal_timbang',
    ];

    protected $casts = [
        'berat_kg' => 'decimal:2',
        'tanggal_timbang' => 'date',
    ];

    public function ternak()
    {
        return $this->belongsTo(Ternak::class);
    }
}

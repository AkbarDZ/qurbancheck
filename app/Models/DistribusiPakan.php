<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistribusiPakan extends Model
{
    protected $fillable = [
        'kandang_id',
        'pakan_id',
        'tanggal_pemberian',
        'jumlah_kg',
        'total_biaya',
    ];

    protected $casts = [
        'tanggal_pemberian' => 'date',
        'jumlah_kg' => 'decimal:2',
        'total_biaya' => 'decimal:2',
    ];

    public function kandang()
    {
        return $this->belongsTo(Kandang::class);
    }

    public function pakan()
    {
        return $this->belongsTo(InventariPakan::class, 'pakan_id');
    }
}

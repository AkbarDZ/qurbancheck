<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventariPakan extends Model
{
    protected $fillable = [
        'nama_pakan',
        'harga_per_kg',
        'stok_kg',
    ];

    protected $casts = [
        'harga_per_kg' => 'decimal:2',
        'stok_kg' => 'decimal:2',
    ];

    public function distribusiPakans()
    {
        return $this->hasMany(DistribusiPakan::class, 'pakan_id');
    }
}

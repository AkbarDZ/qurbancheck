<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipeTernak extends Model
{
    protected $table = 'tipe_ternaks';
    
    protected $fillable = [
        'nama_jenis',
        'umur_minimal_qurban',
    ];

    protected $casts = [
        'umur_minimal_qurban' => 'integer',
    ];

    public function rasTernaks()
    {
        return $this->hasMany(RasTernak::class);
    }
}

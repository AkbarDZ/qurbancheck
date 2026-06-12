<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RasTernak extends Model
{
    protected $fillable = [
        'tipe_ternak_id',
        'nama_ras',
        'deskripsi',
    ];

    public function tipeTernak()
    {
        return $this->belongsTo(TipeTernak::class);
    }

    public function ternaks()
    {
        return $this->hasMany(Ternak::class, 'ras_id');
    }
}

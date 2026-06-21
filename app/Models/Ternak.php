<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ternak extends Model
{
    protected $fillable = [
        'ras_id',
        'kandang_id',
        'nomor_eartag',
        'nama_panggilan',
        'harga_beli_awal',
        'tanggal_lahir',
        'jenis_kelamin',
        'gigi_tanggal',
        'dir_foto_hewan',
        'is_karantina',
    ];

    protected $casts = [
        'harga_beli_awal' => 'decimal:2',
        'tanggal_lahir' => 'date',
        'gigi_tanggal' => 'boolean',
        'is_karantina' => 'boolean',
    ];

    protected $appends = [
        'umur_bulan',
    ];

    public function getUmurBulanAttribute()
    {
        if (!$this->tanggal_lahir) {
            return 0;
        }
        return (int) $this->tanggal_lahir->diffInMonths(now());
    }

    public function kandang()
    {
        return $this->belongsTo(Kandang::class);
    }

    public function ras()
    {
        return $this->belongsTo(RasTernak::class, 'ras_id');
    }

    public function logBerats()
    {
        return $this->hasMany(LogBerat::class);
    }

    public function logKesehatans()
    {
        return $this->hasMany(LogKesehatan::class);
    }

    public function pemeriksaanSyariat()
    {
        return $this->hasMany(PemeriksaanSyariat::class);
    }
}

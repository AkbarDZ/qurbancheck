<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogKesehatan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'ternak_id', 
        'penanggung_jawab_id', 
        'tanggal_rekam', 
        'gejala', 
        'dir_foto_gejala',
        'status_karantina'
    ];

    protected $casts = [
        'status_karantina' => 'boolean'
    ];

    // Relasi ke tabel Users (Pekerja/Admin)
    public function penanggungJawab() {
        return $this->belongsTo(User::class, 'penanggung_jawab_id');
    }

    public function ternak() {
        return $this->belongsTo(Ternak::class);
    }

    public function pengobatans() {
        return $this->hasMany(Pengobatan::class);
    }

    // Cascade Soft Delete untuk Pengobatan
    protected static function boot()
    {
        parent::boot();
        static::deleting(function($log) {
            $log->pengobatans()->delete(); 
        });
    }
}
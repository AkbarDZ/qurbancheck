<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('log_kesehatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ternak_id')->constrained('ternaks')->onDelete('cascade');
            $table->boolean('status_karantina');
            // Kolom Penanggung Jawab (Relasi ke tabel users)
            // Dibuat nullable agar jika user/pekerja dihapus, data rekam medis tidak ikut hilang
            $table->foreignId('penanggung_jawab_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->date('tanggal_rekam');
            $table->text('gejala'); // Gejala klinis yang ditemukan
            $table->string('dir_foto_gejala')->nullable(); // Link bukti foto fisik
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_kesehatans');
    }
};

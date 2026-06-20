<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemeriksaan_syariats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ternak_id')->constrained('ternaks')->onDelete('cascade');
            $table->foreignId('penanggungjawab_id')->nullable()->constrained('users')->onDelete('cascade');
            
            // Relasi ke dokumen SKKH. Nullable karena bisa jadi pemeriksaan mandiri dulu.
            $table->foreignId('dokumen_skkh_id')->nullable()->constrained('dokumen_skkhs')->onDelete('set null');
            
            $table->date('tanggal_pemeriksaan');
            $table->enum('status', ['layak_qurban', 'tidak_layak_qurban'])->default('tidak_layak_qurban');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan_syariats');
    }
};
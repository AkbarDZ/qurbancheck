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
        Schema::create('dokumen_skkhs', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->unique()->nullable(); // Nomor seri SKKHQ
            $table->string('nama_dokter_pemeriksa');
            $table->string('instansi_penerbit')->nullable(); // Misal: Dinas Peternakan Kab. Sleman
            $table->date('tanggal_terbit');
            $table->string('dir_bukti_skkh'); // Path ke file PDF/Gambar di AWS S3
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_skkhs');
    }
};

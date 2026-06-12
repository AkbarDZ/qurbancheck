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
        Schema::create('ras_ternaks', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke jenis hewan (Sapi -> Limosin, Simental)
            $table->foreignId('tipe_ternak_id')->constrained('tipe_ternaks')->onDelete('cascade');
            $table->string('nama_ras');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ras_ternaks');
    }
};

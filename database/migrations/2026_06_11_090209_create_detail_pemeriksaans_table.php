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
        Schema::create('detail_pemeriksaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemeriksaan_id')->constrained('pemeriksaan_syariats')->onDelete('cascade');
            $table->foreignId('kriteria_id')->constrained('kriteria_kurbans')->onDelete('cascade');
            $table->boolean('is_lolos'); // true = Kondisi aman (tidak cacat), false = Mengalami cacat tersebut
            $table->text('catatan')->nullable(); // Catatan tambahan (misal: "Mata kanan katarak")
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pemeriksaans');
    }
};

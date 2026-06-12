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
        Schema::create('pengobatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('log_kesehatan_id')->constrained('log_kesehatans')->onDelete('cascade');
            $table->string('nama_obat_tindakan'); // Nama obat / tindakan medis
            $table->decimal('biaya_pengobatan', 12, 2)->nullable();
            $table->string('dosis')->nullable();
            $table->text('catatan')->nullable();
            $table->boolean('dikarantina')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengobatans');
    }
};

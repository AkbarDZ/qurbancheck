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
        Schema::create('kriteria_kurbans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kriteria'); // Al-Awra (Buta), Al-Maridhah (Sakit), dll
            $table->text('deskripsi')->nullable();
            $table->boolean('is_fatal')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kriteria_kurbans');
    }
};

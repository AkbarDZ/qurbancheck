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
        Schema::create('distribusi_pakans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kandang_id')->constrained('kandangs');
            $table->foreignId('pakan_id')->constrained('inventari_pakans');
            $table->date('tanggal_pemberian');
            $table->decimal('jumlah_kg', 8, 2);
            $table->decimal('total_biaya', 12, 2); // jumlah_kg * harga_per_kg saat itu
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribusi_pakans');
    }
};

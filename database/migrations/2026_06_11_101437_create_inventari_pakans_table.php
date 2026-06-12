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
        Schema::create('inventari_pakans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pakan'); // Misal: Konsentrat, Rumput Gajah
            $table->decimal('harga_per_kg', 12, 2); 
            $table->decimal('stok_kg', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventari_pakans');
    }
};

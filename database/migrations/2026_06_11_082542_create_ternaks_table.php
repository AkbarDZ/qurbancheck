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
        Schema::create('ternaks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ras_id')->constrained('ras_ternaks')->onDelete('cascade');
            $table->string('nomor_eartag')->unique();// eartag yang bukan dari pemerintah
            $table->string('nama_panggilan')->nullable(); // nama panggilan ternak
            $table->decimal('harga_beli_awal', 12, 2)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['jantan', 'betina']);
            $table->boolean('gigi_tanggal')->default(false); 
            $table->string('dir_foto_hewan')->nullable();// foto hewanya
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ternaks');
    }
};

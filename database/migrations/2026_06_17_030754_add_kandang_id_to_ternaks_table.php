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
        Schema::table('ternaks', function (Blueprint $table) {
            $table->foreignId('kandang_id')
                  ->nullable() 
                  ->after('ras_id')
                  ->constrained('kandangs')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ternaks', function (Blueprint $table) {
            $table->dropForeign(['kandang_id']);
            $table->dropColumn('kandang_id');
        });
    }
};

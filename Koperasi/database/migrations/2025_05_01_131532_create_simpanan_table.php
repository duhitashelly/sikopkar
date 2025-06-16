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
        Schema::create('simpanan', function (Blueprint $table) {
            $table->string('id_simpanan', 20)->primary();
            $table->string('id_anggota', 20);
            $table->string('jenis_simpanan', 100);
            $table->decimal('jumlah', 12, 2)->nullable();
            $table->date('tanggal')->nullable();

        
            $table->foreign('id_anggota')->references('id_anggota')->on('anggota');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simpanan');
    }
};
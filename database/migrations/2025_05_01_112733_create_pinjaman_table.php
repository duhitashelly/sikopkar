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
        Schema::create('pinjaman', function (Blueprint $table) {
            $table->string('id_pinjaman', 20)->primary();
            $table->string('id_anggota', 20);
            $table->decimal('jumlah', 12, 2);
            $table->integer('tenor');
            $table->date('tanggal_pinjaman')->nullable();
            $table->enum('status', ['Lunas', 'Belum Lunas'])->nullable();

            $table->foreign('id_anggota')->references('id_anggota')->on('anggota')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinjaman');
    }
};

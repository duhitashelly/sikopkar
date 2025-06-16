<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('angsuran', function (Blueprint $table) {
            $table->string('id_angsuran')->primary(); 
            $table->string('id_anggota', 20);
            $table->decimal('jumlah_angsuran', 15, 2);
            $table->date('tanggal');
            $table->timestamps();
        });
        
    }

    public function down(): void
    {
        Schema::dropIfExists('angsuran');
    }
};

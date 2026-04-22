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
    Schema::create('tarawih_schedules', function (Blueprint $table) {
        $table->id();
        $table->char('tahun_masehi', 4); // 2026
        $table->integer('hari_ke');      // 1 - 30 Ramadhan
        $table->date('tanggal_masehi');  
        
        // Relasi Imam (Semua ambil dari tabel imams)
        $table->foreignId('imam_id')->nullable()->constrained('imams')->onDelete('cascade');
        $table->foreignId('imam_cadangan_id')->nullable()->constrained('imams')->onDelete('set null');
        $table->foreignId('imam_subuh_id')->nullable()->constrained('imams')->onDelete('set null');
        
        // Data pendukung lainnya
        $table->string('bilal')->nullable();
        $table->string('penceramah')->nullable();
        $table->string('tema_ceramah')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarawih_schedules');
    }
};

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
        Schema::create('takjil_schedules', function (Blueprint $table) {
            $table->id();
            $table->char('tahun_masehi', 4); // Filter utama: 2026, 2027, dst
            $table->integer('hari_ke');      // 1 - 30 Ramadhan
            $table->date('tanggal_masehi');
            
            // Plotting Data
            $table->string('nama_donatur')->nullable(); // Diisi admin saat ada warga yang daftar
            $table->integer('jumlah_porsi')->default(0); // Admin tentukan porsi yang dibutuhkan
            
            // Pembeda fitur
            $table->enum('tipe', ['takjil', 'jaburan'])->default('takjil');
            
            // Status Konfirmasi
            $table->boolean('is_confirmed')->default(false); // Opsional: untuk rekap validasi
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('takjil_schedules');
    }
};

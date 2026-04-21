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
        Schema::create('qurban_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('qurban_animal_id')->constrained()->cascadeOnDelete();
            $table->string('nama_kelompok');
            $table->year('tahun_masehi');
            $table->string('tahun_hijriah');
            $table->enum('status_distribusi', ['belum_dipotong', 'proses_potong', 'selesai_distribusi'])->default('belum_dipotong');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qurban_groups');
    }
};

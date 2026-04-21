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
        Schema::create('jumat_berkah_donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained('jumat_berkah_schedules')->onDelete('cascade');
            $table->string('nama_donatur');
            $table->enum('jenis_donasi', ['Uang', 'Makanan']);
            $table->decimal('jumlah_donasi', 15, 2)->nullable(); // Lebih aman untuk nominal jutaan
            $table->integer('jumlah_porsi')->nullable(); // Hanya untuk makanan
            $table->boolean('status_distribusi')->default(false); // false = belum didistribusikan, true = sudah didistribusikan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jumat_berkah_donations');
    }
};

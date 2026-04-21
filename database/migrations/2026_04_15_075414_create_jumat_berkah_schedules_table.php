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
        Schema::create('jumat_berkah_schedules', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->integer('target_porsi');
            $table->enum('status', ['Open', 'Selesai'])->default('Open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jumat_berkah_schedules');
    }
};

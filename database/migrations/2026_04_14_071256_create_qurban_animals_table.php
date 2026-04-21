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
        Schema::create('qurban_animals', function (Blueprint $table) {
            $table->id();
            $table->string('nama_hewan');
            $table->integer('batas_peserta');
            $table->decimal('perkiraan_harga', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qurban_animals');
    }
};

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
       Schema::create('shohibul_qurbans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('qurban_group_id')->constrained()->cascadeOnDelete();
        $table->foreignId('jamaah_id')->nullable()->constrained()->nullOnDelete(); // Nullable jika warga luar
        $table->string('nama_peserta'); // Nama yang dibacakan saat disembelih
        $table->decimal('nominal_iuran', 15, 2);
        $table->enum('status_pembayaran', ['lunas', 'belum_lunas'])->default('belum_lunas');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shohibul_qurbans');
    }
};

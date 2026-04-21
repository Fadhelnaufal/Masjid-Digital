<?php

namespace Database\Seeders;

use App\Models\QurbanAnimal;
use App\Models\QurbanGroup;
use App\Models\ShohibulQurban;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin Takmir',
            'email' => 'admin@masjid.id', // Gunakan email ini untuk login
            'password' => Hash::make('password'), // Passwordnya
        ]);
        // 1. Buat Master Hewan
        $sapi = QurbanAnimal::create(['nama_hewan' => 'Sapi', 'batas_peserta' => 7, 'perkiraan_harga' => 21000000]);
        $kambing = QurbanAnimal::create(['nama_hewan' => 'Kambing', 'batas_peserta' => 1, 'perkiraan_harga' => 3000000]);

        // 2. Buat Kelompok Sapi (Sapi 1)
$sapiSatu = QurbanGroup::create([
    'qurban_animal_id' => $sapi->id,
    'nama_kelompok' => 'Sapi 1',
    'tahun_masehi' => 2026,
    'tahun_hijriah' => '1447H', // <--- Tambahkan baris ini
    'status_distribusi' => 'belum_dipotong'
]);

        // 3. Masukkan 7 Peserta (Shohibul Qurban) ke Sapi 1
        $pesertaSapi = [
            'Bpk Heru Mulyono', 'Ibu Khotim', 'Bpk Sulianto', 
            'Bpk Naf\'an Adina', 'Bpk Kahpi Yanur', 'Ibu Asih Widiyanto', 'Bpk Trisno'
        ];

        foreach ($pesertaSapi as $peserta) {
            ShohibulQurban::create([
                'qurban_group_id' => $sapiSatu->id,
                'nama_peserta' => $peserta,
                'nominal_iuran' => 3000000,
                'status_pembayaran' => 'belum_lunas'
            ]);
        }
    }
}
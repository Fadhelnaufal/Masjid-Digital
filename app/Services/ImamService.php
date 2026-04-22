<?php

namespace App\Services;

use App\Models\Imam;
use Illuminate\Support\Facades\DB;

class ImamService
{
    /**
     * Mengambil semua data imam untuk tabel/list
     */
    public function getAllImams()
    {
        return Imam::orderBy('nama', 'asc')->get();
    }

    /**
     * Logic Simpan Imam Baru
     */
    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Merapikan nama: "ahmad subarjo" -> "Ahmad Subarjo"
            $data['nama'] = ucwords(strtolower($data['nama']));
            
            return Imam::create($data);
        });
    }

    /**
     * Logic Update Data Imam
     */
    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $imam = Imam::findOrFail($id);
            
            if (isset($data['nama'])) {
                $data['nama'] = ucwords(strtolower($data['nama']));
            }

            $imam->update($data);
            return $imam;
        });
    }

    /**
     * Logic Hapus Imam
     * Disini kita tambahkan proteksi agar tidak merusak data jadwal
     */
    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $imam = Imam::findOrFail($id);

            // Cek apakah imam ini sudah masuk di jadwal tarawih manapun
            // Kita gunakan exists() karena lebih cepat daripada count()
            if ($imam->tarawihSchedules()->exists()) {
                throw new \Exception("Imam '{$imam->nama}' tidak bisa dihapus karena masih terikat di Jadwal Tarawih.");
            }

            return $imam->delete();
        });
    }
}
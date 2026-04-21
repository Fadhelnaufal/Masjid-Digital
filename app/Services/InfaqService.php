<?php

namespace App\Services;

use App\Models\Infaq;
use Carbon\Carbon;

class InfaqService
{
    /**
     * Mengambil semua data infaq.
     * Kita tambahkan parameter limit jika ingin digunakan untuk dashboard,
     * tapi default-nya mengambil semua untuk keperluan Export.
     */
    public function getAllInfaqs($limit = null)
    {
        $query = Infaq::orderBy('tanggal', 'desc');
        
        if ($limit) {
            return $query->limit($limit)->get();
        }

        return $query->get();
    }

    /**
     * Menghitung statistik kas infaq.
     * Saya tambahkan konversi (float) agar saat dikirim ke Vue, 
     * typeof datanya adalah 'number' dan fungsi formatRupiah() berjalan otomatis.
     */
    public function getInfaqStats()
    {
        return [
            'total_nominal'   => (float) Infaq::sum('nominal'),
            'total_transaksi' => Infaq::count(),
            'bulan_ini'       => (float) Infaq::whereMonth('tanggal', Carbon::now()->month)
                                        ->whereYear('tanggal', Carbon::now()->year)
                                        ->sum('nominal'),
        ];
    }

    public function storeInfaq(array $data)
    {
        // Pastikan nominal disimpan sebagai angka bersih
        return Infaq::create([
            'tanggal'    => $data['tanggal'],
            'nominal'    => $data['nominal'],
            'keterangan' => $data['keterangan'] ?? 'Infaq Tanpa Keterangan',
            'status'     => 'Diterima'
        ]);
    }

    public function updateInfaq($id, array $data)
    {
        $infaq = Infaq::findOrFail($id);
        $infaq->update($data);
        return $infaq;
    }

    public function deleteInfaq($id)
    {
        $infaq = Infaq::findOrFail($id);
        return $infaq->delete();
    }
}
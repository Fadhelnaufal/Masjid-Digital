<?php

namespace App\Services;

use App\Models\JumatBerkahSchedule;
use App\Models\JumatBerkahDonation;
use Illuminate\Support\Facades\DB;

class JumatBerkahService
{
    // Pusatkan harga porsi di sini. Gampang diubah jika harga beras naik.
    const HARGA_PER_PORSI = 10000;

    public function getAllSchedules()
    {
        $schedules = JumatBerkahSchedule::withCount([
                'donations as terumpul_porsi' => function ($query) {
                    $query->select(DB::raw('sum(jumlah_porsi)'))
                          ->where('status_distribusi', true);
                },
                'donations as total_dana' => function ($query) {
                    $query->select(DB::raw('sum(jumlah_donasi)'))
                          ->where('status_distribusi', true);
                }
            ])
            ->orderBy('tanggal', 'desc')
            ->get();

        // Kita tambahkan data kalkulasi porsi sebelum dikirim ke Controller
        return $schedules->map(fn($schedule) => $this->formatScheduleData($schedule));
    }

    public function getScheduleById($id)
    {
        $schedule = JumatBerkahSchedule::with(['donations' => function($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->withCount([
                'donations as terumpul_porsi' => function ($query) {
                    $query->select(DB::raw('sum(jumlah_porsi)'))
                          ->where('status_distribusi', true);
                },
                'donations as total_dana' => function ($query) {
                    $query->select(DB::raw('sum(jumlah_donasi)'))
                          ->where('status_distribusi', true);
                }
            ])
            ->findOrFail($id);

        return $this->formatScheduleData($schedule);
    }

    /**
     * Logic Konversi Porsi & Progress (Centralized)
     */
    private function formatScheduleData($schedule)
    {
        $porsiDariUang = floor(($schedule->total_dana ?? 0) / self::HARGA_PER_PORSI);
        $totalPorsi = ($schedule->terumpul_porsi ?? 0) + $porsiDariUang;
        
        // Hitung persentase progres
        $target = $schedule->target_porsi > 0 ? $schedule->target_porsi : 1;
        $persentase = round(($totalPorsi / $target) * 100);

        // Tambahkan atribut "virtual" agar Vue tinggal pakai
        $schedule->porsi_uang = $porsiDariUang;
        $schedule->total_akumulasi = $totalPorsi;
        $schedule->persentase = $persentase > 100 ? 100 : $persentase;
        
        return $schedule;
    }

    public function storeSchedule(array $data)
    {
        return JumatBerkahSchedule::create([
            'tanggal' => $data['tanggal'],
            'target_porsi' => $data['target_porsi'],
            'status' => 'Open'
        ]);
    }

    public function updateSchedule($id, array $data)
    {
        $schedule = JumatBerkahSchedule::findOrFail($id);
        $schedule->update([
            'tanggal' => $data['tanggal'],
            'target_porsi' => $data['target_porsi'],
        ]);
        return $schedule;
    }

    public function closeSchedule($id)
    {
        $schedule = JumatBerkahSchedule::findOrFail($id);
        $schedule->update(['status' => 'Selesai']);
        return $schedule;
    }

    public function reopenSchedule($id)
    {
        $schedule = JumatBerkahSchedule::findOrFail($id);
        $schedule->update(['status' => 'Open']);
        return $schedule;
    }

    public function deleteSchedule($id)
    {
        return JumatBerkahSchedule::findOrFail($id)->delete();
    }

    // ==========================================
    // LOGIKA DONASI
    // ==========================================

    public function storeDonation($scheduleId, array $data) 
    {
        $schedule = JumatBerkahSchedule::findOrFail($scheduleId);

        // Proteksi: Jika sudah Selesai, jangan izinkan input donasi baru
        if ($schedule->status === 'Selesai') {
            throw new \Exception("Program sudah selesai, tidak bisa menambah donasi.");
        }
        
        return $schedule->donations()->create([
            'nama_donatur' => $data['nama_donatur'],
            'jenis_donasi' => $data['jenis_donasi'],
            'jumlah_donasi' => $data['jenis_donasi'] === 'Uang' ? ($data['jumlah_donasi'] ?? 0) : 0,
            'jumlah_porsi' => $data['jenis_donasi'] === 'Makanan' ? ($data['jumlah_porsi'] ?? 0) : 0,
            'status_distribusi' => false,
        ]);
    }

    public function toggleDonationReception($id)
    {
        $donation = JumatBerkahDonation::findOrFail($id);
        
        // Proteksi: Jangan bisa toggle jika jadwal sudah diclose
        if ($donation->schedule->status === 'Selesai') {
             throw new \Exception("Status program sudah selesai.");
        }

        $donation->status_distribusi = !$donation->status_distribusi;
        $donation->save();
        
        return $donation;
    }

    public function deleteDonation($id)
    {
        return JumatBerkahDonation::findOrFail($id)->delete();
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDonationRequest;
use App\Http\Requests\StoreJumatBerkahRequest;
use App\Services\JumatBerkahService;
use Inertia\Inertia;

class JumatBerkahController extends Controller
{
    protected $jumatBerkahService;

    public function __construct(JumatBerkahService $jumatBerkahService)
    {
        $this->jumatBerkahService = $jumatBerkahService;
    }

    /**
     * Menampilkan daftar semua jadwal Jumat Berkah.
     */
    public function index()
    {
        return Inertia::render('Admin/JumatBerkah/Index', [
            'schedules' => $this->jumatBerkahService->getAllSchedules()
        ]);
    }

    /**
     * Menyimpan jadwal baru.
     */
    public function store(StoreJumatBerkahRequest $request)
    {
        $this->jumatBerkahService->storeSchedule($request->validated());
        return redirect()->back()->with('success', 'Jadwal berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail jadwal dan daftar donasi di dalamnya.
     */
    public function show($id) 
    {
        return Inertia::render('Admin/JumatBerkah/Show', [
            'schedule' => $this->jumatBerkahService->getScheduleById($id)
        ]);
    }

    /**
     * Memperbarui informasi jadwal (Tanggal & Target).
     */
    public function update(StoreJumatBerkahRequest $request, $id) 
    {
        $this->jumatBerkahService->updateSchedule($id, $request->validated());
        return redirect()->back()->with('success', 'Jadwal berhasil diperbarui!');
    }

    /**
     * Mengubah status jadwal menjadi 'Selesai'.
     */
    public function close($id) 
    {
        $this->jumatBerkahService->closeSchedule($id);
        return redirect()->back()->with('success', 'Jadwal berhasil ditutup!');
    }

    /**
     * Mengubah status jadwal kembali menjadi 'Open'.
     */
    public function reopen($id) 
    {
        $this->jumatBerkahService->reopenSchedule($id);
        return redirect()->back()->with('success', 'Jadwal berhasil dibuka kembali!');
    }

    /**
     * Menghapus jadwal beserta seluruh data donasi terkait.
     */
    public function destroy($id) 
    {
        $this->jumatBerkahService->deleteSchedule($id);
        return redirect()->route('jumat-berkah.index')->with('success', 'Jadwal berhasil dihapus!');
    }

    // ==========================================
    // MANAJEMEN DONASI (Penerimaan Donatur)
    // ==========================================

    /**
     * Mencatat kontribusi donasi baru ke dalam jadwal tertentu.
     */
    public function storeDonation(StoreDonationRequest $request, $id) 
    {
        $this->jumatBerkahService->storeDonation($id, $request->validated());
        return redirect()->back()->with('success', 'Donasi berhasil dicatat!');
    }

    /**
     * Konfirmasi Serah Terima: Mengubah status 'Diterima' atau 'Belum Diterima'.
     */
    public function toggleDonation($id) // Nama method disesuaikan dengan route
    {
        $this->jumatBerkahService->toggleDonationReception($id);
        return redirect()->back()->with('success', 'Status penerimaan diperbarui!');
    }

    /**
     * Menghapus data donasi donatur.
     */
    public function destroyDonation($id)
    {
        // Pastikan fungsi deleteDonation ada di JumatBerkahService
        $this->jumatBerkahService->deleteDonation($id);
        return redirect()->back()->with('success', 'Catatan donasi berhasil dihapus!');
    }
}
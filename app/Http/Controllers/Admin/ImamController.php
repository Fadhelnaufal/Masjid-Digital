<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ImamService; // <--- Menggunakan service yang benar
use Illuminate\Http\Request;
use Inertia\Inertia;

class ImamController extends Controller
{
    protected $imamService;

    /**
     * Sekarang kita Inject ImamService, bukan RamadhanService.
     * Pelayan (Controller) sekarang punya Koki spesifik untuk urusan Imam.
     */
    public function __construct(ImamService $imamService)
    {
        $this->imamService = $imamService;
    }

    /**
     * Menampilkan Halaman Master Data Imam
     */
    public function index()
    {
        return Inertia::render('Admin/DataImam/Index', [
            'imams' => $this->imamService->getAllImams(),
        ]);
    }

    /**
     * Proses Simpan Imam Baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'   => 'required|string|max:255',
            'no_hp'  => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        // Serahkan data ke Koki Imam
        $this->imamService->store($validated);

        return redirect()->back()->with('success', 'Data Imam berhasil ditambahkan.');
    }

    /**
     * Proses Update Data Imam
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama'   => 'required|string|max:255',
            'no_hp'  => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        // Koki Imam yang tahu cara update-nya
        $this->imamService->update($id, $validated);

        return redirect()->back()->with('success', 'Data Imam berhasil diperbarui.');
    }

    /**
     * Proses Hapus Data Imam
     */
    public function destroy($id)
    {
        try {
            // Service akan mengecek apakah imam ini sedang bertugas atau tidak
            $this->imamService->delete($id);
            
            return redirect()->back()->with('success', 'Data Imam berhasil dihapus.');
        } catch (\Exception $e) {
            // Jika Koki bilang "Jangan dihapus!", Pelayan sampaikan alasannya ke user
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
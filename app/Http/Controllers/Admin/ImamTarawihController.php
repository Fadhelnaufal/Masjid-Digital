<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\RamadhanService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ImamTarawihController extends Controller
{
    protected $ramadhanService;

    public function __construct(RamadhanService $ramadhanService)
    {
        $this->ramadhanService = $ramadhanService;
    }

    public function index(Request $request)
    {
        // Menangkap tahun dari dropdown Vue, default ke tahun server sekarang
        $tahun = $request->query('tahun', date('Y'));

        return Inertia::render('Admin/Ramadhan/ImamTarawih/Index', [
            'schedules' => $this->ramadhanService->getTarawihSchedules($tahun),
            'masterImam' => $this->ramadhanService->getImamList(),
            'currentYear' => (string) $tahun,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'imam_id'           => 'required|exists:imams,id',
            'imam_cadangan_id'  => 'nullable|exists:imams,id',
            'imam_subuh_id'     => 'nullable|exists:imams,id',
            'bilal'             => 'nullable|string|max:100',
            'penceramah'        => 'nullable|string|max:100',
            'tema_ceramah'      => 'nullable|string|max:255',
        ]);

        try {
            $this->ramadhanService->updateTarawihPlotting($id, $validated);
            return redirect()->back()->with('success', 'Petugas berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function generate(Request $request)
{
    $request->validate([
        'tahun' => 'required|numeric',
        'tanggal_mulai' => 'required|date_format:Y-m-d' // Validasi format transform
    ]);

    try {
        $this->ramadhanService->generateRamadhanYear($request->tahun, $request->tanggal_mulai);
        return redirect()->back()->with('success', 'Jadwal Ramadhan berhasil dibuat!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
    }
}
}
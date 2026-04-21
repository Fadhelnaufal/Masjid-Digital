<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\InfaqService;
use Illuminate\Http\Request;
use Inertia\Inertia;
// Import tambahan untuk Export
use App\Exports\InfaqExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class InfaqController extends Controller
{
    protected $infaqService;

    public function __construct(InfaqService $infaqService)
    {
        $this->infaqService = $infaqService;
    }

    public function index()
    {
        return Inertia::render('Admin/Infaq/Index', [
            'infaqs' => $this->infaqService->getAllInfaqs(),
            'stats' => $this->infaqService->getInfaqStats()
        ]);
    }

    // --- METHOD EXPORT BARU ---
    public function export($format = 'excel')
{
    $infaqs = $this->infaqService->getAllInfaqs();
    
    // Generate Tracking Code
    $trackingCode = 'INF-' . str_pad(auth()->id(), 2, '0', STR_PAD_LEFT) . '-' . now()->format('Ymd') . '-' . strtoupper(substr(md5(now() . auth()->id()), 0, 4));

    // Nama file yang lebih cantik
    $fileName = 'Laporan_Infaq_Sambit_' . now()->format('d_M_Y') . '.pdf';

    if ($format === 'pdf') {
        $pdf = Pdf::loadView('exports.infaq_pdf', compact('infaqs', 'trackingCode'));
        
        return $pdf->setPaper('a4', 'portrait')
                   ->setOption(['isRemoteEnabled' => true]) // Aktifkan jika nanti pakai gambar/logo
                   ->stream($fileName, [
                       'Content-Disposition' => 'inline; filename="' . $fileName . '"'
                   ]);
    }

    // Excel
    return Excel::download(new InfaqExport($infaqs, $trackingCode), $fileName . '.xlsx');
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal'      => 'required|date',
            'nominal'      => 'required|numeric|min:0',
            'keterangan'   => 'nullable|string',
        ]);

        $this->infaqService->storeInfaq($validated);
        return redirect()->back()->with('success', 'Infaq berhasil dicatat!');
    }

    public function destroy($id)
    {
        $this->infaqService->deleteInfaq($id);
        return redirect()->back()->with('success', 'Catatan infaq berhasil dihapus!');
    }
}
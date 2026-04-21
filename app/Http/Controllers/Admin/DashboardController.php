<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PrayerTimeService;
use App\Services\InfaqService;
use App\Services\JumatBerkahService;
use App\Models\Infaq;
use App\Models\JumatBerkahDonation;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        private PrayerTimeService $prayerService,
        private InfaqService $infakService,
        private JumatBerkahService $jumatBerkahService
    ) {}

    public function index(): Response
    {
        // 1. DATA UNTUK CHART (6 BULAN TERAKHIR)
        $chartRaw = collect(range(5, 0))->map(function($i) {
            $date = now()->subMonths($i);
            return [
                'month' => $date->format('M'),
                'total' => Infaq::whereMonth('tanggal', $date->month)
                            ->whereYear('tanggal', $date->year)
                            ->sum('nominal')
            ];
        });

        $chartData = [
            'labels' => $chartRaw->pluck('month'),
            'datasets' => [
                [
                    'label' => 'Pemasukan Infaq',
                    'data' => $chartRaw->pluck('total'),
                    'backgroundColor' => '#10b981',
                    'borderRadius' => 8,
                    'barThickness' => 20,
                ]
            ]
        ];

        // 2. AMBIL JADWAL SHALAT & FILTER (Hanya ambil 5 waktu utama)
        // Ini agar data seperti "Midnight", "Imsak", dll tidak ikut bocor ke Inspect Element
        $rawJadwal = $this->prayerService->getCachedPrayerTimes('Ponorogo');
        $filteredJadwal = collect($rawJadwal)->only(['Fajr', 'Dhuhr', 'Asr', 'Maghrib', 'Isha'])->toArray();
        
        // 3. AMBIL STATS INFAQ
        $infaqStats = $this->infakService->getInfaqStats();

        // 4. AMBIL STATS DONATUR JUMAT BERKAH
        $totalDonaturJumat = JumatBerkahDonation::where('status_distribusi', true)->count();

        // 5. DATA JAMAAH & QURBAN
        $totalJamaah = class_exists(\App\Models\Jamaah::class) ? \App\Models\Jamaah::count() : 850;
        $totalQurban = class_exists(\App\Models\QurbanAnimal::class) ? \App\Models\QurbanAnimal::count() : 12;

        return Inertia::render('Dashboard', [
            // Gunakan jadwal yang sudah difilter
            'prayerTimes' => $filteredJadwal,
            
            'chartData' => $chartData,

            'stats' => [
                [
                    'label' => 'Total Saldo Kas',
                    'value' => 'Rp ' . number_format($infaqStats['total_nominal'], 0, ',', '.'),
                    'icon' => 'Wallet',
                    'color' => 'text-emerald-600',
                    'bg' => 'bg-emerald-50',
                ],
                [
                    'label' => 'Total Jamaah',
                    'value' => $totalJamaah . ' Orang',
                    'icon' => 'Users',
                    'color' => 'text-blue-600',
                    'bg' => 'bg-blue-50',
                ],
                [
                    'label' => 'Donatur Jumat',
                    'value' => $totalDonaturJumat . ' Orang',
                    'icon' => 'HandHeart',
                    'color' => 'text-purple-600',
                    'bg' => 'bg-purple-50',
                ],
                [
                    'label' => 'Hewan Qurban',
                    'value' => $totalQurban . ' Ekor',
                    'icon' => 'TrendingUp',
                    'color' => 'text-orange-600',
                    'bg' => 'bg-orange-50',
                ],
            ],

            // 6. FILTER AKTIVITAS TERKINI (Hanya kirim kolom yang ditampilkan di UI)
            // Jangan kirim seluruh object Model (biar created_at/updated_at aslinya tidak kelihatan)
            'recentActivities' => Infaq::latest()->take(3)->get()->map(fn($item) => [
                'id' => $item->id,
                'user' => 'Hamba Allah',
                'action' => $item->keterangan ?? 'Donasi Infaq',
                'time' => $item->created_at->diffForHumans(), // Sudah berupa string ramah manusia
                'amount' => 'Rp ' . number_format($item->nominal, 0, ',', '.')
            ])
        ]);
    }
}
<?php

namespace App\Services;

use App\Models\Imam;
use App\Models\TarawihSchedule;
use App\Models\TakjilSchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RamadhanService
{
    // =========================================================================
    // 1. MANAJEMEN IMAM (MASTER DATA SDM)
    // =========================================================================

    public function getImamList()
    {
        return Imam::orderBy('nama', 'asc')->get();
    }

    public function storeImam(array $data)
    {
        return DB::transaction(function () use ($data) {
            $data['nama'] = ucwords(strtolower($data['nama']));
            return Imam::create($data);
        });
    }

    public function updateImam($id, array $data)
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

    public function deleteImam($id)
    {
        return DB::transaction(function () use ($id) {
            $imam = Imam::findOrFail($id);
            
            // Cek keterpautan di jadwal (Mencegah error foreign key)
            $isUsed = TarawihSchedule::where('imam_id', $id)
                ->orWhere('imam_cadangan_id', $id)
                ->orWhere('imam_subuh_id', $id)
                ->exists();

            if ($isUsed) {
                throw new \Exception("Ustadz ini tidak bisa dihapus karena sudah masuk dalam jadwal plotting.");
            }
            
            return $imam->delete();
        });
    }

    // =========================================================================
    // 2. DATA IMAM TARAWIH (PLOTTING PENUGASAN)
    // =========================================================================

    public function getTarawihSchedules($tahun)
    {
        return TarawihSchedule::with(['imam', 'imamCadangan', 'imamSubuh'])
            ->where('tahun_masehi', $tahun)
            ->orderBy('hari_ke', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'id'                => $item->id,
                    'hari_ke'           => $item->hari_ke,
                    'tanggal_masehi'    => $item->tanggal_masehi,
                    // Format tanggal Indonesia (Contoh: 01 Mar 2026)
                    'tanggal'           => Carbon::parse($item->tanggal_masehi)->translatedFormat('d M Y'),
                    'imam_id'           => $item->imam_id,
                    'imam_cadangan_id'  => $item->imam_cadangan_id,
                    'imam_subuh_id'     => $item->imam_subuh_id,
                    'nama_imam'         => $item->imam?->nama ?? 'Belum Diplot',
                    'nama_cadangan'     => $item->imamCadangan?->nama ?? '-',
                    'nama_subuh'        => $item->imamSubuh?->nama ?? '-',
                    'bilal'             => $item->bilal,
                    'penceramah'        => $item->penceramah,
                    'tema_ceramah'      => $item->tema_ceramah,
                ];
            });
    }

    public function updateTarawihPlotting($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $schedule = TarawihSchedule::findOrFail($id);
            
            // Logic Bisnis: Otomatisasi Imam Subuh jika tidak diisi manual
            if (empty($data['imam_subuh_id']) && !empty($data['imam_id'])) {
                $data['imam_subuh_id'] = $data['imam_id'];
            }

            // Update hanya kolom yang ada di formulir plotting
            $schedule->update(array_intersect_key($data, array_flip([
                'imam_id', 'imam_cadangan_id', 'imam_subuh_id', 'bilal', 'penceramah', 'tema_ceramah'
            ])));
            
            return $schedule;
        });
    }

    // =========================================================================
    // 3. GENERATE JADWAL OTOMATIS (SETUP AWAL TAHUN)
    // =========================================================================

    public function generateRamadhanYear($tahun, $tglMulai)
{
    return DB::transaction(function () use ($tahun, $tglMulai) {
        $startDate = Carbon::parse($tglMulai);
        
        for ($i = 1; $i <= 30; $i++) {
            $currentDate = $startDate->copy()->addDays($i - 1)->format('Y-m-d');
            
            TarawihSchedule::updateOrCreate([
                'tahun_masehi' => $tahun,
                'hari_ke'      => $i
            ], [
                'tanggal_masehi' => $currentDate,
            ]);

            if (class_exists(\App\Models\TakjilSchedule::class)) {
                foreach (['takjil', 'jaburan'] as $tipe) {
                    TakjilSchedule::updateOrCreate([
                        'tahun_masehi' => $tahun,
                        'hari_ke'      => $i,
                        'tipe'         => $tipe
                    ], [
                        'tanggal_masehi' => $currentDate,
                        'nama_donatur'   => null,
                        'jumlah_porsi'   => 0
                    ]);
                }
            }
        }
        return true;
    });
}

    // =========================================================================
    // 4. TAKJIL & JABURAN (UNTUK FITUR SELANJUTNYA)
    // =========================================================================

    public function getTakjilSchedules($tahun, $tipe)
    {
        return TakjilSchedule::where('tahun_masehi', $tahun)
            ->where('tipe', $tipe)
            ->orderBy('hari_ke', 'asc')
            ->get();
    }

    public function updateTakjilPlotting($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $slot = TakjilSchedule::findOrFail($id);
            if (isset($data['nama_donatur'])) {
                $data['nama_donatur'] = ucwords(strtolower($data['nama_donatur']));
            }
            $slot->update($data);
            return $slot;
        });
    }
}
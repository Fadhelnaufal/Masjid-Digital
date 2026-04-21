<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PrayerTimeService
{
    public function getCachedPrayerTimes($city = 'Ponorogo')
    {
        return Cache::remember("prayer_times_{$city}", 86400, function () use ($city) {
            try {
                // Tambahkan withoutVerifying() untuk menghindari error SSL di Localhost
                $response = Http::withoutVerifying()->get('https://api.aladhan.com/v1/timingsByCity', [
                    'city'    => $city,
                    'country' => 'Indonesia',
                    'method'  => 11,
                ]);

                if ($response->successful()) {
                    return $response->json()['data']['timings'];
                }

                Log::error("API Aladhan gagal merespon untuk kota: {$city}");
            } catch (\Exception $e) {
                Log::error("Gagal mengambil jadwal shalat: " . $e->getMessage());
            }

            // FALLBACK DATA: Jika API gagal, tetap kirim data agar dashboard tidak "Jadwal Tidak Tersedia"
            return [
                "Fajr" => "04:12",
                "Dhuhr" => "11:38",
                "Asr" => "14:55",
                "Maghrib" => "17:35",
                "Isha" => "18:45",
            ];
        });
    }
}
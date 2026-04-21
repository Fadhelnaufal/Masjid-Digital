<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
            // Daftarkan service untuk Jumat Berkah
            $this->app->singleton(\App\Services\JumatBerkahService::class, function ($app) {
                return new \App\Services\JumatBerkahService();
            });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
    }
}

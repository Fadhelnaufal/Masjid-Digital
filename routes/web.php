<?php

use App\Http\Controllers\Admin\InfaqController;
use App\Http\Controllers\Admin\JumatBerkahController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
// Semua route di dalam group ini WAJIB login dulu
Route::middleware('auth')->group(function () {
    
    // Route Profile Bawaan
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // === ROUTE ADMIN: JUMAT BERKAH ===
    
    // Manajemen Jadwal
    Route::get('/admin/jumat-berkah', [JumatBerkahController::class, 'index'])->name('jumat-berkah.index');
    Route::post('/admin/jumat-berkah', [JumatBerkahController::class, 'store'])->name('jumat-berkah.store');
    Route::get('/admin/jumat-berkah/{id}', [JumatBerkahController::class, 'show'])->name('jumat-berkah.show');
    Route::patch('/admin/jumat-berkah/{id}', [JumatBerkahController::class, 'update'])->name('jumat-berkah.update');
    Route::delete('/admin/jumat-berkah/{id}', [JumatBerkahController::class, 'destroy'])->name('jumat-berkah.destroy');
    
    // Kontrol Status Jadwal
    Route::post('/admin/jumat-berkah/{id}/close', [JumatBerkahController::class, 'close'])->name('jumat-berkah.close');
    Route::post('/admin/jumat-berkah/{id}/reopen', [JumatBerkahController::class, 'reopen'])->name('jumat-berkah.reopen');
    
    // === MANAJEMEN DONASI ===
    
    // 1. Simpan Donasi Baru
    Route::post('/admin/jumat-berkah/{id}/donations', [JumatBerkahController::class, 'storeDonation'])->name('jumat-berkah.donations.store');
    
    // 2. Toggle Penerimaan (Serah Terima)
    Route::post('/admin/donations/{id}/toggle', [JumatBerkahController::class, 'toggleDonation'])->name('jumat-berkah.donations.toggle');
    
    // 3. Hapus Catatan Donasi
    Route::delete('/admin/donations/{id}', [JumatBerkahController::class, 'destroyDonation'])->name('jumat-berkah.donations.destroy');

    // === ROUTE ADMIN: INFAQ ===
    Route::get('/admin/infaq', [InfaqController::class, 'index'])->name('infaq.index');
    Route::post('/admin/infaq', [InfaqController::class, 'store'])->name('infaq.store');
    Route::delete('/admin/infaq/{id}', [InfaqController::class, 'destroy'])->name('infaq.destroy');
    Route::get('/admin/infaq/export/{format?}', [InfaqController::class, 'export'])->name('infaq.export');


}); // Penutup Middleware Auth

require __DIR__.'/auth.php';
<?php

use App\Http\Controllers\Admin\InfaqController;
use App\Http\Controllers\Admin\JumatBerkahController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ImamController;
use App\Http\Controllers\Admin\ImamTarawihController; // Import Controller Baru
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

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
    Route::get('/admin/jumat-berkah', [JumatBerkahController::class, 'index'])->name('jumat-berkah.index');
    Route::post('/admin/jumat-berkah', [JumatBerkahController::class, 'store'])->name('jumat-berkah.store');
    Route::get('/admin/jumat-berkah/{id}', [JumatBerkahController::class, 'show'])->name('jumat-berkah.show');
    Route::patch('/admin/jumat-berkah/{id}', [JumatBerkahController::class, 'update'])->name('jumat-berkah.update');
    Route::delete('/admin/jumat-berkah/{id}', [JumatBerkahController::class, 'destroy'])->name('jumat-berkah.destroy');
    
    Route::post('/admin/jumat-berkah/{id}/close', [JumatBerkahController::class, 'close'])->name('jumat-berkah.close');
    Route::post('/admin/jumat-berkah/{id}/reopen', [JumatBerkahController::class, 'reopen'])->name('jumat-berkah.reopen');
    
    Route::post('/admin/jumat-berkah/{id}/donations', [JumatBerkahController::class, 'storeDonation'])->name('jumat-berkah.donations.store');
    Route::post('/admin/donations/{id}/toggle', [JumatBerkahController::class, 'toggleDonation'])->name('jumat-berkah.donations.toggle');
    Route::delete('/admin/donations/{id}', [JumatBerkahController::class, 'destroyDonation'])->name('jumat-berkah.donations.destroy');

    // === ROUTE ADMIN: INFAQ ===
    Route::get('/admin/infaq', [InfaqController::class, 'index'])->name('infaq.index');
    Route::post('/admin/infaq', [InfaqController::class, 'store'])->name('infaq.store');
    Route::delete('/admin/infaq/{id}', [InfaqController::class, 'destroy'])->name('infaq.destroy');
    Route::get('/admin/infaq/export/{format?}', [InfaqController::class, 'export'])->name('infaq.export');

    // === ROUTE ADMIN: SDM MASJID (MASTER DATA IMAM) ===
    Route::get('/admin/data-imam', [ImamController::class, 'index'])->name('admin.data-imam.index');
    Route::post('/admin/data-imam', [ImamController::class, 'store'])->name('admin.data-imam.store');
    Route::patch('/admin/data-imam/{id}', [ImamController::class, 'update'])->name('admin.data-imam.update'); 
    Route::delete('/admin/data-imam/{id}', [ImamController::class, 'destroy'])->name('admin.data-imam.destroy');

    // === ROUTE ADMIN: KEGIATAN RAMADHAN (OPERASIONAL) ===
    Route::prefix('admin/ramadhan')->group(function () {
        
        // 1. Data Imam Tarawih (Menu Pertama di Sidebar Ramadhan)
        // Gunakan PATCH karena kita hanya mengupdate kolom petugas di baris yang sudah ada
        Route::get('/data-imam', [ImamTarawihController::class, 'index'])->name('admin.ramadhan.imam.index');
        Route::patch('/data-imam/{id}', [ImamTarawihController::class, 'update'])->name('admin.ramadhan.imam.update');
        Route::post('/generate', [ImamTarawihController::class, 'generate'])->name('admin.ramadhan.generate');

        // Slot untuk menu lainnya nanti:
        // Route::get('/jadwal-tarawih', [JadwalTarawihController::class, 'index'])->name('admin.ramadhan.jadwal.index');
        // Route::get('/jadwal-pentakjil', [PentakjilController::class, 'index'])->name('admin.ramadhan.pentakjil.index');
        // Route::get('/jadwal-jaburan', [JaburanController::class, 'index'])->name('admin.ramadhan.jaburan.index');
    });

}); 

require __DIR__.'/auth.php';
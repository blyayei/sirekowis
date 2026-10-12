<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminWisataController;
use App\Http\Controllers\Admin\AdminKategoriController;
use App\Http\Controllers\Admin\AdminFasilitasController;
use App\Http\Controllers\Admin\AdminLaporanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - SIREKOWIS v2.0
|--------------------------------------------------------------------------
*/

// Portal Publik
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/katalog', [PublicController::class, 'katalog'])->name('katalog');
Route::get('/wisata/{slug}', [PublicController::class, 'detail'])->name('wisata.detail');
Route::get('/simulasi-cbf', [PublicController::class, 'simulasiCbf'])->name('simulasi.cbf');

// Panel Admin (Breeze Auth)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // CRUD Wisata
    Route::resource('wisata', AdminWisataController::class)->parameters(['wisata' => 'wisata'])->except(['show']);

    // CRUD Kategori
    Route::resource('kategori', AdminKategoriController::class)->parameters(['kategori' => 'kategori'])->except(['show']);

    // CRUD Fasilitas
    Route::resource('fasilitas', AdminFasilitasController::class)->parameters(['fasilitas' => 'fasilitas'])->except(['show']);

    // 6 Modul Laporan PDF Resmi
    Route::get('/laporan', [AdminLaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/cetak/{kode}', [AdminLaporanController::class, 'cetak'])->name('laporan.cetak');
});

// Alias dashboard untuk Breeze
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


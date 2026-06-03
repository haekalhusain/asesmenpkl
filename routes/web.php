<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PeminjamController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\UserController;

Route::get('/', fn() => redirect()->route('login'));

// ─── Auth ────────────────────────────────────────────────
Route::get('/login',   [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',  [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Admin ───────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Peminjam: view only
    Route::get('/peminjam',            [PeminjamController::class, 'index'])->name('peminjam.index');
    Route::get('/peminjam/{peminjam}', [PeminjamController::class, 'show'])->name('peminjam.show');

    // Pengguna: CRUD penuh
    Route::resource('pengguna', PenggunaController::class);

    // Barang: CRUD penuh
    Route::resource('barang', BarangController::class);

    // Peminjaman: CRUD penuh
    Route::resource('peminjaman', PeminjamanController::class);
    Route::get('/peminjaman-export/excel', [PeminjamanController::class, 'exportExcel'])->name('peminjaman.export-excel');
    Route::get('/peminjaman-export/pdf',   [PeminjamanController::class, 'exportPdf'])->name('peminjaman.export-pdf');
});

// ─── User ────────────────────────────────────────────────
Route::middleware(['auth', 'role:user'])->prefix('u')->name('user.')->group(function () {

    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

    // Barang: CRUD penuh (view terpisah di user.barang.*)
    Route::resource('barang', BarangController::class);

    // Peminjaman: CRUD penuh (hanya data milik user sendiri)
    Route::get('/peminjaman',                    [UserController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/create',             [UserController::class, 'create'])->name('peminjaman.create');
    Route::post('/peminjaman',                   [UserController::class, 'store'])->name('peminjaman.store');
    Route::get('/peminjaman/{peminjaman}',        [UserController::class, 'show'])->name('peminjaman.show');
    Route::get('/peminjaman/{peminjaman}/edit',   [UserController::class, 'edit'])->name('peminjaman.edit');
    Route::put('/peminjaman/{peminjaman}',        [UserController::class, 'update'])->name('peminjaman.update');
    Route::delete('/peminjaman/{peminjaman}',     [UserController::class, 'destroy'])->name('peminjaman.destroy');
});

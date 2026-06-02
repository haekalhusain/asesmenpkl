<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\PeralatanController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\UserController;

// ─── Auth ───────────────────────────────────────────────
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout',[AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/', fn() => redirect()->route('login'));

// ─── Admin ──────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('pengguna',   PenggunaController::class);
    Route::resource('peralatan',  PeralatanController::class);
    Route::resource('peminjaman', PeminjamanController::class);
});

// ─── User biasa ─────────────────────────────────────────
Route::middleware(['auth', 'role:user'])->prefix('u')->name('user.')->group(function () {

    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

    Route::get('/peminjaman',        [UserController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/buat',   [UserController::class, 'create'])->name('peminjaman.create');
    Route::post('/peminjaman',       [UserController::class, 'store'])->name('peminjaman.store');
    Route::get('/peminjaman/{peminjaman}', [UserController::class, 'show'])->name('peminjaman.show');
});

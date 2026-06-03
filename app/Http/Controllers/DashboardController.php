<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Barang;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index', [
            'totalPengguna'    => User::where('role', 'user')->count(),
            'totalBarang'      => Barang::count(),
            'totalPeminjaman'  => Peminjaman::count(),
            'peminjamanAktif'  => Peminjaman::where('status', 'Dipinjam')->count(),
            'peminjamanTerbaru'=> Peminjaman::with(['pengguna', 'barang'])
                                    ->latest()
                                    ->take(5)
                                    ->get(),
        ]);
    }
}

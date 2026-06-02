<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Peralatan;
use App\Models\Peminjaman;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index', [
            'totalPengguna'    => User::where('role', 'user')->count(),
            'totalPeralatan'   => Peralatan::count(),
            'totalPeminjaman'  => Peminjaman::count(),
            'peminjamanAktif'  => Peminjaman::where('status', 'dipinjam')->count(),
            'peminjamanTerbaru'=> Peminjaman::with(['pengguna', 'peralatan'])
                                    ->latest()
                                    ->take(5)
                                    ->get(),
        ]);
    }
}

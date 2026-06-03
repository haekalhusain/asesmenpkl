<?php

namespace App\Http\Controllers;

use App\Models\Peminjam;
use Illuminate\Http\Request;

class PeminjamController extends Controller
{
    // Tampilkan daftar semua peminjam (view only, tidak ada CRUD)
    public function index(Request $request)
    {
        $query = Peminjam::query();

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($q2) use ($q) {
                $q2->where('nama_peminjam', 'like', "%$q%")
                   ->orWhere('kelas', 'like', "%$q%")
                   ->orWhere('jurusan', 'like', "%$q%");
            });
        }

        $peminjams = $query->latest()->paginate(10);
        return view('peminjam.index', compact('peminjams'));
    }

    // Detail satu peminjam beserta riwayat peminjamannya
    public function show(Peminjam $peminjam)
    {
        $peminjam->load(['peminjaman.barang']);
        return view('peminjam.show', compact('peminjam'));
    }
}

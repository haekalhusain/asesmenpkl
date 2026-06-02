<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePeminjamanRequest;
use App\Models\Peminjaman;
use App\Models\Peralatan;
use App\Services\PeminjamanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct(
        private readonly PeminjamanService $peminjamanService
    ) {}

    public function dashboard()
    {
        $user = Auth::user();

        return view('user.dashboard', [
            'totalPeminjaman' => Peminjaman::where('pengguna_id', $user->id)->count(),
            'sedangDipinjam'  => Peminjaman::where('pengguna_id', $user->id)
                                           ->where('status', 'dipinjam')->count(),
            'sudahKembali'    => Peminjaman::where('pengguna_id', $user->id)
                                           ->where('status', 'dikembalikan')->count(),
            'riwayatTerbaru'  => Peminjaman::with('peralatan')
                                           ->where('pengguna_id', $user->id)
                                           ->latest()->take(5)->get(),
        ]);
    }

    public function index(Request $request)
    {
        $query = Peminjaman::with('peralatan')
                           ->where('pengguna_id', Auth::id());

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $q = $request->search;
            $query->whereHas('peralatan', fn($q2) =>
                $q2->where('nama_peralatan', 'like', "%$q%")
            );
        }

        $peminjamans = $query->latest()->paginate(10);
        return view('user.peminjaman.index', compact('peminjamans'));
    }

    public function create()
    {
        $peralatans = Peralatan::where('stok', '>', 0)
                               ->where('kondisi', '!=', 'rusak_berat')
                               ->orderBy('nama_peralatan')
                               ->get();
        return view('user.peminjaman.create', compact('peralatans'));
    }

    public function store(StorePeminjamanRequest $request)
    {
        // StorePeminjamanRequest sudah validasi stok,
        // Service menangani transaction + lockForUpdate.
        // Kita hanya perlu tambahkan pengguna_id dari session.
        $data = array_merge($request->validated(), [
            'pengguna_id'    => Auth::id(),
            'kode_peminjaman' => 'PJM-' . date('Ymd') . '-' . str_pad(
                Peminjaman::whereDate('created_at', today())->count() + 1,
                3, '0', STR_PAD_LEFT
            ),
        ]);

        try {
            $this->peminjamanService->store($data);
            return redirect()->route('user.peminjaman.index')
                             ->with('success', 'Peminjaman berhasil dicatat.');
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(Peminjaman $peminjaman)
    {
        // Pastikan user hanya bisa lihat miliknya sendiri
        if ($peminjaman->pengguna_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak mengakses data ini.');
        }

        $peminjaman->load('peralatan');
        return view('user.peminjaman.show', compact('peminjaman'));
    }
}

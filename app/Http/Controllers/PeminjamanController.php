<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePeminjamanRequest;
use App\Http\Requests\UpdatePeminjamanRequest;
use App\Models\Peminjaman;
use App\Models\Peralatan;
use App\Models\User;
use App\Services\PeminjamanService;
use Illuminate\Http\Request;

/**
 * Controller ini sengaja dibuat slim.
 * Semua business logic (stok, transaction) ada di PeminjamanService.
 * Controller hanya bertugas: terima request → panggil service → return response.
 */
class PeminjamanController extends Controller
{
    public function __construct(
        private readonly PeminjamanService $peminjamanService
    ) {}

    public function index(Request $request)
    {
        $query = Peminjaman::with(['pengguna', 'peralatan']);

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($q2) use ($q) {
                $q2->whereHas('pengguna', fn($q3) => $q3->where('name', 'like', "%$q%"))
                   ->orWhereHas('peralatan', fn($q3) => $q3->where('nama_peralatan', 'like', "%$q%"))
                   ->orWhere('kode_peminjaman', 'like', "%$q%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $peminjamans = $query->latest()->paginate(10);
        return view('peminjaman.index', compact('peminjamans'));
    }

    public function create()
    {
        $penggunas  = User::where('role', 'user')->orderBy('name')->get();
        $peralatans = Peralatan::where('stok', '>', 0)->orderBy('nama_peralatan')->get();
        return view('peminjaman.create', compact('penggunas', 'peralatans'));
    }

    public function store(StorePeminjamanRequest $request)
    {
        try {
            $this->peminjamanService->store($request->validated());
            return redirect()->route('peminjaman.index')
                             ->with('success', 'Peminjaman berhasil dicatat.');
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load(['pengguna', 'peralatan']);
        return view('peminjaman.show', compact('peminjaman'));
    }

    public function edit(Peminjaman $peminjaman)
    {
        $penggunas  = User::where('role', 'user')->orderBy('name')->get();
        $peralatans = Peralatan::orderBy('nama_peralatan')->get();
        return view('peminjaman.edit', compact('peminjaman', 'penggunas', 'peralatans'));
    }

    public function update(UpdatePeminjamanRequest $request, Peminjaman $peminjaman)
    {
        try {
            $this->peminjamanService->update($peminjaman, $request->validated());
            return redirect()->route('peminjaman.index')
                             ->with('success', 'Data peminjaman berhasil diperbarui.');
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy(Peminjaman $peminjaman)
    {
        $this->peminjamanService->destroy($peminjaman);
        return redirect()->route('peminjaman.index')
                         ->with('success', 'Data peminjaman berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Enums\LoanStatus;
use App\Models\Peminjaman;
use App\Models\Peralatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
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

    public function store(Request $request)
    {
        // ── Validasi (dipindah dari StorePeminjamanRequest — konteks user) ─
        $data = $request->validate([
            'peralatan_id'            => 'required|exists:peralatans,id',
            'jumlah'                  => 'required|integer|min:1',
            'tanggal_pinjam'          => 'required|date',
            'tanggal_rencana_kembali' => 'nullable|date|after_or_equal:tanggal_pinjam',
            'keterangan'              => 'nullable|string|max:500',
        ], [
            'peralatan_id.required'                  => 'Peralatan wajib dipilih.',
            'jumlah.required'                        => 'Jumlah wajib diisi.',
            'jumlah.min'                             => 'Jumlah minimal 1.',
            'tanggal_pinjam.required'                => 'Tanggal pinjam wajib diisi.',
            'tanggal_rencana_kembali.after_or_equal' => 'Rencana kembali tidak boleh sebelum tanggal pinjam.',
        ]);

        // ── Validasi stok (dipindah dari withValidator di StorePeminjamanRequest) ──
        $peralatan = Peralatan::find($data['peralatan_id']);
        if ($peralatan) {
            if ($peralatan->stok <= 0) {
                return back()->withInput()->withErrors([
                    'peralatan_id' => "Peralatan \"{$peralatan->nama_peralatan}\" stoknya sudah habis.",
                ]);
            }
            if (!$peralatan->hasStock((int) $data['jumlah'])) {
                return back()->withInput()->withErrors([
                    'jumlah' => "Stok tidak mencukupi. Stok tersedia: {$peralatan->stok} unit.",
                ]);
            }
        }

        // ── Business logic (dipindah dari PeminjamanService::store) ───────
        $data['pengguna_id']      = Auth::id();
        $data['kode_peminjaman']  = 'PJM-' . date('Ymd') . '-' . str_pad(
            Peminjaman::whereDate('created_at', today())->count() + 1,
            3, '0', STR_PAD_LEFT
        );

        try {
            DB::transaction(function () use ($data) {
                $peralatan = Peralatan::lockForUpdate()->findOrFail($data['peralatan_id']);

                if (!$peralatan->hasStock($data['jumlah'])) {
                    throw new \RuntimeException(
                        "Stok tidak mencukupi. Stok tersedia: {$peralatan->stok} unit."
                    );
                }

                Peminjaman::create([
                    'kode_peminjaman'         => $data['kode_peminjaman'],
                    'pengguna_id'             => $data['pengguna_id'],
                    'peralatan_id'            => $data['peralatan_id'],
                    'jumlah'                  => $data['jumlah'],
                    'tanggal_pinjam'          => $data['tanggal_pinjam'],
                    'tanggal_rencana_kembali' => $data['tanggal_rencana_kembali'] ?? null,
                    'status'                  => LoanStatus::Dipinjam,
                    'keterangan'              => $data['keterangan'] ?? null,
                ]);

                $peralatan->decrement('stok', $data['jumlah']);
            });

            return redirect()->route('user.peminjaman.index')
                             ->with('success', 'Peminjaman berhasil dicatat.');
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(Peminjaman $peminjaman)
    {
        if ($peminjaman->pengguna_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak mengakses data ini.');
        }

        $peminjaman->load('peralatan');
        return view('user.peminjaman.show', compact('peminjaman'));
    }
}

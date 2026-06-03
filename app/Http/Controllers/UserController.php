<?php

namespace App\Http\Controllers;

use App\Enums\LoanStatus;
use App\Models\Barang;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    // ── Dashboard ─────────────────────────────────────────────────────────

    public function dashboard()
    {
        $user = Auth::user();

        return view('user.dashboard', [
            'totalPeminjaman' => Peminjaman::where('pengguna_id', $user->id)->count(),
            'sedangDipinjam'  => Peminjaman::where('pengguna_id', $user->id)
                                           ->where('status', 'Dipinjam')->count(),
            'sudahKembali'    => Peminjaman::where('pengguna_id', $user->id)
                                           ->where('status', 'Dikembalikan')->count(),
            'totalBarang'     => Barang::count(),
            'riwayatTerbaru'  => Peminjaman::with('barang')
                                           ->where('pengguna_id', $user->id)
                                           ->latest()->take(5)->get(),
        ]);
    }

    // ── Peminjaman: Index ─────────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = Peminjaman::with('barang')
                           ->where('pengguna_id', Auth::id());

        if ($request->filled('status')) {
            $statusValue = ucfirst(strtolower($request->status));
            $query->where('status', $statusValue);
        }

        if ($request->filled('search')) {
            $q = $request->search;
            $query->whereHas('barang', fn($q2) =>
                $q2->where('nama_barang', 'like', "%$q%")
            );
        }

        $peminjamans = $query->latest()->paginate(10);
        return view('user.peminjaman.index', compact('peminjamans'));
    }

    // ── Peminjaman: Create ────────────────────────────────────────────────

    public function create()
    {
        $barangs = Barang::where('stok', '>', 0)
                         ->where('kondisi_barang', '!=', 'Rusak Berat')
                         ->orderBy('nama_barang')
                         ->get();
        return view('user.peminjaman.create', compact('barangs'));
    }

    // ── Peminjaman: Store ─────────────────────────────────────────────────

    public function store(Request $request)
    {
        $data = $request->validate([
            'barang_id'               => 'required|exists:barang,id',
            'jumlah'                  => 'required|integer|min:1',
            'tanggal_pinjam'          => 'required|date',
            'tanggal_rencana_kembali' => 'nullable|date|after_or_equal:tanggal_pinjam',
            'keterangan'              => 'nullable|string|max:500',
        ], [
            'barang_id.required'                     => 'Barang wajib dipilih.',
            'jumlah.required'                        => 'Jumlah wajib diisi.',
            'jumlah.min'                             => 'Jumlah minimal 1.',
            'tanggal_pinjam.required'                => 'Tanggal pinjam wajib diisi.',
            'tanggal_rencana_kembali.after_or_equal' => 'Rencana kembali tidak boleh sebelum tanggal pinjam.',
        ]);

        $barang = Barang::find($data['barang_id']);
        if (!$barang->hasStock((int) $data['jumlah'])) {
            return back()->withInput()->withErrors([
                'jumlah' => "Stok tidak mencukupi. Stok tersedia: {$barang->stok} unit.",
            ]);
        }

        try {
            DB::transaction(function () use ($data) {
                $barang = Barang::lockForUpdate()->findOrFail($data['barang_id']);

                if (!$barang->hasStock($data['jumlah'])) {
                    throw new \RuntimeException("Stok tidak mencukupi. Stok tersedia: {$barang->stok} unit.");
                }

                Peminjaman::create([
                    'kode_peminjaman'         => 'PJM-' . date('Ymd') . '-' . str_pad(
                        Peminjaman::whereDate('created_at', today())->count() + 1,
                        3, '0', STR_PAD_LEFT
                    ),
                    'pengguna_id'             => Auth::id(),
                    'barang_id'               => $data['barang_id'],
                    'jumlah'                  => $data['jumlah'],
                    'tanggal_pinjam'          => $data['tanggal_pinjam'],
                    'tanggal_rencana_kembali' => $data['tanggal_rencana_kembali'] ?? null,
                    'status'                  => LoanStatus::Dipinjam,
                    'keterangan'              => $data['keterangan'] ?? null,
                ]);

                $barang->decrement('stok', $data['jumlah']);
            });

            return redirect()->route('user.peminjaman.index')
                             ->with('success', 'Peminjaman berhasil dicatat.');
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    // ── Peminjaman: Show ──────────────────────────────────────────────────

    public function show(Peminjaman $peminjaman)
    {
        $this->authorizeOwner($peminjaman);
        $peminjaman->load('barang');
        return view('user.peminjaman.show', compact('peminjaman'));
    }

    // ── Peminjaman: Edit ──────────────────────────────────────────────────

    public function edit(Peminjaman $peminjaman)
    {
        $this->authorizeOwner($peminjaman);

        // Hanya bisa edit jika status masih Dipinjam
        if ($peminjaman->status !== LoanStatus::Dipinjam) {
            return redirect()->route('user.peminjaman.index')
                             ->with('error', 'Peminjaman yang sudah dikembalikan tidak dapat diedit.');
        }

        $barangs = Barang::orderBy('nama_barang')->get();
        return view('user.peminjaman.edit', compact('peminjaman', 'barangs'));
    }

    // ── Peminjaman: Update ────────────────────────────────────────────────

    public function update(Request $request, Peminjaman $peminjaman)
    {
        $this->authorizeOwner($peminjaman);

        if ($peminjaman->status !== LoanStatus::Dipinjam) {
            return redirect()->route('user.peminjaman.index')
                             ->with('error', 'Peminjaman yang sudah dikembalikan tidak dapat diedit.');
        }

        $data = $request->validate([
            'tanggal_pinjam'          => 'required|date',
            'tanggal_rencana_kembali' => 'nullable|date|after_or_equal:tanggal_pinjam',
            'tanggal_kembali'         => 'nullable|date|after_or_equal:tanggal_pinjam',
            'keterangan'              => 'nullable|string|max:500',
            'status'                  => 'required|in:Dipinjam,Dikembalikan',
        ], [
            'tanggal_pinjam.required'                => 'Tanggal pinjam wajib diisi.',
            'tanggal_rencana_kembali.after_or_equal' => 'Rencana kembali tidak boleh sebelum tanggal pinjam.',
            'tanggal_kembali.after_or_equal'         => 'Tanggal kembali tidak boleh sebelum tanggal pinjam.',
            'status.required'                        => 'Status wajib dipilih.',
            'status.in'                              => 'Status tidak valid.',
        ]);

        try {
            DB::transaction(function () use ($data, $peminjaman) {
                $statusBaru = LoanStatus::from($data['status']);

                // Jika status berubah jadi Dikembalikan → kembalikan stok
                if ($statusBaru === LoanStatus::Dikembalikan &&
                    $peminjaman->status === LoanStatus::Dipinjam) {
                    $barang = Barang::lockForUpdate()->findOrFail($peminjaman->barang_id);
                    $barang->increment('stok', $peminjaman->jumlah);

                    // Isi tanggal kembali otomatis jika belum diisi
                    if (empty($data['tanggal_kembali'])) {
                        $data['tanggal_kembali'] = today()->toDateString();
                    }
                }

                $peminjaman->update([
                    'tanggal_pinjam'          => $data['tanggal_pinjam'],
                    'tanggal_rencana_kembali' => $data['tanggal_rencana_kembali'] ?? null,
                    'tanggal_kembali'         => $data['tanggal_kembali'] ?? null,
                    'status'                  => $statusBaru,
                    'keterangan'              => $data['keterangan'] ?? null,
                ]);
            });

            return redirect()->route('user.peminjaman.index')
                             ->with('success', 'Peminjaman berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui: ' . $e->getMessage());
        }
    }

    // ── Peminjaman: Destroy ───────────────────────────────────────────────

    public function destroy(Peminjaman $peminjaman)
    {
        $this->authorizeOwner($peminjaman);

        // Hanya bisa hapus jika masih berstatus Dipinjam (stok dikembalikan)
        try {
            DB::transaction(function () use ($peminjaman) {
                if ($peminjaman->status === LoanStatus::Dipinjam) {
                    $barang = Barang::lockForUpdate()->findOrFail($peminjaman->barang_id);
                    $barang->increment('stok', $peminjaman->jumlah);
                }
                $peminjaman->delete();
            });

            return redirect()->route('user.peminjaman.index')
                             ->with('success', 'Peminjaman berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    // ── Helper ────────────────────────────────────────────────────────────

    /**
     * Pastikan peminjaman milik user yang sedang login.
     */
    private function authorizeOwner(Peminjaman $peminjaman): void
    {
        if ($peminjaman->pengguna_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak mengakses data ini.');
        }
    }
}

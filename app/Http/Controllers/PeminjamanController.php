<?php

namespace App\Http\Controllers;

use App\Enums\LoanStatus;
use App\Models\Peminjaman;
use App\Models\Peralatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PeminjamanController extends Controller
{
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

    public function store(Request $request)
    {
        $isAdmin = auth()->user()->role === 'admin';

        // ── Validasi (dipindah dari StorePeminjamanRequest) ────────────────
        $data = $request->validate([
            'kode_peminjaman'         => $isAdmin
                                            ? 'required|string|max:50|unique:peminjamans,kode_peminjaman'
                                            : 'nullable|string|max:50',
            'pengguna_id'             => $isAdmin ? 'required|exists:users,id' : 'nullable',
            'peralatan_id'            => 'required|exists:peralatans,id',
            'jumlah'                  => 'required|integer|min:1',
            'tanggal_pinjam'          => 'required|date',
            'tanggal_rencana_kembali' => 'nullable|date|after_or_equal:tanggal_pinjam',
            'keterangan'              => 'nullable|string|max:500',
        ], [
            'kode_peminjaman.required'              => 'Kode peminjaman wajib diisi.',
            'kode_peminjaman.unique'                => 'Kode peminjaman sudah digunakan.',
            'pengguna_id.required'                  => 'Peminjam wajib dipilih.',
            'peralatan_id.required'                 => 'Peralatan wajib dipilih.',
            'jumlah.required'                       => 'Jumlah wajib diisi.',
            'jumlah.min'                            => 'Jumlah minimal 1.',
            'tanggal_pinjam.required'               => 'Tanggal pinjam wajib diisi.',
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
        try {
            DB::transaction(function () use ($data) {
                // Lock row agar tidak ada race condition saat dua request masuk bersamaan
                $peralatan = Peralatan::lockForUpdate()->findOrFail($data['peralatan_id']);

                // Double-check stok di dalam transaction
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

    public function update(Request $request, Peminjaman $peminjaman)
    {
        // ── Validasi (dipindah dari UpdatePeminjamanRequest) ───────────────
        $data = $request->validate([
            'pengguna_id'             => 'required|exists:users,id',
            'peralatan_id'            => 'required|exists:peralatans,id',
            'jumlah'                  => 'required|integer|min:1',
            'tanggal_pinjam'          => 'required|date',
            'tanggal_rencana_kembali' => 'nullable|date|after_or_equal:tanggal_pinjam',
            'tanggal_kembali'         => 'nullable|date|after_or_equal:tanggal_pinjam',
            'status'                  => 'required|in:dipinjam,dikembalikan,terlambat',
            'keterangan'              => 'nullable|string|max:500',
        ], [
            'pengguna_id.required'           => 'Peminjam wajib dipilih.',
            'peralatan_id.required'          => 'Peralatan wajib dipilih.',
            'jumlah.min'                     => 'Jumlah minimal 1.',
            'tanggal_pinjam.required'        => 'Tanggal pinjam wajib diisi.',
            'status.in'                      => 'Status tidak valid.',
            'tanggal_kembali.after_or_equal' => 'Tanggal kembali tidak boleh sebelum tanggal pinjam.',
        ]);

        // ── Business logic (dipindah dari PeminjamanService::update) ──────
        try {
            DB::transaction(function () use ($peminjaman, $data) {
                $statusLama = $peminjaman->status;
                $statusBaru = LoanStatus::from($data['status']);

                if ($statusLama !== $statusBaru) {
                    $peralatan = Peralatan::lockForUpdate()->findOrFail($peminjaman->peralatan_id);

                    if ($statusBaru === LoanStatus::Dikembalikan
                        && $statusLama === LoanStatus::Dipinjam) {
                        // Barang dikembalikan → stok naik
                        $peralatan->increment('stok', $peminjaman->jumlah);

                    } elseif ($statusBaru === LoanStatus::Dipinjam
                        && $statusLama === LoanStatus::Dikembalikan) {
                        // Koreksi: dikembalikan → dipinjam lagi → stok turun
                        if (!$peralatan->hasStock($data['jumlah'])) {
                            throw new \RuntimeException(
                                "Stok tidak mencukupi untuk koreksi ini. Stok tersedia: {$peralatan->stok} unit."
                            );
                        }
                        $peralatan->decrement('stok', $data['jumlah']);
                    }
                }

                $peminjaman->update([
                    'pengguna_id'             => $data['pengguna_id'],
                    'peralatan_id'            => $data['peralatan_id'],
                    'jumlah'                  => $data['jumlah'],
                    'tanggal_pinjam'          => $data['tanggal_pinjam'],
                    'tanggal_rencana_kembali' => $data['tanggal_rencana_kembali'] ?? null,
                    'tanggal_kembali'         => $data['tanggal_kembali'] ?? null,
                    'status'                  => $statusBaru,
                    'keterangan'              => $data['keterangan'] ?? null,
                ]);
            });

            return redirect()->route('peminjaman.index')
                             ->with('success', 'Data peminjaman berhasil diperbarui.');
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy(Peminjaman $peminjaman)
    {
        // ── Business logic (dipindah dari PeminjamanService::destroy) ─────
        DB::transaction(function () use ($peminjaman) {
            if ($peminjaman->status === LoanStatus::Dipinjam) {
                $peminjaman->peralatan->increment('stok', $peminjaman->jumlah);
            }
            $peminjaman->delete();
        });

        return redirect()->route('peminjaman.index')
                         ->with('success', 'Data peminjaman berhasil dihapus.');
    }
}

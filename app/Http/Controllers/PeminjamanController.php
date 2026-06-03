<?php

namespace App\Http\Controllers;

use App\Enums\LoanStatus;
use App\Exports\PeminjamanExport;
use App\Models\Barang;
use App\Models\Peminjam;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['pengguna', 'barang']);

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($q2) use ($q) {
                $q2->whereHas('pengguna', fn($q3) => $q3->where('nama_peminjam', 'like', "%$q%"))
                   ->orWhereHas('barang', fn($q3) =>
                       $q3->where('nama_barang', 'like', "%$q%")
                          ->orWhere('kategori_barang', 'like', "%$q%")
                   )
                   ->orWhere('kode_peminjaman', 'like', "%$q%");
            });
        }

        if ($request->filled('status')) {
            $statusValue = ucfirst(strtolower($request->status));
            $query->where('status', $statusValue);
        }

        $peminjamans = $query->latest()->paginate(10);
        return view('peminjaman.index', compact('peminjamans'));
    }

    public function create()
    {
        $penggunas = Peminjam::orderBy('nama_peminjam')->get();
        $barangs   = Barang::where('stok', '>', 0)->orderBy('nama_barang')->get();
        return view('peminjaman.create', compact('penggunas', 'barangs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pengguna_id'             => 'required|exists:peminjam,id',
            'barang_id'               => 'required|exists:barang,id',
            'jumlah'                  => 'required|integer|min:1',
            'tanggal_pinjam'          => 'required|date',
            'tanggal_rencana_kembali' => 'nullable|date|after_or_equal:tanggal_pinjam',
            'keterangan'              => 'nullable|string|max:500',
        ], [
            'pengguna_id.required'                   => 'Peminjam wajib dipilih.',
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
                    'pengguna_id'             => $data['pengguna_id'],
                    'barang_id'               => $data['barang_id'],
                    'jumlah'                  => $data['jumlah'],
                    'tanggal_pinjam'          => $data['tanggal_pinjam'],
                    'tanggal_rencana_kembali' => $data['tanggal_rencana_kembali'] ?? null,
                    'status'                  => LoanStatus::Dipinjam,
                    'keterangan'              => $data['keterangan'] ?? null,
                ]);

                $barang->decrement('stok', $data['jumlah']);
            });

            return redirect()->route('peminjaman.index')
                             ->with('success', 'Peminjaman berhasil dicatat.');
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load(['pengguna', 'barang']);
        return view('peminjaman.show', compact('peminjaman'));
    }

    public function edit(Peminjaman $peminjaman)
    {
        $penggunas = Peminjam::orderBy('nama_peminjam')->get();
        $barangs   = Barang::orderBy('nama_barang')->get();
        return view('peminjaman.edit', compact('peminjaman', 'penggunas', 'barangs'));
    }

    public function update(Request $request, Peminjaman $peminjaman)
    {
        $data = $request->validate([
            'pengguna_id'             => 'required|exists:peminjam,id',
            'barang_id'               => 'required|exists:barang,id',
            'jumlah'                  => 'required|integer|min:1',
            'tanggal_pinjam'          => 'required|date',
            'tanggal_rencana_kembali' => 'nullable|date|after_or_equal:tanggal_pinjam',
            'tanggal_kembali'         => 'nullable|date',
            'status'                  => 'required|in:Dipinjam,Dikembalikan,Terlambat',
            'keterangan'              => 'nullable|string|max:500',
        ], [
            'pengguna_id.required'    => 'Peminjam wajib dipilih.',
            'barang_id.required'      => 'Barang wajib dipilih.',
            'jumlah.min'              => 'Jumlah minimal 1.',
            'tanggal_pinjam.required' => 'Tanggal pinjam wajib diisi.',
            'status.in'               => 'Status tidak valid.',
        ]);

        try {
            DB::transaction(function () use ($peminjaman, $data) {
                $statusLama = $peminjaman->status;
                $statusBaru = LoanStatus::from($data['status']);

                if ($statusLama !== $statusBaru) {
                    $barang = Barang::lockForUpdate()->findOrFail($peminjaman->barang_id);

                    if ($statusBaru === LoanStatus::Dikembalikan && $statusLama === LoanStatus::Dipinjam) {
                        $barang->increment('stok', $peminjaman->jumlah);

                    } elseif ($statusBaru === LoanStatus::Dipinjam && $statusLama !== LoanStatus::Dipinjam) {
                        if (!$barang->hasStock($data['jumlah'])) {
                            throw new \RuntimeException("Stok tidak mencukupi. Stok tersedia: {$barang->stok} unit.");
                        }
                        $barang->decrement('stok', $data['jumlah']);
                    }
                }

                $peminjaman->update([
                    'pengguna_id'             => $data['pengguna_id'],
                    'barang_id'               => $data['barang_id'],
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
        DB::transaction(function () use ($peminjaman) {
            if ($peminjaman->status === LoanStatus::Dipinjam) {
                $peminjaman->barang->increment('stok', $peminjaman->jumlah);
            }
            $peminjaman->delete();
        });

        return redirect()->route('peminjaman.index')
                         ->with('success', 'Data peminjaman berhasil dihapus.');
    }

    public function exportExcel(Request $request)
    {
        $filename = 'Peminjaman_' . date('d-m-Y_H-i-s') . '.xlsx';
        return Excel::download(
            new PeminjamanExport($request->search, $request->status),
            $filename
        );
    }

    public function exportPdf(Request $request)
    {
        $query = Peminjaman::with(['pengguna', 'barang']);

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($q2) use ($q) {
                $q2->whereHas('pengguna', fn($q3) => $q3->where('nama_peminjam', 'like', "%$q%"))
                   ->orWhereHas('barang', fn($q3) =>
                       $q3->where('nama_barang', 'like', "%$q%")
                          ->orWhere('kategori_barang', 'like', "%$q%")
                   )
                   ->orWhere('kode_peminjaman', 'like', "%$q%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $peminjamans = $query->latest()->get();

        $pdf = Pdf::loadView('peminjaman.export_pdf', compact('peminjamans'));
        return $pdf->download('Peminjaman_' . date('d-m-Y_H-i-s') . '.pdf');
    }
}

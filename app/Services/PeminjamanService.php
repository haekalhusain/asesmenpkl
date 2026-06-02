<?php

namespace App\Services;

use App\Enums\LoanStatus;
use App\Models\Peminjaman;
use App\Models\Peralatan;
use Illuminate\Support\Facades\DB;

/**
 * PeminjamanService
 *
 * Semua business logic terkait peminjaman dikumpulkan di sini
 * agar Controller tetap slim dan logic mudah di-test secara terpisah.
 */
class PeminjamanService
{
    /**
     * Buat peminjaman baru + kurangi stok secara atomic.
     *
     * DB::transaction memastikan: jika pengurangan stok gagal,
     * record peminjaman juga ikut di-rollback (tidak ada data orphan).
     * Ini juga mencegah race condition saat dua user meminjam bersamaan.
     */
    public function store(array $data): Peminjaman
    {
        return DB::transaction(function () use ($data) {
            // 1. Ambil peralatan dengan lock FOR UPDATE
            //    Lock ini mencegah row yang sama dibaca proses lain
            //    sampai transaction ini selesai (mencegah race condition).
            $peralatan = Peralatan::lockForUpdate()->findOrFail($data['peralatan_id']);

            // 2. Double-check stok di dalam transaction
            //    (validasi di FormRequest bisa saja sudah lewat
            //    ketika request lain masuk bersamaan)
            if (!$peralatan->hasStock($data['jumlah'])) {
                throw new \RuntimeException(
                    "Stok tidak mencukupi. Stok tersedia: {$peralatan->stok} unit."
                );
            }

            // 3. Buat record peminjaman
            $peminjaman = Peminjaman::create([
                'kode_peminjaman'         => $data['kode_peminjaman'],
                'pengguna_id'             => $data['pengguna_id'],
                'peralatan_id'            => $data['peralatan_id'],
                'jumlah'                  => $data['jumlah'],
                'tanggal_pinjam'          => $data['tanggal_pinjam'],
                'tanggal_rencana_kembali' => $data['tanggal_rencana_kembali'] ?? null,
                'status'                  => LoanStatus::Dipinjam,
                'keterangan'              => $data['keterangan'] ?? null,
            ]);

            // 4. Kurangi stok
            $peralatan->decrement('stok', $data['jumlah']);

            return $peminjaman;
        });
    }

    /**
     * Update peminjaman + kelola stok berdasarkan perubahan status.
     *
     * Skenario yang ditangani:
     * - dipinjam → dikembalikan : stok bertambah
     * - dikembalikan → dipinjam : stok berkurang (misal koreksi data)
     * - status sama             : tidak ada perubahan stok
     */
    public function update(Peminjaman $peminjaman, array $data): Peminjaman
    {
        return DB::transaction(function () use ($peminjaman, $data) {
            $statusLama = $peminjaman->status;
            $statusBaru = LoanStatus::from($data['status']);

            // Kelola stok jika status berubah
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

            return $peminjaman->fresh();
        });
    }

    /**
     * Hapus peminjaman + kembalikan stok jika masih berstatus dipinjam.
     */
    public function destroy(Peminjaman $peminjaman): void
    {
        DB::transaction(function () use ($peminjaman) {
            if ($peminjaman->status === LoanStatus::Dipinjam) {
                $peminjaman->peralatan->increment('stok', $peminjaman->jumlah);
            }

            $peminjaman->delete();
        });
    }
}

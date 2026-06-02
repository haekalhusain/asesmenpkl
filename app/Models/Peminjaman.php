<?php

namespace App\Models;

use App\Enums\LoanStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Peminjaman extends Model
{
    protected $table = 'peminjamans';

    protected $fillable = [
        'kode_peminjaman',
        'pengguna_id',
        'peralatan_id',
        'jumlah',
        'tanggal_pinjam',
        'tanggal_rencana_kembali',
        'tanggal_kembali',
        'status',
        'keterangan',
    ];

    /**
     * Cast kolom status ke Native Enum LoanStatus.
     * Laravel otomatis konversi string ↔ Enum saat read/write.
     */
    protected $casts = [
        'status'                  => LoanStatus::class,
        'tanggal_pinjam'          => 'date',
        'tanggal_rencana_kembali' => 'date',
        'tanggal_kembali'         => 'date',
    ];

    // ── Relasi ──────────────────────────────────────────────

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pengguna_id');
    }

    public function peralatan(): BelongsTo
    {
        return $this->belongsTo(Peralatan::class, 'peralatan_id');
    }

    // ── Helper ──────────────────────────────────────────────

    /**
     * Apakah peminjaman ini sedang aktif (belum dikembalikan)?
     */
    public function isActive(): bool
    {
        return $this->status === LoanStatus::Dipinjam;
    }
}

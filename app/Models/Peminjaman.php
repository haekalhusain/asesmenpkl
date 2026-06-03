<?php

namespace App\Models;

use App\Enums\LoanStatus;
use App\Models\Peminjam;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $fillable = [
        'kode_peminjaman',
        'pengguna_id',
        'barang_id',
        'jumlah',
        'tanggal_pinjam',
        'tanggal_rencana_kembali',
        'tanggal_kembali',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'status'                  => LoanStatus::class,
        'tanggal_pinjam'          => 'date',
        'tanggal_rencana_kembali' => 'date',
        'tanggal_kembali'         => 'date',
    ];

    // ── Relasi ──────────────────────────────────────────────────────────────

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Peminjam::class, 'pengguna_id');
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    // ── Helper ──────────────────────────────────────────────────────────────

    public function isActive(): bool
    {
        return $this->status === LoanStatus::Dipinjam;
    }
}

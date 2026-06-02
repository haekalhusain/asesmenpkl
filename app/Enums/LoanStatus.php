<?php

namespace App\Enums;

/**
 * Native Enum untuk status peminjaman.
 * Dengan enum, status tidak bisa diisi sembarangan string —
 * harus salah satu dari tiga nilai ini.
 */
enum LoanStatus: string
{
    case Dipinjam      = 'dipinjam';
    case Dikembalikan  = 'dikembalikan';
    case Terlambat     = 'terlambat';

    /**
     * Label untuk ditampilkan di UI.
     */
    public function label(): string
    {
        return match($this) {
            self::Dipinjam     => 'Dipinjam',
            self::Dikembalikan => 'Dikembalikan',
            self::Terlambat    => 'Terlambat',
        };
    }

    /**
     * CSS class badge untuk tampilan.
     */
    public function badgeClass(): string
    {
        return match($this) {
            self::Dipinjam     => 'badge-warning',
            self::Dikembalikan => 'badge-success',
            self::Terlambat    => 'badge-danger',
        };
    }
}

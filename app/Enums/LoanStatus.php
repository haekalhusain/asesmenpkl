<?php

namespace App\Enums;

enum LoanStatus: string
{
    case Dipinjam     = 'Dipinjam';
    case Dikembalikan = 'Dikembalikan';
    case Terlambat    = 'Terlambat';

    public function label(): string
    {
        return match($this) {
            self::Dipinjam     => 'Dipinjam',
            self::Dikembalikan => 'Dikembalikan',
            self::Terlambat    => 'Terlambat',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::Dipinjam     => 'badge-warning',
            self::Dikembalikan => 'badge-success',
            self::Terlambat    => 'badge-danger',
        };
    }
}

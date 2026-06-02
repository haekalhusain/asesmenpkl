<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Peralatan extends Model
{
    protected $table = 'peralatans';

    protected $fillable = [
        'kode_peralatan',
        'nama_peralatan',
        'kategori',
        'stok',
        'kondisi',
        'deskripsi',
        'foto',
    ];

    protected $casts = [
        'stok' => 'integer',
    ];

    public function peminjamans(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'peralatan_id');
    }

    /**
     * Apakah stok mencukupi untuk jumlah yang diminta?
     */
    public function hasStock(int $jumlah = 1): bool
    {
        return $this->stok >= $jumlah;
    }
}

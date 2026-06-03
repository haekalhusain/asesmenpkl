<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    protected $table = 'barang';

    protected $fillable = [
        'nama_barang',
        'kategori_barang',
        'stok',
        'kondisi_barang',
        'gambar',
    ];

    protected $casts = [
        'stok' => 'integer',
    ];

    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'barang_id');
    }

    public function hasStock(int $jumlah = 1): bool
    {
        return $this->stok >= $jumlah;
    }
}

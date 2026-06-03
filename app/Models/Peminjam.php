<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Peminjam extends Model
{
    protected $table = 'peminjam';

    protected $fillable = [
        'nama_peminjam',
        'kelas',
        'jurusan',
        'no_hp',
    ];

    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'pengguna_id');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Akun login (idempotent) ──────────────────────────────
        DB::table('users')->updateOrInsert(
            ['no_pengguna' => 'ADMIN001'],
            [
                'email'       => 'admin@inventaris.com',
                'name'        => 'Admin',
                'password'    => Hash::make('password'),
                'role'        => 'admin',
                'updated_at'  => now(),
                'created_at'  => DB::raw("COALESCE(created_at, '".now()."')"),
            ]
        );

        DB::table('users')->updateOrInsert(
            ['no_pengguna' => 'USER001'],
            [
                'email'       => 'user@inventaris.com',
                'name'        => 'User',
                'password'    => Hash::make('password'),
                'role'        => 'user',
                'updated_at'  => now(),
                'created_at'  => DB::raw("COALESCE(created_at, '".now()."')"),
            ]
        );

        // ── Data Peminjam ────────────────────────────────────────
        // Diisi via Seeder saja, tidak ada CRUD Peminjam
        DB::table('peminjam')->updateOrInsert(
            ['nama_peminjam' => 'Ahmad Fauzan'],
            [
                'kelas'      => 'XI',
                'jurusan'    => 'PPLG 1',
                'no_hp'      => null,
                'updated_at' => now(),
                'created_at' => DB::raw("COALESCE(created_at, '".now()."')"),
            ]
        );

        DB::table('peminjam')->updateOrInsert(
            ['nama_peminjam' => 'Rizky Pratama'],
            [
                'kelas'      => 'XI',
                'jurusan'    => 'PPLG 2',
                'no_hp'      => null,
                'updated_at' => now(),
                'created_at' => DB::raw("COALESCE(created_at, '".now()."')"),
            ]
        );

        DB::table('peminjam')->updateOrInsert(
            ['nama_peminjam' => 'Dinda Putri'],
            [
                'kelas'      => 'XI',
                'jurusan'    => 'PPLG 1',
                'no_hp'      => null,
                'updated_at' => now(),
                'created_at' => DB::raw("COALESCE(created_at, '".now()."')"),
            ]
        );

        // ── Data Barang ──────────────────────────────────────────
        DB::table('barang')->updateOrInsert(
            ['nama_barang' => 'Laptop Asus'],
            [
                'kategori_barang' => 'Laptop',
                'stok'            => 10,
                'kondisi_barang'  => 'Baik',
                'updated_at'      => now(),
                'created_at'      => DB::raw("COALESCE(created_at, '".now()."')"),
            ]
        );

        DB::table('barang')->updateOrInsert(
            ['nama_barang' => 'Mouse Logitech'],
            [
                'kategori_barang' => 'Aksesoris',
                'stok'            => 15,
                'kondisi_barang'  => 'Baik',
                'updated_at'      => now(),
                'created_at'      => DB::raw("COALESCE(created_at, '".now()."')"),
            ]
        );

        DB::table('barang')->updateOrInsert(
            ['nama_barang' => 'Keyboard Mechanical'],
            [
                'kategori_barang' => 'Aksesoris',
                'stok'            => 8,
                'kondisi_barang'  => 'Baik',
                'updated_at'      => now(),
                'created_at'      => DB::raw("COALESCE(created_at, '".now()."')"),
            ]
        );
    }
}

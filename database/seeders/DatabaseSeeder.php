<?php

namespace Database\Seeders;

use App\Enums\LoanStatus;
use App\Models\Peminjaman;
use App\Models\Peralatan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ────────────────────────────────────────────
        User::create([
            'no_pengguna' => 'ADM-001',
            'name'        => 'Admin TEFA',
            'email'       => 'admin@asesmen.test',
            'password'    => Hash::make('password'),
            'no_hp'       => '081234567890',
            'alamat'      => 'Lab TEFA PPLG',
            'role'        => 'admin',
        ]);

        // ── Pengguna biasa ───────────────────────────────────
        $users = [
            ['no_pengguna' => 'USR-001', 'name' => 'Budi Santoso',  'email' => 'budi@siswa.test'],
            ['no_pengguna' => 'USR-002', 'name' => 'Siti Aminah',   'email' => 'siti@siswa.test'],
            ['no_pengguna' => 'USR-003', 'name' => 'Rizky Pratama', 'email' => 'rizky@siswa.test'],
        ];

        foreach ($users as $u) {
            User::create([
                ...$u,
                'password' => Hash::make('password'),
                'no_hp'    => '08' . rand(100000000, 999999999),
                'alamat'   => 'Palangkaraya, Kalimantan Tengah',
                'role'     => 'user',
            ]);
        }

        // ── Peralatan ────────────────────────────────────────
        $peralatans = [
            ['kode_peralatan' => 'KMP-001', 'nama_peralatan' => 'Laptop Lenovo ThinkPad', 'kategori' => 'Komputer',  'stok' => 10, 'kondisi' => 'baik'],
            ['kode_peralatan' => 'KMP-002', 'nama_peralatan' => 'Raspberry Pi 4',          'kategori' => 'Komputer',  'stok' => 5,  'kondisi' => 'baik'],
            ['kode_peralatan' => 'JRG-001', 'nama_peralatan' => 'Switch Cisco 24 Port',    'kategori' => 'Jaringan',  'stok' => 3,  'kondisi' => 'baik'],
            ['kode_peralatan' => 'JRG-002', 'nama_peralatan' => 'Kabel UTP Cat6 (meter)',  'kategori' => 'Jaringan',  'stok' => 50, 'kondisi' => 'baik'],
            ['kode_peralatan' => 'ALT-001', 'nama_peralatan' => 'Multimeter Digital',      'kategori' => 'Alat Ukur', 'stok' => 8,  'kondisi' => 'baik'],
            ['kode_peralatan' => 'ALT-002', 'nama_peralatan' => 'Solder Listrik 40W',      'kategori' => 'Alat Ukur', 'stok' => 6,  'kondisi' => 'rusak_ringan'],
        ];

        foreach ($peralatans as $p) {
            Peralatan::create([...$p, 'deskripsi' => 'Peralatan Lab TEFA PPLG']);
        }

        // ── Dummy peminjaman ─────────────────────────────────
        Peminjaman::create([
            'kode_peminjaman'         => 'PJM-20240601-001',
            'pengguna_id'             => 2,
            'peralatan_id'            => 1,
            'jumlah'                  => 1,
            'tanggal_pinjam'          => '2024-06-01',
            'tanggal_rencana_kembali' => '2024-06-07',
            'tanggal_kembali'         => '2024-06-06',
            'status'                  => LoanStatus::Dikembalikan, // pakai Enum, bukan string
        ]);

        Peminjaman::create([
            'kode_peminjaman'         => 'PJM-20240610-002',
            'pengguna_id'             => 3,
            'peralatan_id'            => 3,
            'jumlah'                  => 1,
            'tanggal_pinjam'          => '2024-06-10',
            'tanggal_rencana_kembali' => '2024-06-17',
            'status'                  => LoanStatus::Dipinjam,
        ]);

        // Kurangi stok untuk peminjaman yang masih aktif
        Peralatan::find(3)->decrement('stok', 1);
    }
}

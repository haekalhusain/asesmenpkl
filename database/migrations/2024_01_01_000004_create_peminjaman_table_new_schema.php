<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Tabel users (tambah kolom no_pengguna, no_hp, alamat) ──────────
        Schema::table('users', function (Blueprint $table) {
            // Cek sebelum tambah agar aman saat re-run
            if (!Schema::hasColumn('users', 'no_pengguna')) {
                $table->string('no_pengguna', 20)->nullable()->unique()->after('id');
            }
            if (!Schema::hasColumn('users', 'no_hp')) {
                $table->string('no_hp', 20)->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'alamat')) {
                $table->text('alamat')->nullable()->after('no_hp');
            }
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role', 20)->default('user')->after('alamat');
            }
        });

        // ── Tabel peralatans (sudah benar, pastikan ada) ───────────────────
        if (!Schema::hasTable('peralatans')) {
            Schema::create('peralatans', function (Blueprint $table) {
                $table->id();
                $table->string('kode_peralatan', 30)->unique();
                $table->string('nama_peralatan', 150);
                $table->string('kategori', 100)->nullable();
                $table->unsignedInteger('stok')->default(0);
                $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat'])->default('baik');
                $table->text('deskripsi')->nullable();
                $table->string('foto')->nullable();
                $table->timestamps();
            });
        }

        // ── Tabel peminjaman (skema baru) ──────────────────────────────────
        // Drop tabel lama jika ada (hati-hati di production, backup dulu!)
        Schema::dropIfExists('peminjaman');

        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->string('kode_peminjaman', 30)->unique();
            $table->foreignId('pengguna_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('barang_id')->constrained('barang')->cascadeOnDelete();
            $table->unsignedInteger('jumlah')->default(1);
            $table->date('tanggal_pinjam');
            $table->date('tanggal_rencana_kembali')->nullable();
            $table->date('tanggal_kembali')->nullable();
            $table->enum('status', ['Dipinjam', 'Dikembalikan', 'Terlambat'])->default('Dipinjam');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};

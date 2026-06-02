<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_peminjaman', 50)->unique();

            // Foreign key ke tabel users dengan restrict:
            // tidak bisa hapus user yang masih punya data peminjaman
            $table->foreignId('pengguna_id')
                  ->constrained('users')
                  ->onDelete('restrict');

            // Foreign key ke tabel peralatans dengan restrict:
            // tidak bisa hapus peralatan yang sedang/pernah dipinjam
            $table->foreignId('peralatan_id')
                  ->constrained('peralatans')
                  ->onDelete('restrict');

            $table->unsignedInteger('jumlah')->default(1);
            $table->date('tanggal_pinjam');
            $table->date('tanggal_rencana_kembali')->nullable();
            $table->date('tanggal_kembali')->nullable();

            // Enum sinkron dengan App\Enums\LoanStatus
            $table->enum('status', ['dipinjam', 'dikembalikan', 'terlambat'])
                  ->default('dipinjam');

            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};

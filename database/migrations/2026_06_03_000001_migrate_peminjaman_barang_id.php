<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('peminjaman')) {
            return;
        }

        if (!Schema::hasColumn('peminjaman', 'barang_id')) {
            Schema::table('peminjaman', function (Blueprint $table) {
                $table->foreignId('barang_id')->nullable()->after('pengguna_id')->constrained('barang')->nullOnDelete();
            });
        }

        if (Schema::hasColumn('peminjaman', 'peralatan_id')) {
            DB::statement(
                'UPDATE peminjaman
                 JOIN peralatans ON peminjaman.peralatan_id = peralatans.id
                 JOIN barang ON peralatans.nama_peralatan = barang.nama_barang
                 SET peminjaman.barang_id = barang.id'
            );

            Schema::table('peminjaman', function (Blueprint $table) {
                $table->dropForeign(['peralatan_id']);
                $table->dropColumn('peralatan_id');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('peminjaman')) {
            return;
        }

        if (!Schema::hasColumn('peminjaman', 'barang_id')) {
            return;
        }

        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropForeign(['barang_id']);
        });

        if (!Schema::hasColumn('peminjaman', 'peralatan_id')) {
            Schema::table('peminjaman', function (Blueprint $table) {
                $table->unsignedBigInteger('peralatan_id')->nullable()->after('barang_id');
                $table->foreign('peralatan_id')->references('id')->on('peralatans')->nullOnDelete();
            });
        }

        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn('barang_id');
        });
    }
};

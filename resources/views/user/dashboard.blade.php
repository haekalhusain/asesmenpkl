@extends('layouts.user')
@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <h1 class="page-title">Dashboard</h1>
    <a href="{{ route('user.peminjaman.create') }}" class="btn btn-primary">
        + Tambah Peminjaman
    </a>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon si-gold">≡</div>
        <div>
            <div class="stat-number">{{ $totalPeminjaman }}</div>
            <div class="stat-label">Total Peminjaman</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-red">◷</div>
        <div>
            <div class="stat-number">{{ $sedangDipinjam }}</div>
            <div class="stat-label">Sedang Dipinjam</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-green">✓</div>
        <div>
            <div class="stat-number">{{ $sudahKembali }}</div>
            <div class="stat-label">Sudah Dikembalikan</div>
        </div>
    </div>
</div>

<div class="table-wrapper">
    <div class="table-toolbar">
        <span class="page-title" style="font-size:1rem;">Peminjaman Terbaru</span>
        <a href="{{ route('user.peminjaman.index') }}" class="btn btn-neutral">Lihat Semua</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>Peralatan</th>
                <th>Tanggal Pinjam</th>
                <th>Rencana Kembali</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($riwayatTerbaru as $item)
            <tr>
                <td>{{ $item->peralatan->nama_peralatan ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</td>
                <td>{{ $item->tanggal_rencana_kembali
                        ? \Carbon\Carbon::parse($item->tanggal_rencana_kembali)->format('d M Y')
                        : '-' }}</td>
                <td>
                    @if($item->status === 'dipinjam')
                        <span class="badge badge-warning">Dipinjam</span>
                    @elseif($item->status === 'dikembalikan')
                        <span class="badge badge-success">Dikembalikan</span>
                    @else
                        <span class="badge badge-danger">Terlambat</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('user.peminjaman.show', $item) }}" class="btn btn-neutral">Detail</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5">
                    <div class="empty-state">
                        <div class="empty-icon">≡</div>
                        <p>Belum ada data peminjaman</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

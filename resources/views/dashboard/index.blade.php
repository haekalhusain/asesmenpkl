@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <h1 class="page-title">Dashboard</h1>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon si-gold">◉</div>
        <div>
            <div class="stat-number">{{ $totalPengguna }}</div>
            <div class="stat-label">Total Pengguna</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-blue">◈</div>
        <div>
            <div class="stat-number">{{ $totalPeralatan }}</div>
            <div class="stat-label">Total Peralatan</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-green">≡</div>
        <div>
            <div class="stat-number">{{ $totalPeminjaman }}</div>
            <div class="stat-label">Total Peminjaman</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon si-red">◷</div>
        <div>
            <div class="stat-number">{{ $peminjamanAktif }}</div>
            <div class="stat-label">Sedang Dipinjam</div>
        </div>
    </div>
</div>

<div class="table-wrapper">
    <div class="table-toolbar">
        <span class="page-title" style="font-size:1rem;">Peminjaman Terbaru</span>
        <a href="{{ route('peminjaman.index') }}" class="btn btn-neutral">Lihat Semua</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>Peminjam</th>
                <th>Peralatan</th>
                <th>Tanggal Pinjam</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjamanTerbaru as $item)
            <tr>
                <td>{{ $item->pengguna->name ?? '-' }}</td>
                <td>{{ $item->peralatan->nama_peralatan ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</td>
                <td>
                    @if($item->status === 'dipinjam')
                        <span class="badge badge-warning">Dipinjam</span>
                    @elseif($item->status === 'dikembalikan')
                        <span class="badge badge-success">Dikembalikan</span>
                    @else
                        <span class="badge badge-danger">Terlambat</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4">
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

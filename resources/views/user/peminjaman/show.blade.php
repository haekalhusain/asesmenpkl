@extends('layouts.user')
@section('title', 'Detail Peminjaman')

@section('content')
<div class="page-header">
    <h1 class="page-title">Detail Peminjaman</h1>
    <a href="{{ route('user.peminjaman.index') }}" class="btn btn-neutral">Kembali</a>
</div>

<div class="card" style="max-width:600px; padding:1.75rem;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; padding-bottom:1rem; border-bottom:1px solid var(--border);">
        <div>
            <p style="font-size:0.75rem; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px;">Kode Peminjaman</p>
            <p style="font-family:'Rajdhani',sans-serif; font-size:1.2rem; font-weight:700; color:var(--gold);">
                {{ $peminjaman->kode_peminjaman }}
            </p>
        </div>
        <div>
            @if($peminjaman->status === 'dipinjam')
                <span class="badge badge-warning" style="font-size:0.85rem; padding:6px 14px;">Dipinjam</span>
            @elseif($peminjaman->status === 'dikembalikan')
                <span class="badge badge-success" style="font-size:0.85rem; padding:6px 14px;">Dikembalikan</span>
            @else
                <span class="badge badge-danger" style="font-size:0.85rem; padding:6px 14px;">Terlambat</span>
            @endif
        </div>
    </div>

    <div class="detail-grid">
        <div class="detail-item">
            <label>Peralatan</label>
            <p>{{ $peminjaman->peralatan->nama_peralatan ?? '-' }}</p>
        </div>
        <div class="detail-item">
            <label>Kode Peralatan</label>
            <p>{{ $peminjaman->peralatan->kode_peralatan ?? '-' }}</p>
        </div>
        <div class="detail-item">
            <label>Jumlah Dipinjam</label>
            <p>{{ $peminjaman->jumlah }} unit</p>
        </div>
        <div class="detail-item">
            <label>Tanggal Pinjam</label>
            <p>{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d F Y') }}</p>
        </div>
        <div class="detail-item">
            <label>Rencana Kembali</label>
            <p>{{ $peminjaman->tanggal_rencana_kembali
                    ? \Carbon\Carbon::parse($peminjaman->tanggal_rencana_kembali)->format('d F Y')
                    : '-' }}</p>
        </div>
        <div class="detail-item">
            <label>Tanggal Dikembalikan</label>
            <p>{{ $peminjaman->tanggal_kembali
                    ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d F Y')
                    : '-' }}</p>
        </div>
        @if($peminjaman->keterangan)
        <div class="detail-item" style="grid-column:1/-1">
            <label>Keterangan</label>
            <p>{{ $peminjaman->keterangan }}</p>
        </div>
        @endif
    </div>
</div>
@endsection

@extends('layouts.user')
@section('title', 'Detail Peminjaman')

@section('content')

<div class="page-header">
    <h1 class="page-title">Detail Peminjaman</h1>
    <div class="action-group">
        <a href="{{ route('user.peminjaman.edit', $peminjaman) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('user.peminjaman.index') }}" class="btn btn-neutral">&#8592; Kembali</a>
    </div>
</div>

<div class="card" style="max-width:640px; padding:1.75rem;">

    {{-- Header: kode + status --}}
    <div style="display:flex; justify-content:space-between; align-items:center;
                margin-bottom:1.5rem; padding-bottom:1rem; border-bottom:1px solid var(--border);">
        <div>
            <p style="font-size:0.72rem; color:var(--text-muted); text-transform:uppercase;
                      letter-spacing:0.5px; font-family:'Rajdhani',sans-serif;">
                Kode Peminjaman
            </p>
            <p style="font-family:'Rajdhani',sans-serif; font-size:1.25rem;
                      font-weight:700; color:var(--gold);">
                {{ $peminjaman->kode_peminjaman }}
            </p>
        </div>
        @php $st = $peminjaman->status instanceof \App\Enums\LoanStatus ? $peminjaman->status->value : $peminjaman->status; @endphp
        @if($st === 'Dipinjam')
            <span class="badge badge-warning" style="font-size:0.82rem; padding:5px 12px;">Dipinjam</span>
        @elseif($st === 'Dikembalikan')
            <span class="badge badge-success" style="font-size:0.82rem; padding:5px 12px;">Dikembalikan</span>
        @else
            <span class="badge badge-danger" style="font-size:0.82rem; padding:5px 12px;">Terlambat</span>
        @endif
    </div>

    {{-- Detail grid --}}
    <div class="detail-grid">
        <div class="detail-item">
            <label>Peminjam</label>
            <p>{{ $peminjaman->pengguna->nama_peminjam ?? '-' }}</p>
        </div>
        <div class="detail-item">
            <label>Kelas</label>
            <p>{{ $peminjaman->pengguna->kelas ?? '-' }}</p>
        </div>
        <div class="detail-item">
            <label>Barang</label>
            <p>{{ $peminjaman->barang->nama_barang ?? '-' }}</p>
        </div>
        <div class="detail-item">
            <label>Kategori</label>
            <p>{{ $peminjaman->barang->kategori_barang ?? '-' }}</p>
        </div>
        <div class="detail-item">
            <label>Jumlah</label>
            <p>{{ $peminjaman->jumlah }} unit</p>
        </div>
        <div class="detail-item">
            <label>Tanggal Pinjam</label>
            <p>{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d F Y') }}</p>
        </div>
        <div class="detail-item">
            <label>Rencana Kembali</label>
            <p>
                {{ $peminjaman->tanggal_rencana_kembali
                    ? \Carbon\Carbon::parse($peminjaman->tanggal_rencana_kembali)->format('d F Y')
                    : '-' }}
            </p>
        </div>
        <div class="detail-item">
            <label>Tanggal Dikembalikan</label>
            <p>
                {{ $peminjaman->tanggal_kembali
                    ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d F Y')
                    : '-' }}
            </p>
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

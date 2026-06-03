@extends('layouts.user')
@section('title', 'Detail Barang')

@section('content')

<div class="page-header">
    <h1 class="page-title">Detail Barang</h1>
    <div class="action-group">
        <a href="{{ route('user.barang.edit', $barang) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('user.barang.index') }}" class="btn btn-neutral">&#8592; Kembali</a>
    </div>
</div>

<div class="card" style="max-width:640px; padding:1.75rem;">
    <div style="margin-bottom:1.5rem; padding-bottom:1.25rem; border-bottom:1px solid var(--border);">
        @if($barang->gambar)
            <img src="{{ asset('assets/' . $barang->gambar) }}"
                 alt="Gambar {{ $barang->nama_barang }}"
                 style="max-width:100%; border-radius:12px; margin-bottom:1rem; object-fit:cover;">
        @endif
        <p style="font-family:'Rajdhani',sans-serif; font-size:1.4rem; font-weight:700; color:var(--text-primary);">
            {{ $barang->nama_barang }}
        </p>
        <p style="color:var(--text-muted); font-size:0.85rem; margin-top:3px;">
            Kategori: {{ $barang->kategori_barang }}
        </p>
    </div>

    <div class="detail-grid">
        <div class="detail-item">
            <label>Nama Barang</label>
            <p>{{ $barang->nama_barang }}</p>
        </div>
        <div class="detail-item">
            <label>Kategori</label>
            <p>{{ $barang->kategori_barang }}</p>
        </div>
        <div class="detail-item">
            <label>Stok Tersedia</label>
            <p><span class="badge badge-info">{{ $barang->stok }} unit</span></p>
        </div>
        <div class="detail-item">
            <label>Kondisi</label>
            <p>
                @if(strtolower($barang->kondisi_barang) === 'baik')
                    <span class="badge badge-success">Baik</span>
                @elseif(strtolower($barang->kondisi_barang) === 'rusak ringan')
                    <span class="badge badge-warning">Rusak Ringan</span>
                @else
                    <span class="badge badge-danger">{{ $barang->kondisi_barang }}</span>
                @endif
            </p>
        </div>
        <div class="detail-item" style="grid-column:1/-1">
            <label>Dibuat</label>
            <p>{{ $barang->created_at->format('d M Y') }}</p>
        </div>
    </div>
</div>

@endsection

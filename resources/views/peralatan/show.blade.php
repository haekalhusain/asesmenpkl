@extends('layouts.app')
@section('title', 'Detail Peralatan')

@section('content')
<div class="page-header">
    <h1 class="page-title">Detail Peralatan</h1>
    <div class="action-group">
        <a href="{{ route('peralatan.edit', $peralatan) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('peralatan.index') }}" class="btn btn-neutral">Kembali</a>
    </div>
</div>

<div class="card" style="max-width:640px; padding:1.75rem;">
    <div style="display:flex; gap:1.25rem; align-items:flex-start; margin-bottom:1.5rem; padding-bottom:1.25rem; border-bottom:1px solid var(--border);">
        @if($peralatan->foto)
            <img src="{{ asset('storage/peralatan/'.$peralatan->foto) }}"
                 style="width:100px;height:100px;object-fit:cover;border-radius:8px;border:1px solid var(--border);">
        @else
            <div style="width:100px;height:100px;background:var(--bg-secondary);border-radius:8px;border:1px solid var(--border);display:flex;align-items:center;justify-content:center;font-size:2rem;color:var(--text-muted);">◈</div>
        @endif
        <div>
            <p style="font-family:'Rajdhani',sans-serif;font-size:1.4rem;font-weight:700;color:var(--text-primary);">
                {{ $peralatan->nama_peralatan }}
            </p>
            <p style="color:var(--text-muted);font-size:0.85rem;margin-top:3px;">{{ $peralatan->kode_peralatan }}</p>
        </div>
    </div>

    <div class="detail-grid">
        <div class="detail-item">
            <label>Kategori</label>
            <p>{{ $peralatan->kategori ?? '-' }}</p>
        </div>
        <div class="detail-item">
            <label>Stok Tersedia</label>
            <p><span class="badge badge-info">{{ $peralatan->stok }} unit</span></p>
        </div>
        <div class="detail-item">
            <label>Kondisi</label>
            <p>
                @if($peralatan->kondisi === 'baik')
                    <span class="badge badge-success">Baik</span>
                @elseif($peralatan->kondisi === 'rusak_ringan')
                    <span class="badge badge-warning">Rusak Ringan</span>
                @else
                    <span class="badge badge-danger">Rusak Berat</span>
                @endif
            </p>
        </div>
        <div class="detail-item">
            <label>Total Pernah Dipinjam</label>
            <p>{{ $peralatan->peminjamans->count() }} kali</p>
        </div>
        <div class="detail-item" style="grid-column:1/-1">
            <label>Deskripsi</label>
            <p>{{ $peralatan->deskripsi ?? '-' }}</p>
        </div>
    </div>
</div>
@endsection

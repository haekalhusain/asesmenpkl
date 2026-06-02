@extends('layouts.app')
@section('title', 'Detail Pengguna')

@section('content')

<div class="page-header">
    <h1 class="page-title">Detail Pengguna</h1>
    <div class="action-group">
        <a href="{{ route('pengguna.edit', $pengguna) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('pengguna.index') }}" class="btn btn-neutral">&#8592; Kembali</a>
    </div>
</div>

<div class="card" style="max-width:640px; padding:1.75rem; margin-bottom:1.5rem;">
    <div class="detail-grid">
        <div class="detail-item">
            <label>No Pengguna</label>
            <p>{{ $pengguna->no_pengguna }}</p>
        </div>
        <div class="detail-item">
            <label>Nama Lengkap</label>
            <p>{{ $pengguna->name }}</p>
        </div>
        <div class="detail-item">
            <label>Email</label>
            <p>{{ $pengguna->email }}</p>
        </div>
        <div class="detail-item">
            <label>No HP</label>
            <p>{{ $pengguna->no_hp ?? '-' }}</p>
        </div>
        <div class="detail-item">
            <label>Total Peminjaman</label>
            <p>{{ $pengguna->peminjamans->count() }} kali</p>
        </div>
        <div class="detail-item">
            <label>Terdaftar Sejak</label>
            <p>{{ $pengguna->created_at->format('d M Y') }}</p>
        </div>
        <div class="detail-item" style="grid-column:1/-1">
            <label>Alamat</label>
            <p>{{ $pengguna->alamat ?? '-' }}</p>
        </div>
    </div>
</div>

@if($pengguna->peminjamans->count() > 0)
<div class="table-wrapper" style="max-width:640px;">
    <div class="table-toolbar">
        <span class="page-title" style="font-size:1rem;">Riwayat Peminjaman</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Peralatan</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengguna->peminjamans as $p)
            <tr>
                <td>{{ $p->peralatan->nama_peralatan ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') }}</td>
                <td>
                    {{ $p->tanggal_kembali
                        ? \Carbon\Carbon::parse($p->tanggal_kembali)->format('d M Y')
                        : '-' }}
                </td>
                <td>
                    @php $st = $p->status->value ?? $p->status; @endphp
                    @if($st === 'dipinjam')
                        <span class="badge badge-warning">Dipinjam</span>
                    @elseif($st === 'dikembalikan')
                        <span class="badge badge-success">Dikembalikan</span>
                    @else
                        <span class="badge badge-danger">Terlambat</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@endsection

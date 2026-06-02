@extends('layouts.user')
@section('title', 'Riwayat Peminjaman')

@section('content')
<div class="page-header">
    <h1 class="page-title">Riwayat Peminjaman</h1>
    <a href="{{ route('user.peminjaman.create') }}" class="btn btn-primary">
        + Tambah Peminjaman
    </a>
</div>

<div class="table-wrapper">
    <div class="table-toolbar">
        <form method="GET" action="{{ route('user.peminjaman.index') }}"
              style="display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
            <div class="search-box">
                <span class="search-icon">&#9906;</span>
                <input type="text" name="search" placeholder="Cari nama peralatan..."
                       value="{{ request('search') }}">
            </div>
            <select name="status" class="filter-select">
                <option value="">Semua Status</option>
                <option value="dipinjam"     {{ request('status') == 'dipinjam'     ? 'selected' : '' }}>Dipinjam</option>
                <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                <option value="terlambat"    {{ request('status') == 'terlambat'    ? 'selected' : '' }}>Terlambat</option>
            </select>
            <button type="submit" class="btn btn-neutral">Filter</button>
            @if(request('search') || request('status'))
                <a href="{{ route('user.peminjaman.index') }}" class="btn btn-neutral">Reset</a>
            @endif
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Peralatan</th>
                <th>Jumlah</th>
                <th>Tanggal Pinjam</th>
                <th>Rencana Kembali</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjamans as $i => $item)
            <tr>
                <td>{{ $peminjamans->firstItem() + $i }}</td>
                <td>{{ $item->kode_peminjaman }}</td>
                <td>{{ $item->peralatan->nama_peralatan ?? '-' }}</td>
                <td>{{ $item->jumlah }} unit</td>
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
                <td colspan="8">
                    <div class="empty-state">
                        <div class="empty-icon">≡</div>
                        <p>Belum ada data peminjaman</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination">
        {{ $peminjamans->withQueryString()->links('vendor.pagination.custom') }}
    </div>
</div>
@endsection

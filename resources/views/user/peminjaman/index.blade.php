@extends('layouts.user')
@section('title', 'Peminjaman Saya')

@section('content')

<div class="page-header">
    <h1 class="page-title">Peminjaman Saya</h1>
    <a href="{{ route('user.peminjaman.create') }}" class="btn btn-primary">+ Tambah Peminjaman</a>
</div>

<div class="table-wrapper">
    <div class="table-toolbar">
        <form method="GET" action="{{ route('user.peminjaman.index') }}"
              style="display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
            <div class="search-box">
                <span class="search-icon">&#9906;</span>
                <input type="text"
                       name="search"
                       placeholder="Cari nama barang..."
                       value="{{ request('search') }}">
            </div>
            <select name="status" class="filter-select">
                <option value="">Semua Status</option>
                <option value="Dipinjam"     {{ request('status') === 'Dipinjam'     ? 'selected' : '' }}>Dipinjam</option>
                <option value="Dikembalikan" {{ request('status') === 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
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
                <th>Barang</th>
                <th>Jumlah</th>
                <th>Tgl Pinjam</th>
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
                <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                <td>{{ $item->jumlah }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</td>
                <td>
                    {{ $item->tanggal_rencana_kembali
                        ? \Carbon\Carbon::parse($item->tanggal_rencana_kembali)->format('d M Y')
                        : '-' }}
                </td>
                <td>
                    @php $st = $item->status instanceof \App\Enums\LoanStatus ? $item->status->value : $item->status; @endphp
                    @if($st === 'Dipinjam')
                        <span class="badge badge-warning">Dipinjam</span>
                    @elseif($st === 'Dikembalikan')
                        <span class="badge badge-success">Dikembalikan</span>
                    @else
                        <span class="badge badge-danger">Terlambat</span>
                    @endif
                </td>
                <td>
                    <div class="action-group">
                        <a href="{{ route('user.peminjaman.show', $item) }}" class="btn btn-neutral">Detail</a>

                        @if($st === 'Dipinjam')
                            <a href="{{ route('user.peminjaman.edit', $item) }}" class="btn btn-warning">Edit</a>
                            <form method="POST" action="{{ route('user.peminjaman.destroy', $item) }}"
                                  onsubmit="return confirm('Hapus data peminjaman ini? Stok barang akan dikembalikan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">
                    <div class="empty-state">
                        <div class="empty-icon">&#8801;</div>
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

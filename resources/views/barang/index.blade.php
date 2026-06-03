@extends('layouts.app')
@section('title', 'Data Barang')

@section('content')

<div class="page-header">
    <h1 class="page-title">Data Barang</h1>
    <a href="{{ route('barang.create') }}" class="btn btn-primary">+ Tambah Barang</a>
</div>

<div class="table-wrapper">
    <div class="table-toolbar">
        <form method="GET" action="{{ route('barang.index') }}">
            <div class="search-box">
                <span class="search-icon">&#9906;</span>
                <input type="text"
                       name="search"
                       placeholder="Cari nama atau kategori..."
                       value="{{ request('search') }}">
            </div>
        </form>
        @if(request('search'))
            <a href="{{ route('barang.index') }}" class="btn btn-neutral">Reset</a>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Kondisi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($barangs as $i => $item)
            <tr>
                <td>{{ $barangs->firstItem() + $i }}</td>
                <td>{{ $item->nama_barang }}</td>
                <td>{{ $item->kategori_barang }}</td>
                <td><span class="badge badge-info">{{ $item->stok }}</span></td>
                <td>
                    @if(strtolower($item->kondisi_barang) === 'baik')
                        <span class="badge badge-success">Baik</span>
                    @elseif(strtolower($item->kondisi_barang) === 'rusak_ringan')
                        <span class="badge badge-warning">Rusak Ringan</span>
                    @else
                        <span class="badge badge-danger">{{ $item->kondisi_barang }}</span>
                    @endif
                </td>
                <td>
                    <div class="action-group">
                        <a href="{{ route('barang.show', $item) }}" class="btn btn-neutral">Detail</a>
                        <a href="{{ route('barang.edit', $item) }}" class="btn btn-warning">Edit</a>
                        <form method="POST" action="{{ route('barang.destroy', $item) }}"
                              onsubmit="return confirm('Hapus barang ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">
                    <div class="empty-state">
                        <div class="empty-icon">&#9672;</div>
                        <p>Belum ada data barang</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination">
        {{ $barangs->withQueryString()->links('vendor.pagination.custom') }}
    </div>
</div>

@endsection

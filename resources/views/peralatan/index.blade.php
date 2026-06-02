@extends('layouts.app')
@section('title', 'Data Peralatan')

@section('content')

<div class="page-header">
    <h1 class="page-title">Data Peralatan</h1>
    <a href="{{ route('peralatan.create') }}" class="btn btn-primary">+ Tambah Peralatan</a>
</div>

<div class="table-wrapper">
    <div class="table-toolbar">
        <form method="GET" action="{{ route('peralatan.index') }}">
            <div class="search-box">
                <span class="search-icon">&#9906;</span>
                <input type="text"
                       name="search"
                       placeholder="Cari nama, kode, kategori..."
                       value="{{ request('search') }}">
            </div>
        </form>
        @if(request('search'))
            <a href="{{ route('peralatan.index') }}" class="btn btn-neutral">Reset</a>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Foto</th>
                <th>Kode</th>
                <th>Nama Peralatan</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Kondisi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peralatans as $i => $item)
            <tr>
                <td>{{ $peralatans->firstItem() + $i }}</td>
                <td>
                    @if($item->foto)
                        <img src="{{ asset('storage/'.$item->foto) }}"
                             alt="{{ $item->nama_peralatan }}"
                             class="preview-img">
                    @else
                        <div style="width:48px; height:48px; background:var(--bg-secondary);
                                    border-radius:6px; border:1px solid var(--border);
                                    display:flex; align-items:center; justify-content:center;
                                    color:var(--text-muted); font-size:1.2rem;">&#9672;</div>
                    @endif
                </td>
                <td>{{ $item->kode_peralatan }}</td>
                <td>{{ $item->nama_peralatan }}</td>
                <td>{{ $item->kategori ?? '-' }}</td>
                <td><span class="badge badge-info">{{ $item->stok }}</span></td>
                <td>
                    @if($item->kondisi === 'baik')
                        <span class="badge badge-success">Baik</span>
                    @elseif($item->kondisi === 'rusak_ringan')
                        <span class="badge badge-warning">Rusak Ringan</span>
                    @else
                        <span class="badge badge-danger">Rusak Berat</span>
                    @endif
                </td>
                <td>
                    <div class="action-group">
                        <a href="{{ route('peralatan.show', $item) }}" class="btn btn-neutral">Detail</a>
                        <a href="{{ route('peralatan.edit', $item) }}" class="btn btn-warning">Edit</a>
                        <form method="POST" action="{{ route('peralatan.destroy', $item) }}"
                              onsubmit="return confirm('Hapus peralatan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">
                    <div class="empty-state">
                        <div class="empty-icon">&#9672;</div>
                        <p>Belum ada data peralatan</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination">
        {{ $peralatans->withQueryString()->links('vendor.pagination.custom') }}
    </div>
</div>

@endsection

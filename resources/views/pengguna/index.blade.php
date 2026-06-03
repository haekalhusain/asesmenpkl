@extends('layouts.app')
@section('title', 'Data Pengguna')

@section('content')

<div class="page-header">
    <h1 class="page-title">Data Pengguna</h1>

</div>

<div class="table-wrapper">
    <div class="table-toolbar">
        <form method="GET" action="{{ route('pengguna.index') }}">
            <div class="search-box">
                <span class="search-icon">&#9906;</span>
                <input type="text"
                       name="search"
                       placeholder="Cari nama, kelas, jurusan, no hp..."
                       value="{{ request('search') }}">
            </div>
        </form>
        @if(request('search'))
            <a href="{{ route('pengguna.index') }}" class="btn btn-neutral">Reset</a>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Peminjam</th>
                <th>Kelas</th>
                <th>Jurusan</th>
                <th>No HP</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($penggunas as $i => $pengguna)
            <tr>
                <td>{{ $penggunas->firstItem() + $i }}</td>
                <td>{{ $pengguna->nama_peminjam }}</td>
                <td>{{ $pengguna->kelas }}</td>
                <td>{{ $pengguna->jurusan ?? '-' }}</td>
                <td>{{ $pengguna->no_hp ?? '-' }}</td>
                <td>
                    <div class="action-group">
                        <a href="{{ route('pengguna.show', $pengguna) }}" class="btn btn-neutral">Detail</a>
                       
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">
                    <div class="empty-state">
                        <div class="empty-icon">&#9673;</div>
                        <p>Belum ada data peminjam</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination">
        {{ $penggunas->withQueryString()->links('vendor.pagination.custom') }}
    </div>
</div>

@endsection

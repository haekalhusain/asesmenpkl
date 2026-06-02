@extends('layouts.app')
@section('title', 'Data Pengguna')

@section('content')

<div class="page-header">
    <h1 class="page-title">Data Pengguna</h1>
    <a href="{{ route('pengguna.create') }}" class="btn btn-primary">+ Tambah Pengguna</a>
</div>

<div class="table-wrapper">
    <div class="table-toolbar">
        <form method="GET" action="{{ route('pengguna.index') }}">
            <div class="search-box">
                <span class="search-icon">&#9906;</span>
                <input type="text"
                       name="search"
                       placeholder="Cari nama, email, no pengguna..."
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
                <th>No Pengguna</th>
                <th>Nama</th>
                <th>Email</th>
                <th>No HP</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($penggunas as $i => $pengguna)
            <tr>
                <td>{{ $penggunas->firstItem() + $i }}</td>
                <td>{{ $pengguna->no_pengguna }}</td>
                <td>{{ $pengguna->name }}</td>
                <td>{{ $pengguna->email }}</td>
                <td>{{ $pengguna->no_hp ?? '-' }}</td>
                <td>
                    <div class="action-group">
                        <a href="{{ route('pengguna.show', $pengguna) }}" class="btn btn-neutral">Detail</a>
                        <a href="{{ route('pengguna.edit', $pengguna) }}" class="btn btn-warning">Edit</a>
                        <form method="POST" action="{{ route('pengguna.destroy', $pengguna) }}"
                              onsubmit="return confirm('Hapus pengguna ini?')">
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
                        <div class="empty-icon">&#9673;</div>
                        <p>Belum ada data pengguna</p>
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

@extends('layouts.app')
@section('title', 'Edit Peminjam')

@section('content')

<div class="page-header">
    <h1 class="page-title">Edit Peminjam</h1>
    <a href="{{ route('pengguna.index') }}" class="btn btn-neutral">&#8592; Kembali</a>
</div>

<div class="form-card">
    <form method="POST" action="{{ route('pengguna.update', $pengguna) }}" novalidate>
        @csrf
        @method('PUT')

        {{-- Nama Peminjam --}}
        <div class="form-group">
            <label class="form-label" for="nama_peminjam">Nama Peminjam</label>
            <input type="text"
                   id="nama_peminjam"
                   name="nama_peminjam"
                   class="form-control {{ $errors->has('nama_peminjam') ? 'is-invalid' : '' }}"
                   value="{{ old('nama_peminjam', $pengguna->nama_peminjam) }}">
            @error('nama_peminjam')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        {{-- Kelas --}}
        <div class="form-group">
            <label class="form-label" for="kelas">Kelas</label>
            <input type="text"
                   id="kelas"
                   name="kelas"
                   class="form-control {{ $errors->has('kelas') ? 'is-invalid' : '' }}"
                   value="{{ old('kelas', $pengguna->kelas) }}">
            @error('kelas')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        {{-- Jurusan --}}
        <div class="form-group">
            <label class="form-label" for="jurusan">Jurusan</label>
            <input type="text"
                   id="jurusan"
                   name="jurusan"
                   class="form-control {{ $errors->has('jurusan') ? 'is-invalid' : '' }}"
                   value="{{ old('jurusan', $pengguna->jurusan) }}">
            @error('jurusan')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        {{-- No HP --}}
        <div class="form-group">
            <label class="form-label" for="no_hp">No HP</label>
            <input type="text"
                   id="no_hp"
                   name="no_hp"
                   class="form-control {{ $errors->has('no_hp') ? 'is-invalid' : '' }}"
                   value="{{ old('no_hp', $pengguna->no_hp) }}">
            @error('no_hp')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('pengguna.index') }}" class="btn btn-neutral">Batal</a>
        </div>
    </form>
</div>

@endsection

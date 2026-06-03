@extends('layouts.app')
@section('title', 'Tambah Peminjam')

@section('content')

<div class="page-header">
    <h1 class="page-title">Tambah Peminjam</h1>
    <a href="{{ route('pengguna.index') }}" class="btn btn-neutral">&#8592; Kembali</a>
</div>

<div class="form-card">
    <form method="POST" action="{{ route('pengguna.store') }}" novalidate>
        @csrf

        {{-- Nama Peminjam --}}
        <div class="form-group">
            <label class="form-label" for="nama_peminjam">Nama Peminjam</label>
            <input type="text"
                   id="nama_peminjam"
                   name="nama_peminjam"
                   class="form-control {{ $errors->has('nama_peminjam') ? 'is-invalid' : '' }}"
                   value="{{ old('nama_peminjam') }}"
                   placeholder="Nama peminjam">
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
                   value="{{ old('kelas') }}"
                   placeholder="Kelas">
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
                   value="{{ old('jurusan') }}"
                   placeholder="Jurusan">
            @error('jurusan')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        {{-- No HP --}}
        <div class="form-group">
            <label class="form-label" for="no_hp">
                No HP
                <span style="color:var(--text-muted); font-weight:400; text-transform:none;">(opsional)</span>
            </label>
            <input type="text"
                   id="no_hp"
                   name="no_hp"
                   class="form-control {{ $errors->has('no_hp') ? 'is-invalid' : '' }}"
                   value="{{ old('no_hp') }}"
                   placeholder="08xxxxxxxxxx">
            @error('no_hp')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('pengguna.index') }}" class="btn btn-neutral">Batal</a>
        </div>
    </form>
</div>

@endsection

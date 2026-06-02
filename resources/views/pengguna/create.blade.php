@extends('layouts.app')
@section('title', 'Tambah Pengguna')

@section('content')

<div class="page-header">
    <h1 class="page-title">Tambah Pengguna</h1>
    <a href="{{ route('pengguna.index') }}" class="btn btn-neutral">&#8592; Kembali</a>
</div>

<div class="form-card">
    <form method="POST" action="{{ route('pengguna.store') }}" novalidate>
        @csrf

        {{-- No Pengguna --}}
        <div class="form-group">
            <label class="form-label" for="no_pengguna">No Pengguna</label>
            <input type="text"
                   id="no_pengguna"
                   name="no_pengguna"
                   class="form-control {{ $errors->has('no_pengguna') ? 'is-invalid' : '' }}"
                   value="{{ old('no_pengguna') }}"
                   placeholder="Contoh: USR-001">
            @error('no_pengguna')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        {{-- Nama Lengkap --}}
        <div class="form-group">
            <label class="form-label" for="name">Nama Lengkap</label>
            <input type="text"
                   id="name"
                   name="name"
                   class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                   value="{{ old('name') }}"
                   placeholder="Nama lengkap">
            @error('name')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        {{-- Email --}}
        <div class="form-group">
            <label class="form-label" for="email">Email</label>
            <input type="email"
                   id="email"
                   name="email"
                   class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                   value="{{ old('email') }}"
                   placeholder="email@example.com">
            @error('email')
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
                   class="form-control"
                   value="{{ old('no_hp') }}"
                   placeholder="08xxxxxxxxxx">
        </div>

        {{-- Alamat --}}
        <div class="form-group">
            <label class="form-label" for="alamat">
                Alamat
                <span style="color:var(--text-muted); font-weight:400; text-transform:none;">(opsional)</span>
            </label>
            <textarea id="alamat"
                      name="alamat"
                      class="form-control"
                      rows="3"
                      placeholder="Alamat lengkap">{{ old('alamat') }}</textarea>
        </div>

        {{-- Password --}}
        <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <input type="password"
                   id="password"
                   name="password"
                   class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                   placeholder="Minimal 8 karakter">
            @error('password')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        {{-- Konfirmasi Password --}}
        <div class="form-group">
            <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
            <input type="password"
                   id="password_confirmation"
                   name="password_confirmation"
                   class="form-control"
                   placeholder="Ulangi password">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('pengguna.index') }}" class="btn btn-neutral">Batal</a>
        </div>
    </form>
</div>

@endsection

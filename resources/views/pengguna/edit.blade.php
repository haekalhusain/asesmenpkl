@extends('layouts.app')
@section('title', 'Edit Pengguna')

@section('content')

<div class="page-header">
    <h1 class="page-title">Edit Pengguna</h1>
    <a href="{{ route('pengguna.index') }}" class="btn btn-neutral">&#8592; Kembali</a>
</div>

<div class="form-card">
    <form method="POST" action="{{ route('pengguna.update', $pengguna) }}" novalidate>
        @csrf
        @method('PUT')

        {{-- No Pengguna --}}
        <div class="form-group">
            <label class="form-label" for="no_pengguna">No Pengguna</label>
            <input type="text"
                   id="no_pengguna"
                   name="no_pengguna"
                   class="form-control {{ $errors->has('no_pengguna') ? 'is-invalid' : '' }}"
                   value="{{ old('no_pengguna', $pengguna->no_pengguna) }}">
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
                   value="{{ old('name', $pengguna->name) }}">
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
                   value="{{ old('email', $pengguna->email) }}">
            @error('email')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        {{-- No HP --}}
        <div class="form-group">
            <label class="form-label" for="no_hp">No HP</label>
            <input type="text"
                   id="no_hp"
                   name="no_hp"
                   class="form-control"
                   value="{{ old('no_hp', $pengguna->no_hp) }}">
        </div>

        {{-- Alamat --}}
        <div class="form-group">
            <label class="form-label" for="alamat">Alamat</label>
            <textarea id="alamat"
                      name="alamat"
                      class="form-control"
                      rows="3">{{ old('alamat', $pengguna->alamat) }}</textarea>
        </div>

        {{-- Password Baru --}}
        <div class="form-group">
            <label class="form-label" for="password">
                Password Baru
                <span style="color:var(--text-muted); font-weight:400; text-transform:none;">
                    (kosongkan jika tidak diganti)
                </span>
            </label>
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
            <label class="form-label" for="password_confirmation">Konfirmasi Password Baru</label>
            <input type="password"
                   id="password_confirmation"
                   name="password_confirmation"
                   class="form-control">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('pengguna.index') }}" class="btn btn-neutral">Batal</a>
        </div>
    </form>
</div>

@endsection

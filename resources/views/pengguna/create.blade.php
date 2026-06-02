@extends('layouts.app')
@section('title', 'Tambah Pengguna')

@section('content')
<div class="page-header">
    <h1 class="page-title">Tambah Pengguna</h1>
    <a href="{{ route('pengguna.index') }}" class="btn btn-neutral">Kembali</a>
</div>

<div class="form-card">
    <form method="POST" action="{{ route('pengguna.store') }}">
        @csrf

        <div class="form-group">
            <label class="form-label">No Pengguna</label>
            <input type="text" name="no_pengguna"
                   class="form-control {{ $errors->has('no_pengguna') ? 'is-invalid' : '' }}"
                   value="{{ old('no_pengguna') }}" placeholder="Contoh: USR-001">
            @error('no_pengguna')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="name"
                   class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                   value="{{ old('name') }}" placeholder="Nama lengkap">
            @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email"
                   class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                   value="{{ old('email') }}" placeholder="email@example.com">
            @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">No HP</label>
            <input type="text" name="no_hp"
                   class="form-control {{ $errors->has('no_hp') ? 'is-invalid' : '' }}"
                   value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx">
            @error('no_hp')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control" rows="3"
                      placeholder="Alamat lengkap">{{ old('alamat') }}</textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" name="password"
                   class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                   placeholder="Minimal 8 karakter">
            @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Konfirmasi Password</label>
            <input type="password" name="password_confirmation"
                   class="form-control" placeholder="Ulangi password">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('pengguna.index') }}" class="btn btn-neutral">Batal</a>
        </div>
    </form>
</div>
@endsection

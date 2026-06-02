@extends('layouts.app')
@section('title', 'Edit Pengguna')

@section('content')
<div class="page-header">
    <h1 class="page-title">Edit Pengguna</h1>
    <a href="{{ route('pengguna.index') }}" class="btn btn-neutral">Kembali</a>
</div>

<div class="form-card">
    <form method="POST" action="{{ route('pengguna.update', $pengguna) }}">
        @csrf @method('PUT')

        <div class="form-group">
            <label class="form-label">No Pengguna</label>
            <input type="text" name="no_pengguna"
                   class="form-control {{ $errors->has('no_pengguna') ? 'is-invalid' : '' }}"
                   value="{{ old('no_pengguna', $pengguna->no_pengguna) }}">
            @error('no_pengguna')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="name"
                   class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                   value="{{ old('name', $pengguna->name) }}">
            @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email"
                   class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                   value="{{ old('email', $pengguna->email) }}">
            @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">No HP</label>
            <input type="text" name="no_hp" class="form-control"
                   value="{{ old('no_hp', $pengguna->no_hp) }}">
        </div>

        <div class="form-group">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control"
                      rows="3">{{ old('alamat', $pengguna->alamat) }}</textarea>
        </div>

        <div class="form-group">
            <label class="form-label">
                Password Baru
                <span style="color:var(--text-muted); font-weight:400; text-transform:none;">
                    (kosongkan jika tidak diganti)
                </span>
            </label>
            <input type="password" name="password"
                   class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                   placeholder="Minimal 8 karakter">
            @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('pengguna.index') }}" class="btn btn-neutral">Batal</a>
        </div>
    </form>
</div>
@endsection

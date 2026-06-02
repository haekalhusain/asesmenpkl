@extends('layouts.auth')
@section('title', 'Login')

@section('content')
<div class="auth-card">
    <div class="auth-logo">
        <h1>AsesmenPKL</h1>
        <p>Sistem Peminjaman Lab TEFA PPLG</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email"
                   class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                   placeholder="email@example.com"
                   value="{{ old('email') }}" required autofocus>
            @error('email')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" name="password"
                   class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                   placeholder="••••••••" required>
            @error('password')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group" style="display:flex; align-items:center; gap:8px;">
            <input type="checkbox" name="remember" id="remember"
                   style="accent-color:var(--gold); width:14px; height:14px;">
            <label for="remember" style="font-size:0.83rem; color:var(--text-secondary); cursor:pointer;">
                Ingat saya
            </label>
        </div>

        <button type="submit" class="btn btn-primary"
                style="width:100%; justify-content:center; padding:10px;">
            Masuk
        </button>
    </form>
</div>
@endsection

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login &ndash; AsesmenPKL</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<div class="auth-wrapper">
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
                <label class="form-label" for="email">Email</label>
                <input type="email"
                       id="email"
                       name="email"
                       class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                       placeholder="email@example.com"
                       value="{{ old('email') }}"
                       required
                       autofocus>
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password"
                       id="password"
                       name="password"
                       class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                       placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;"
                       required>
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="display:flex; align-items:center; gap:8px;">
                <input type="checkbox"
                       name="remember"
                       id="remember"
                       style="accent-color:var(--gold); width:14px; height:14px;">
                <label for="remember" style="font-size:0.83rem; color:var(--text-secondary); cursor:pointer;">
                    Ingat saya
                </label>
            </div>

            <button type="submit"
                    class="btn btn-primary"
                    style="width:100%; justify-content:center; padding:10px;">
                Masuk
            </button>
        </form>
    </div>
</div>

</body>
</html>

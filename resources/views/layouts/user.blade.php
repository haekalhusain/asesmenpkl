<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Peminjaman') &ndash; AsesmenPKL</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>

<nav class="navbar">
    <a href="{{ route('user.dashboard') }}" class="navbar-brand">AsesmenPKL</a>
    <div class="navbar-right">
        <span>{{ Auth::user()->name }}</span>
        <span class="badge badge-neutral">{{ Auth::user()->no_pengguna }}</span>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-danger">Keluar</button>
        </form>
    </div>
</nav>

<div class="layout">
    <aside class="sidebar">
        <p class="sidebar-section">Menu</p>
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('user.dashboard') }}"
                   class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                    <span class="sidebar-icon">&#9635;</span> Dashboard
                </a>
            </li>
        </ul>

        <p class="sidebar-section">Barang</p>
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('user.barang.index') }}"
                   class="{{ request()->routeIs('user.barang.*') ? 'active' : '' }}">
                    <span class="sidebar-icon">&#9671;</span> Data Barang
                </a>
            </li>
        </ul>

        <p class="sidebar-section">Peminjaman</p>
        <ul class="sidebar-menu">

            <li>
                <a href="{{ route('user.peminjaman.index') }}"
                   class="{{ request()->routeIs('user.peminjaman.index') ? 'active' : '' }}">
                    <span class="sidebar-icon">&#8801;</span> Peminjaman
                </a>
            </li>
        </ul>
    </aside>

    <main class="main-content">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>
</div>

@stack('scripts')
</body>
</html>

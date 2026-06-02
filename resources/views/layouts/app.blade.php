<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') – AsesmenPKL</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>

<nav class="navbar">
    <a href="{{ route('dashboard') }}" class="navbar-brand">AsesmenPKL</a>
    <div class="navbar-right">
        <span>{{ Auth::user()->name }}</span>
        <span class="badge badge-warning">Admin</span>
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
                <a href="{{ route('dashboard') }}"
                   class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="sidebar-icon">▣</span> Dashboard
                </a>
            </li>
        </ul>

        <p class="sidebar-section">Master Data</p>
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('pengguna.index') }}"
                   class="{{ request()->routeIs('pengguna.*') ? 'active' : '' }}">
                    <span class="sidebar-icon">◉</span> Data Pengguna
                </a>
            </li>
            <li>
                <a href="{{ route('peralatan.index') }}"
                   class="{{ request()->routeIs('peralatan.*') ? 'active' : '' }}">
                    <span class="sidebar-icon">◈</span> Data Peralatan
                </a>
            </li>
        </ul>

        <p class="sidebar-section">Transaksi</p>
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('peminjaman.index') }}"
                   class="{{ request()->routeIs('peminjaman.*') ? 'active' : '' }}">
                    <span class="sidebar-icon">≡</span> Peminjaman
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

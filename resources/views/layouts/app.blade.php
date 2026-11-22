@php
    $u = auth()->user();
    $nav = match ($u->role) {
        'mahasiswa' => [
            ['mahasiswa.dashboard', 'Dashboard', 'dashboard', ['mahasiswa.dashboard']],
            ['mahasiswa.laporan.create', 'Buat Laporan', 'plus', ['mahasiswa.laporan.create']],
            ['mahasiswa.riwayat', 'Riwayat Laporan', 'list', ['mahasiswa.riwayat', 'laporan.show']],
            ['profil.edit', 'Profil', 'user', ['profil.*']],
        ],
        'pemimpin' => [
            ['pemimpin.dashboard', 'Executive Dashboard', 'target', ['pemimpin.dashboard']],
            ['pemimpin.monitoring', 'Monitoring Laporan', 'activity', ['pemimpin.monitoring']],
            ['pemimpin.analytics', 'Analytics', 'chart', ['pemimpin.analytics']],
            ['pemimpin.rekapitulasi', 'Rekapitulasi Laporan', 'file', ['pemimpin.rekapitulasi*', 'laporan.show']],
            ['pemimpin.insight', 'Insight & Evaluasi', 'spark', ['pemimpin.insight']],
            ['profil.edit', 'Profil', 'user', ['profil.*']],
        ],
        default => [
            ['admin.dashboard', 'Dashboard', 'dashboard', ['admin.dashboard']],
            ['admin.laporan.index', 'Manajemen Laporan', 'inbox', ['admin.laporan.index', 'laporan.show']],
            ['admin.gedung.index', 'Data Gedung', 'building', ['admin.gedung.*']],
            ['admin.ruangan.index', 'Data Ruangan', 'door', ['admin.ruangan.*']],
            ['admin.kategori.index', 'Kategori Fasilitas', 'tag', ['admin.kategori.*']],
            ['admin.users', 'Manajemen Pengguna', 'users', ['admin.users*']],
            ['admin.rekapitulasi', 'Rekapitulasi Laporan', 'file', ['admin.rekapitulasi']],
            ['profil.edit', 'Profil', 'user', ['profil.*']],
        ],
    };
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
@include('layouts.head')
</head>
<body>
<div class="app" data-app>
    <div class="nav-overlay" data-nav-close></div>

    <aside class="sidebar" aria-label="Navigasi utama">
        <a class="brand" href="{{ route($u->homeRoute()) }}">
            <span class="brand-mark">S</span>
            <span class="brand-text"><b>siakam</b><small>Sistem Pelaporan Fasilitas</small></span>
        </a>

        <nav class="nav">
            <span class="nav-label">{{ $u->roleLabel() }}</span>
            @foreach ($nav as [$route, $label, $icon, $patterns])
                <a class="nav-item {{ request()->routeIs(...$patterns) ? 'on' : '' }}" href="{{ route($route) }}"
                   @if (request()->routeIs(...$patterns)) aria-current="page" @endif>
                    <span class="nav-icon"><x-icon :name="$icon" :size="18" /></span>
                    <span>{{ $label }}</span>
                </a>
            @endforeach
        </nav>

        <div class="sidebar-foot">
            <div class="user-chip">
                <span class="avatar">{{ $u->initials() }}</span>
                <div class="user-meta">
                    <b>{{ $u->name }}</b>
                    <span>{{ $u->roleLabel() }}</span>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-ghost btn-block btn-sm" type="submit">
                    <x-icon name="logout" :size="16" /> Keluar
                </button>
            </form>
        </div>
    </aside>

    <div class="main">
        <header class="topbar">
            <div class="topbar-left">
                <button class="icon-btn nav-toggle" type="button" data-nav-toggle aria-label="Buka menu navigasi">
                    <x-icon name="menu" :size="19" />
                </button>
                <div>
                    <div class="crumb">
                        <a href="{{ route($u->homeRoute()) }}">{{ $u->roleLabel() }}</a>
                        <x-icon name="chevron-right" :size="11" />
                        <span>@yield('title', 'Dashboard')</span>
                    </div>
                    <div class="topbar-title">@yield('title', 'Dashboard')</div>
                </div>
            </div>
            <div class="topbar-right">
                <span class="topbar-date"><x-icon name="calendar" :size="15" />{{ now()->translatedFormat('d M Y') }}</span>
                <div class="user-chip">
                    <span class="avatar">{{ $u->initials() }}</span>
                    <div class="user-meta">
                        <b>{{ $u->name }}</b>
                        <span>{{ $u->roleLabel() }}</span>
                    </div>
                </div>
            </div>
        </header>

        <main class="page" id="main">
            @if (session('ok'))
                <div class="alert alert-ok" role="status">{{ session('ok') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-err" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
</body>
</html>

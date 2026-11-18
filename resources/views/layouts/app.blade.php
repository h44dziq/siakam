<!DOCTYPE html>
<html lang="id">
<head>
@include('layouts.head')
</head>
<body>
@php
    $u = auth()->user();
    $nav = match (true) {
        $u->role === 'mahasiswa' => [
            ['mahasiswa.dashboard', 'Riwayat laporan', ['mahasiswa.dashboard', 'laporan.show']],
            ['mahasiswa.laporan.create', 'Buat laporan', ['mahasiswa.laporan.*']],
            ['profil.edit', 'Profil', ['profil.*']],
        ],
        $u->role === 'ob' => [
            ['ob.dashboard', 'Laporan masuk', ['ob.dashboard', 'laporan.show']],
            ['profil.edit', 'Profil', ['profil.*']],
        ],
        default => [
            ['admin.dashboard', 'Ringkasan', ['admin.dashboard']],
            ['admin.laporan.index', 'Laporan', ['admin.laporan.*', 'laporan.show']],
            ['admin.master', 'Data master', ['admin.master']],
            ['admin.users', 'Pengguna', ['admin.users*']],
            ['profil.edit', 'Profil', ['profil.*']],
        ],
    };
@endphp
<div class="shell">
    <aside class="side">
        <a class="brand" href="{{ route($u->homeRoute()) }}"><span class="mark"></span>siakam</a>
        <nav class="nav" aria-label="Menu utama">
            @foreach ($nav as [$route, $label, $patterns])
                <a href="{{ route($route) }}" class="{{ request()->routeIs(...$patterns) ? 'on' : '' }}">{{ $label }}</a>
            @endforeach
        </nav>
        <div class="me">
            <span class="avatar">{{ $u->initials() }}</span>
            <div class="who"><b>{{ $u->name }}</b><span>{{ $u->roleLabel() }}</span></div>
            <form method="POST" action="{{ route('logout') }}">@csrf<button class="linkbtn">Keluar</button></form>
        </div>
    </aside>
    <main class="main">
        @if (session('ok'))<div class="flash ok" role="status">{{ session('ok') }}</div>@endif
        @if ($errors->any())
            <div class="flash err" role="alert">
                <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif
        @yield('content')
    </main>
</div>
</body>
</html>

@extends('layouts.app')
@section('title', 'Analytics')

@section('content')
<div class="page-head">
    <div>
        <h2 class="page-title">Analytics fasilitas</h2>
        <p class="page-sub">Analisis data laporan tahun {{ $year }} untuk melihat pola dan area yang perlu perhatian.</p>
    </div>
    <form method="GET">
        <div class="field">
            <label for="year">Tahun</label>
            <select id="year" name="year" data-auto>
                @foreach ($years as $option)
                    <option value="{{ $option }}" @selected($year === $option)>{{ $option }}</option>
                @endforeach
            </select>
        </div>
    </form>
</div>

<div class="stat-grid">
    <x-stat-card label="Total Laporan" :value="$stats['total']" icon="file" tone="brand" :hint="'Sepanjang tahun '.$year" />
    <x-stat-card label="Tingkat Penyelesaian" :value="$completion.'%'" icon="target" tone="success" :hint="$stats['resolved'].' laporan selesai'" />
    <x-stat-card label="Rata-rata Penanganan" :value="$avgHours === null ? '—' : $avgHours.' jam'" icon="clock" tone="info" hint="Dari laporan selesai" />
    <x-stat-card label="Belum Selesai" :value="$stats['reported'] + $stats['in_progress']" icon="alert" tone="warn" hint="Dilaporkan + diproses" />
</div>

<div class="card section">
    <div class="card-head">
        <div>
            <h3 class="card-title">Tren laporan masuk dan selesai</h3>
            <p class="card-sub">Perbandingan volume laporan dan penyelesaian per bulan.</p>
        </div>
        <span class="count-pill">{{ $year }}</span>
    </div>
    <div class="card-body">
        <x-chart-bars :labels="$trend['labels']" :series="[
            ['name' => 'Laporan masuk', 'color' => '#0d9488', 'data' => $trend['incoming']],
            ['name' => 'Laporan selesai', 'color' => '#2563eb', 'data' => $trend['resolved']],
        ]" :height="260" />
    </div>
</div>

<div class="split section">
    <div class="card">
        <div class="card-head"><div><h3 class="card-title">Distribusi status</h3><p class="card-sub">Kondisi penanganan tahun {{ $year }}.</p></div></div>
        <div class="card-body">
            <x-chart-donut :data="[
                ['label' => 'Dilaporkan', 'value' => $stats['reported'], 'color' => '#f59e0b'],
                ['label' => 'Diproses', 'value' => $stats['in_progress'], 'color' => '#3b82f6'],
                ['label' => 'Selesai', 'value' => $stats['resolved'], 'color' => '#16a34a'],
            ]" :center="$stats['total']" label="Laporan" />
        </div>
    </div>

    <div class="card">
        <div class="card-head"><div><h3 class="card-title">Ruangan paling sering dilaporkan</h3><p class="card-sub">Sepuluh ruangan teratas.</p></div></div>
        <div class="card-body">
            <x-rank-list :items="$topRooms" color="#0d9488" />
        </div>
    </div>
</div>

<div class="grid grid-2 section">
    <div class="card">
        <div class="card-head"><div><h3 class="card-title">Kategori kerusakan</h3><p class="card-sub">Jenis masalah terbanyak tahun {{ $year }}.</p></div></div>
        <div class="card-body"><x-rank-list :items="$byCategory" color="#d97706" /></div>
    </div>
    <div class="card">
        <div class="card-head"><div><h3 class="card-title">Laporan per gedung</h3><p class="card-sub">Sebaran laporan antargedung.</p></div></div>
        <div class="card-body"><x-rank-list :items="$byBuilding" color="#2563eb" /></div>
    </div>
</div>
@endsection

@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
@php
    $monthLabel = $month ? \App\Support\ReportStats::MONTHS[$month].' '.$year : 'Sepanjang tahun '.$year;
@endphp

<div class="page-head">
    <div>
        <h2 class="page-title">Ringkasan operasional</h2>
        <p class="page-sub">Kondisi laporan fasilitas pada {{ $monthLabel }}. Fokus pada laporan yang perlu ditangani.</p>
    </div>
    <div class="page-actions">
        <a class="btn btn-primary" href="{{ route('admin.laporan.index') }}">
            <x-icon name="inbox" :size="16" /> Kelola laporan
        </a>
        <a class="btn" href="{{ route('admin.laporan.export', array_filter(['month' => $month, 'year' => $year])) }}">
            <x-icon name="download" :size="16" /> Unduh CSV
        </a>
    </div>
</div>

<form class="filters" method="GET">
    <div class="filter-grid">
        <div class="field">
            <label for="month">Bulan</label>
            <select id="month" name="month" data-auto>
                <option value="">Semua bulan</option>
                @foreach (\App\Support\ReportStats::MONTHS as $number => $name)
                    <option value="{{ $number }}" @selected($month === $number)>{{ $name }}</option>
                @endforeach
            </select>
        </div>
        <div class="field">
            <label for="year">Tahun</label>
            <select id="year" name="year" data-auto>
                @foreach ($years as $option)
                    <option value="{{ $option }}" @selected($year === $option)>{{ $option }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-actions">
            <noscript><button class="btn">Terapkan</button></noscript>
        </div>
    </div>
</form>

<div class="stat-grid">
    <x-stat-card label="Total Laporan" :value="$stats['total']" icon="file" tone="brand" hint="Semua laporan periode ini" />
    <x-stat-card label="Dilaporkan" :value="$stats['reported']" icon="inbox" tone="warn" hint="Belum ditangani" />
    <x-stat-card label="Diproses" :value="$stats['in_progress']" icon="activity" tone="info" hint="Sedang ditangani" />
    <x-stat-card label="Selesai" :value="$stats['resolved']" icon="check" tone="success" hint="Sudah tuntas" />
</div>

<div class="split section">
    <div class="card">
        <div class="card-head">
            <div>
                <h3 class="card-title">Tren laporan {{ $year }}</h3>
                <p class="card-sub">Laporan masuk dibandingkan laporan yang diselesaikan.</p>
            </div>
        </div>
        <div class="card-body">
            <x-chart-area :labels="$trend['labels']" :series="[
                ['name' => 'Laporan masuk', 'color' => '#0d9488', 'data' => $trend['incoming']],
                ['name' => 'Laporan selesai', 'color' => '#2563eb', 'data' => $trend['resolved']],
            ]" />
        </div>
    </div>

    <div class="card">
        <div class="card-head">
            <div>
                <h3 class="card-title">Distribusi status</h3>
                <p class="card-sub">Komposisi laporan periode ini.</p>
            </div>
        </div>
        <div class="card-body">
            <x-chart-donut :data="[
                ['label' => 'Dilaporkan', 'value' => $stats['reported'], 'color' => '#f59e0b'],
                ['label' => 'Diproses', 'value' => $stats['in_progress'], 'color' => '#3b82f6'],
                ['label' => 'Selesai', 'value' => $stats['resolved'], 'color' => '#16a34a'],
            ]" :center="$stats['total']" label="Laporan" />
        </div>
    </div>
</div>

<div class="grid grid-3 section">
    <div class="card">
        <div class="card-head"><div><h3 class="card-title">Laporan per kategori</h3><p class="card-sub">Jenis masalah terbanyak.</p></div></div>
        <div class="card-body">
            <x-rank-list :items="$byCategory" color="#0d9488" />
        </div>
    </div>
    <div class="card">
        <div class="card-head"><div><h3 class="card-title">Laporan per gedung</h3><p class="card-sub">Sebaran lokasi laporan.</p></div></div>
        <div class="card-body">
            <x-rank-list :items="$byBuilding" color="#2563eb" />
        </div>
    </div>
    <div class="card">
        <div class="card-head"><div><h3 class="card-title">Ruangan paling sering dilaporkan</h3><p class="card-sub">Tiga ruangan teratas.</p></div></div>
        <div class="card-body">
            <x-rank-list :items="$topRooms" color="#d97706" />
        </div>
    </div>
</div>

<div class="split section">
    <div class="card">
        <div class="card-head">
            <div>
                <h3 class="card-title">Laporan terbaru</h3>
                <p class="card-sub">Enam laporan terakhir pada periode ini.</p>
            </div>
            <a class="link" href="{{ route('admin.laporan.index') }}">Lihat semua <x-icon name="chevron-right" :size="14" /></a>
        </div>
        <div class="card-body">
            <x-report-table :reports="$recent" empty-title="Belum ada laporan" empty-text="Laporan mahasiswa akan muncul di sini." />
        </div>
    </div>

    <div class="card">
        <div class="card-head"><div><h3 class="card-title">Aktivitas penanganan</h3><p class="card-sub">Perubahan status terakhir.</p></div></div>
        <div class="card-body">
            @if ($activity->isEmpty())
                <x-empty-state title="Belum ada aktivitas" text="Riwayat penanganan akan tampil di sini." icon="activity" />
            @else
                <div class="timeline">
                    @foreach ($activity as $item)
                        @php
                            $tone = match ($item->to_status) {
                                'resolved' => 'is-success',
                                'in_progress' => 'is-info',
                                default => 'is-warn',
                            };
                        @endphp
                        <div class="timeline-item {{ $tone }}">
                            <span class="timeline-dot"></span>
                            <div class="timeline-body">
                                <b>{{ \App\Models\Report::LABELS[$item->to_status] ?? $item->to_status }} · {{ $item->report->category->name }}</b>
                                <div class="timeline-meta">
                                    {{ $item->author->name }} · {{ $item->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

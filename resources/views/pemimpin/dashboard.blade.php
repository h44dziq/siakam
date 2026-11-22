@extends('layouts.app')
@section('title', 'Executive Dashboard')

@section('content')
@php
    $periodLabel = now()->setDate($year, $month, 1)->locale('id')->translatedFormat('F Y');
@endphp

<div class="page-head">
    <div>
        <h2 class="page-title">Executive overview</h2>
        <p class="page-sub">Pengawasan menyeluruh kinerja penanganan fasilitas kampus.</p>
    </div>
    <div class="page-actions">
        <a class="btn" href="{{ route('pemimpin.analytics') }}"><x-icon name="chart" :size="16" /> Analytics</a>
        <a class="btn btn-primary" href="{{ route('pemimpin.rekapitulasi') }}"><x-icon name="file" :size="16" /> Rekapitulasi</a>
    </div>
</div>

<section class="exec-hero">
    <div class="exec-hero-inner">
        <div>
            <span class="exec-kicker"><x-icon name="shield" :size="13" /> Executive Dashboard</span>
            <h2 class="exec-title">Selamat datang, {{ auth()->user()->name }}</h2>
            <p class="exec-sub">Ringkasan kondisi SIAKAM periode {{ $periodLabel }}, dibandingkan {{ $compare['label'] }}.</p>
        </div>
        <div class="exec-meta">
            <form class="exec-period" method="GET">
                <span>Periode</span>
                <select name="month" data-auto aria-label="Bulan">
                    @foreach (\App\Support\ReportStats::MONTHS as $number => $name)
                        <option value="{{ $number }}" @selected($month === $number)>{{ $name }}</option>
                    @endforeach
                </select>
                <select name="year" data-auto aria-label="Tahun">
                    @foreach ($years as $option)
                        <option value="{{ $option }}" @selected($year === $option)>{{ $option }}</option>
                    @endforeach
                </select>
            </form>
            <span class="exec-badge"><i></i> Data aktual dari database</span>
        </div>
    </div>

    <div class="exec-kpis">
        <div class="exec-kpi">
            <span>Total laporan</span>
            <b>{{ $stats['total'] }}</b>
            <small>{{ $compare['total'] >= 0 ? '+' : '' }}{{ $compare['total'] }}% vs {{ $compare['prevTotal'] }} bulan lalu</small>
        </div>
        <div class="exec-kpi">
            <span>Belum ditangani</span>
            <b>{{ $stats['reported'] }}</b>
            <small>{{ $compare['reported'] >= 0 ? '+' : '' }}{{ $compare['reported'] }}% vs {{ $compare['prevReported'] }} bulan lalu</small>
        </div>
        <div class="exec-kpi">
            <span>Sedang diproses</span>
            <b>{{ $stats['in_progress'] }}</b>
            <small>Dalam penanganan admin</small>
        </div>
        <div class="exec-kpi">
            <span>Selesai</span>
            <b>{{ $stats['resolved'] }}</b>
            <small>{{ $completion }}% tingkat penyelesaian</small>
        </div>
    </div>
</section>

<div class="split">
    <div class="card">
        <div class="card-head">
            <div>
                <h3 class="card-title">Tren laporan {{ $year }}</h3>
                <p class="card-sub">Laporan masuk dan laporan selesai per bulan.</p>
            </div>
            <span class="count-pill">12 bulan</span>
        </div>
        <div class="card-body">
            <x-chart-area :labels="$trend['labels']" :series="[
                ['name' => 'Laporan masuk', 'color' => '#14b8a6', 'data' => $trend['incoming']],
                ['name' => 'Laporan selesai', 'color' => '#d4a72c', 'data' => $trend['resolved']],
            ]" :height="250" />
        </div>
    </div>

    <div class="stack">
        <div class="card">
            <div class="card-head"><div><h3 class="card-title">Tingkat penyelesaian</h3><p class="card-sub">Periode {{ $periodLabel }}.</p></div></div>
            <div class="card-body">
                <div class="meter-label"><span>{{ $stats['resolved'] }} dari {{ $stats['total'] }} laporan</span><b>{{ $completion }}%</b></div>
                <div class="progress gold"><i style="width: {{ $completion }}%"></i></div>
                <div class="divider"></div>
                <div class="facts" style="grid-template-columns: repeat(2, minmax(0, 1fr))">
                    <div><dt>Rata-rata penanganan</dt><dd>{{ $avgHours === null ? 'Belum ada data' : $avgHours.' jam' }}</dd></div>
                    <div><dt>Belum selesai</dt><dd>{{ $stats['reported'] + $stats['in_progress'] }} laporan</dd></div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-head"><div><h3 class="card-title">Distribusi status</h3><p class="card-sub">Komposisi laporan periode ini.</p></div></div>
            <div class="card-body">
                <x-chart-donut :data="[
                    ['label' => 'Dilaporkan', 'value' => $stats['reported'], 'color' => '#f59e0b'],
                    ['label' => 'Diproses', 'value' => $stats['in_progress'], 'color' => '#3b82f6'],
                    ['label' => 'Selesai', 'value' => $stats['resolved'], 'color' => '#16a34a'],
                ]" :center="$stats['total']" label="Laporan" />
            </div>
        </div>
    </div>
</div>

<div class="grid grid-3 section">
    <div class="card">
        <div class="card-head"><div><h3 class="card-title">Kategori kerusakan</h3><p class="card-sub">Jenis masalah terbanyak.</p></div></div>
        <div class="card-body"><x-rank-list :items="$byCategory" color="#14b8a6" /></div>
    </div>
    <div class="card">
        <div class="card-head"><div><h3 class="card-title">Laporan per gedung</h3><p class="card-sub">Sebaran lokasi laporan.</p></div></div>
        <div class="card-body"><x-rank-list :items="$byBuilding" color="#2563eb" /></div>
    </div>
    <div class="card">
        <div class="card-head"><div><h3 class="card-title">Ruangan terbanyak</h3><p class="card-sub">Lima ruangan teratas.</p></div></div>
        <div class="card-body"><x-rank-list :items="$topRooms" color="#d4a72c" /></div>
    </div>
</div>

<div class="split section">
    <div class="card">
        <div class="card-head">
            <div>
                <h3 class="card-title">Perlu perhatian</h3>
                <p class="card-sub">Laporan terlama yang belum selesai.</p>
            </div>
            <a class="link" href="{{ route('pemimpin.monitoring') }}">Monitoring <x-icon name="chevron-right" :size="14" /></a>
        </div>
        <div class="card-body">
            <x-report-table :reports="$attention" :show-reporter="false" :show-age="true"
                empty-title="Semua laporan tertangani"
                empty-text="Tidak ada laporan yang menunggu tindak lanjut pada periode ini." />
        </div>
    </div>

    <div class="card">
        <div class="card-head"><div><h3 class="card-title">Aktivitas terbaru</h3><p class="card-sub">Perubahan status terakhir.</p></div></div>
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
                                <div class="timeline-meta">{{ $item->author->name }} · {{ $item->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

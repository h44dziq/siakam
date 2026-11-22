@extends('layouts.app')
@section('title', 'Monitoring Laporan')

@section('content')
<div class="page-head">
    <div>
        <h2 class="page-title">Monitoring laporan</h2>
        <p class="page-sub">Pantau seluruh laporan dan usia penanganannya. Mode pengawasan — tanpa perubahan status.</p>
    </div>
    <div class="page-actions">
        <a class="btn" href="{{ route('pemimpin.analytics') }}"><x-icon name="chart" :size="16" /> Analytics</a>
    </div>
</div>

<div class="stat-grid">
    <x-stat-card label="Total Laporan" :value="$stats['total']" icon="file" tone="brand" hint="Sesuai filter" />
    <x-stat-card label="Belum Ditangani" :value="$stats['reported']" icon="inbox" tone="warn" hint="Menunggu tindakan admin" />
    <x-stat-card label="Sedang Diproses" :value="$stats['in_progress']" icon="activity" tone="info" hint="Dalam penanganan" />
    <x-stat-card label="Selesai" :value="$stats['resolved']" icon="check" tone="success" hint="Sudah tuntas" />
</div>

<form class="filters section" method="GET">
    <div class="filter-grid">
        <div class="field grow">
            <label for="q">Cari pelapor</label>
            <input id="q" type="text" name="q" value="{{ $f['q'] ?? '' }}" placeholder="Nama atau NIM">
        </div>
        <div class="field">
            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="">Semua status</option>
                @foreach (\App\Models\Report::LABELS as $key => $label)
                    <option value="{{ $key }}" @selected(($f['status'] ?? '') === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="field">
            <label for="building_id">Gedung</label>
            <select id="building_id" name="building_id">
                <option value="">Semua gedung</option>
                @foreach ($buildings as $building)
                    <option value="{{ $building->id }}" @selected(($f['building_id'] ?? '') == $building->id)>{{ $building->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="field">
            <label for="category_id">Kategori</label>
            <select id="category_id" name="category_id">
                <option value="">Semua kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(($f['category_id'] ?? '') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-actions">
            <button class="btn btn-primary">Terapkan</button>
            @if (array_filter($f))<a class="btn btn-ghost" href="{{ route('pemimpin.monitoring') }}">Reset</a>@endif
        </div>
    </div>
</form>

<div class="split">
    <div class="card">
        <div class="card-head">
            <div>
                <h3 class="card-title">Daftar laporan</h3>
                <p class="card-sub">{{ $reports->total() }} laporan sesuai filter.</p>
            </div>
        </div>
        <div class="card-body">
            <x-report-table :reports="$reports" :show-age="true"
                empty-title="Tidak ada laporan" empty-text="Coba ubah filter status atau lokasi." />
        </div>
    </div>

    <div class="card">
        <div class="card-head"><div><h3 class="card-title">Perlu perhatian</h3><p class="card-sub">Laporan terlama yang belum selesai.</p></div></div>
        <div class="card-body">
            @if ($attention->isEmpty())
                <x-empty-state title="Semua tertangani" text="Tidak ada laporan yang menunggu tindak lanjut." icon="check" />
            @else
                <div class="timeline">
                    @foreach ($attention as $report)
                        <div class="timeline-item is-warn">
                            <span class="timeline-dot"></span>
                            <div class="timeline-body">
                                <b>{{ $report->category->name }}</b>
                                <div class="timeline-meta">
                                    {{ $report->room->name }} · {{ $report->room->building->name }} · {{ $report->daysOpen() }} hari
                                </div>
                                <a class="link" href="{{ route('laporan.show', $report) }}">Lihat detail <x-icon name="chevron-right" :size="13" /></a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')
@section('title', 'Rekapitulasi Laporan')

@section('content')
<div class="page-head">
    <div>
        <h2 class="page-title">Rekapitulasi laporan</h2>
        <p class="page-sub">Data lengkap seluruh laporan untuk evaluasi. Filter berdasarkan periode, lokasi, kategori, dan status.</p>
    </div>
    <div class="page-actions">
        <a class="btn btn-primary" href="{{ route('pemimpin.rekapitulasi.export', array_filter($f)) }}">
            <x-icon name="download" :size="16" /> Unduh CSV
        </a>
    </div>
</div>

<form class="filters" method="GET">
    <div class="filter-grid">
        <div class="field">
            <label for="month">Bulan</label>
            <select id="month" name="month">
                <option value="">Semua bulan</option>
                @foreach (\App\Support\ReportStats::MONTHS as $number => $name)
                    <option value="{{ $number }}" @selected(($f['month'] ?? '') == $number)>{{ $name }}</option>
                @endforeach
            </select>
        </div>
        <div class="field">
            <label for="year">Tahun</label>
            <select id="year" name="year">
                <option value="">Semua tahun</option>
                @foreach (range(now()->year, now()->year - 3) as $option)
                    <option value="{{ $option }}" @selected(($f['year'] ?? '') == $option)>{{ $option }}</option>
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
            <label for="room_id">Ruangan</label>
            <select id="room_id" name="room_id">
                <option value="">Semua ruangan</option>
                @foreach ($rooms as $room)
                    <option value="{{ $room->id }}" @selected(($f['room_id'] ?? '') == $room->id)>{{ $room->name }} — {{ $room->building->name }}</option>
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
        <div class="field">
            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="">Semua status</option>
                @foreach (\App\Models\Report::LABELS as $key => $label)
                    <option value="{{ $key }}" @selected(($f['status'] ?? '') === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-actions">
            <button class="btn btn-primary">Terapkan</button>
            @if (array_filter($f))<a class="btn btn-ghost" href="{{ route('pemimpin.rekapitulasi') }}">Reset</a>@endif
        </div>
    </div>
</form>

<div class="stat-grid section">
    <x-stat-card label="Total Laporan" :value="$stats['total']" icon="file" tone="brand" hint="Sesuai filter periode" />
    <x-stat-card label="Dilaporkan" :value="$stats['reported']" icon="inbox" tone="warn" hint="Belum ditangani" />
    <x-stat-card label="Diproses" :value="$stats['in_progress']" icon="activity" tone="info" hint="Sedang ditangani" />
    <x-stat-card label="Selesai" :value="$stats['resolved']" icon="check" tone="success" hint="Sudah tuntas" />
</div>

<div class="card section">
    <div class="card-head">
        <div>
            <h3 class="card-title">Data laporan</h3>
            <p class="card-sub">{{ $reports->total() }} laporan sesuai filter.</p>
        </div>
    </div>
    <div class="card-body">
        <x-report-table :reports="$reports" :show-age="true"
            empty-title="Belum ada data" empty-text="Coba longgarkan filter periode atau lokasi." />
    </div>
</div>
@endsection

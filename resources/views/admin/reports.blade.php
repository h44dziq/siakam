@extends('layouts.app')
@section('title', 'Manajemen Laporan')

@section('content')
<div class="page-head">
    <div>
        <h2 class="page-title">Manajemen laporan</h2>
        <p class="page-sub">Tinjau seluruh laporan mahasiswa, cari berdasarkan pelapor, lalu buka detail untuk menangani.</p>
    </div>
    <div class="page-actions">
        <a class="btn" href="{{ route('admin.laporan.export', array_filter($f)) }}">
            <x-icon name="download" :size="16" /> Unduh CSV
        </a>
    </div>
</div>

<form class="filters" method="GET">
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
        <div class="field">
            <label for="from">Dari tanggal</label>
            <input id="from" type="date" name="from" value="{{ $f['from'] ?? '' }}">
        </div>
        <div class="field">
            <label for="to">Sampai tanggal</label>
            <input id="to" type="date" name="to" value="{{ $f['to'] ?? '' }}">
        </div>
        <div class="filter-actions">
            <button class="btn btn-primary">Terapkan</button>
            @if (array_filter($f))
                <a class="btn btn-ghost" href="{{ route('admin.laporan.index') }}">Reset</a>
            @endif
        </div>
    </div>
</form>

<div class="card">
    <div class="card-head">
        <div>
            <h3 class="card-title">Daftar laporan</h3>
            <p class="card-sub">{{ $reports->total() }} laporan ditemukan.</p>
        </div>
    </div>
    <div class="card-body">
        <x-report-table :reports="$reports" :show-age="true"
            empty-title="Tidak ada laporan yang cocok"
            empty-text="Ubah kata kunci, status, atau rentang tanggalnya." />
    </div>
</div>
@endsection

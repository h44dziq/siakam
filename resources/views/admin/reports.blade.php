@extends('layouts.app')
@section('title', 'Laporan')
@section('content')
<div class="head">
    <div>
        <h1>Semua laporan</h1>
        <p>Pantau seluruh laporan dari mahasiswa, lalu buka detailnya untuk meninjau atau mengubah status.</p>
    </div>
    <a class="btn" href="{{ route('admin.laporan.export', request()->only(['q', 'status', 'from', 'to'])) }}">Unduh CSV</a>
</div>
<form class="filters" method="GET">
    <div class="field grow">
        <label for="q">Cari pelapor</label>
        <input id="q" type="text" name="q" value="{{ request('q') }}" placeholder="Nama atau NIM">
    </div>
    <div class="field">
        <label for="status">Status</label>
        <select id="status" name="status">
            <option value="">Semua status</option>
            @foreach (\App\Models\Report::LABELS as $k => $l)<option value="{{ $k }}" @selected(request('status') === $k)>{{ $l }}</option>@endforeach
        </select>
    </div>
    <div class="field"><label for="from">Dari tanggal</label><input id="from" type="date" name="from" value="{{ request('from') }}"></div>
    <div class="field"><label for="to">Sampai tanggal</label><input id="to" type="date" name="to" value="{{ request('to') }}"></div>
    <button class="btn btn-primary">Terapkan</button>
    @if (request()->hasAny(['q', 'status', 'from', 'to']))<a class="btn" href="{{ route('admin.laporan.index') }}">Reset</a>@endif
</form>
<div class="panel">
    @include('partials.report-table', [
        'reports' => $reports,
        'showReporter' => true,
        'emptyTitle' => 'Tidak ada laporan yang cocok',
        'emptyText' => 'Ubah kata kunci atau rentang tanggalnya.',
    ])
</div>
@endsection

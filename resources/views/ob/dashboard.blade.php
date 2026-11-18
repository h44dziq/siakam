@extends('layouts.app')
@section('title', 'Laporan masuk')
@section('content')
<div class="head">
    <div>
        <h1>Laporan masuk</h1>
        <p>Laporan yang perlu ditangani, lengkap dengan lokasi dan jenis masalahnya.</p>
    </div>
</div>
<form class="filters" method="GET">
    <div class="field">
        <label for="status">Status</label>
        <select id="status" name="status" onchange="this.form.submit()">
            <option value="aktif" @selected($status === 'aktif')>Perlu ditangani</option>
            <option value="reported" @selected($status === 'reported')>Dilaporkan</option>
            <option value="in_progress" @selected($status === 'in_progress')>Diproses</option>
            <option value="resolved" @selected($status === 'resolved')>Selesai</option>
            <option value="all" @selected($status === 'all')>Semua</option>
        </select>
    </div>
    <div class="field">
        <label for="building">Gedung</label>
        <select id="building" name="building" onchange="this.form.submit()">
            <option value="">Semua gedung</option>
            @foreach ($buildings as $b)<option value="{{ $b->id }}" @selected(request('building') == $b->id)>{{ $b->name }}</option>@endforeach
        </select>
    </div>
    <noscript><button class="btn">Terapkan</button></noscript>
</form>
<div class="panel">
    @include('partials.report-table', [
        'reports' => $reports,
        'showReporter' => false,
        'emptyTitle' => 'Tidak ada laporan di sini',
        'emptyText' => 'Coba ubah filter status atau gedung.',
    ])
</div>
@endsection

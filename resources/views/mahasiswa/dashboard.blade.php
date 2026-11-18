@extends('layouts.app')
@section('title', 'Riwayat laporan')
@section('content')
<div class="head">
    <div>
        <h1>Riwayat laporan</h1>
        <p>Semua laporan yang pernah Anda kirim dan statusnya saat ini.</p>
    </div>
    <a class="btn btn-primary" href="{{ route('mahasiswa.laporan.create') }}">Buat laporan</a>
</div>
<div class="panel">
    @include('partials.report-table', [
        'reports' => $reports,
        'emptyTitle' => 'Belum ada laporan',
        'emptyText' => 'Lihat AC menyala di ruang kosong atau fasilitas rusak? Kirim laporan pertama Anda.',
    ])
</div>
@endsection

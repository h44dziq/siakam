@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="page-head">
    <div>
        <h2 class="page-title">Halo, {{ auth()->user()->name }}</h2>
        <p class="page-sub">Pantau laporan fasilitas Anda dan lihat perkembangan penanganannya.</p>
    </div>
    <div class="page-actions">
        <a class="btn btn-primary" href="{{ route('mahasiswa.laporan.create') }}">
            <x-icon name="plus" :size="16" /> Buat Laporan
        </a>
    </div>
</div>

<div class="stat-grid">
    <x-stat-card label="Total Laporan" :value="$stats['total']" icon="file" tone="brand" hint="Semua laporan Anda" />
    <x-stat-card label="Dilaporkan" :value="$stats['reported']" icon="inbox" tone="warn" hint="Menunggu ditangani" />
    <x-stat-card label="Diproses" :value="$stats['in_progress']" icon="activity" tone="info" hint="Sedang ditangani" />
    <x-stat-card label="Selesai" :value="$stats['resolved']" icon="check" tone="success" hint="Sudah tuntas" />
</div>

<div class="split section">
    <div class="card">
        <div class="card-head">
            <div>
                <h3 class="card-title">Laporan terbaru</h3>
                <p class="card-sub">Enam laporan terakhir yang Anda kirim.</p>
            </div>
            <a class="link" href="{{ route('mahasiswa.riwayat') }}">Lihat semua <x-icon name="chevron-right" :size="14" /></a>
        </div>
        <div class="card-body">
            @if ($reports->isEmpty())
                <x-empty-state title="Belum ada laporan"
                    text="Menemukan fasilitas rusak atau pemborosan listrik? Kirim laporan pertama Anda."
                    icon="inbox">
                    <a class="btn btn-soft btn-sm" href="{{ route('mahasiswa.laporan.create') }}">Buat laporan</a>
                </x-empty-state>
            @else
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Laporan</th>
                                <th class="hide-sm">Lokasi</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reports as $report)
                                <tr>
                                    <td>
                                        <span class="cell-title">{{ $report->category->name }}</span>
                                        <span class="cell-sub">#{{ $report->id }} · {{ $report->created_at->translatedFormat('d M Y') }}</span>
                                    </td>
                                    <td class="hide-sm">
                                        <span class="cell-title">{{ $report->room->name }}</span>
                                        <span class="cell-sub">{{ $report->room->building->name }}</span>
                                    </td>
                                    <td><x-status-badge :status="$report->status" /></td>
                                    <td class="num">
                                        <a class="btn btn-sm" href="{{ route('laporan.show', $report) }}">Detail</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="stack">
        <div class="card">
            <div class="card-head"><h3 class="card-title">Alur penanganan</h3></div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item is-warn">
                        <span class="timeline-dot"></span>
                        <div class="timeline-body">
                            <b>Dilaporkan</b>
                            <div class="timeline-meta">Laporan masuk dan menunggu verifikasi admin.</div>
                        </div>
                    </div>
                    <div class="timeline-item is-info">
                        <span class="timeline-dot"></span>
                        <div class="timeline-body">
                            <b>Diproses</b>
                            <div class="timeline-meta">Admin menangani dan mencatat tindakan.</div>
                        </div>
                    </div>
                    <div class="timeline-item is-success">
                        <span class="timeline-dot"></span>
                        <div class="timeline-body">
                            <b>Selesai</b>
                            <div class="timeline-meta">Masalah tuntas dan riwayat tersimpan.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-head"><h3 class="card-title">Tips laporan cepat ditangani</h3></div>
            <div class="card-body">
                <ul class="rank">
                    <li><div class="rank-head"><b>Lokasi tepat</b></div><div class="rank-meta">Pilih gedung dan ruangan yang benar.</div></li>
                    <li><div class="rank-head"><b>Deskripsi jelas</b></div><div class="rank-meta">Sebutkan apa yang rusak dan sejak kapan.</div></li>
                    <li><div class="rank-head"><b>Foto bukti</b></div><div class="rank-meta">Membantu petugas menemukan masalah lebih cepat.</div></li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

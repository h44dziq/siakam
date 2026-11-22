@extends('layouts.app')
@section('title', 'Riwayat Laporan')

@section('content')
<div class="page-head">
    <div>
        <h2 class="page-title">Riwayat laporan</h2>
        <p class="page-sub">Semua laporan yang pernah Anda kirim beserta status penanganannya.</p>
    </div>
    <div class="page-actions">
        <a class="btn btn-primary" href="{{ route('mahasiswa.laporan.create') }}">
            <x-icon name="plus" :size="16" /> Buat Laporan
        </a>
    </div>
</div>

<form class="filters" method="GET">
    <div class="filter-grid">
        <div class="field">
            <label for="status">Status</label>
            <select id="status" name="status" data-auto>
                <option value="">Semua status</option>
                @foreach (\App\Models\Report::LABELS as $key => $label)
                    <option value="{{ $key }}" @selected($status === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-actions">
            <button class="btn">Terapkan</button>
            @if ($status)<a class="btn btn-ghost" href="{{ route('mahasiswa.riwayat') }}">Reset</a>@endif
        </div>
    </div>
</form>

<div class="card">
    <div class="card-body">
        @if ($reports->isEmpty())
            <x-empty-state title="Tidak ada laporan" text="Belum ada laporan dengan status ini." icon="list" />
        @else
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Laporan</th>
                            <th class="hide-sm">Lokasi</th>
                            <th class="hide-sm">Tanggal</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reports as $report)
                            <tr>
                                <td>
                                    <span class="cell-title">{{ $report->category->name }}</span>
                                    <span class="cell-sub">#{{ $report->id }} · {{ \Illuminate\Support\Str::limit($report->description, 48) }}</span>
                                </td>
                                <td class="hide-sm">
                                    <span class="cell-title">{{ $report->room->name }}</span>
                                    <span class="cell-sub">{{ $report->room->building->name }}</span>
                                </td>
                                <td class="hide-sm nowrap">{{ $report->created_at->translatedFormat('d M Y') }}</td>
                                <td><x-status-badge :status="$report->status" /></td>
                                <td class="num">
                                    <a class="btn btn-sm" href="{{ route('laporan.show', $report) }}">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $reports->links() }}
        @endif
    </div>
</div>
@endsection

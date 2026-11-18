@php($showReporter = $showReporter ?? false)
<div class="tbl-wrap">
@if ($reports->isEmpty())
    <div class="empty">
        <b>{{ $emptyTitle ?? 'Belum ada laporan' }}</b>
        {{ $emptyText ?? '' }}
    </div>
@else
    <table class="tbl">
        <thead>
            <tr>
                <th>ID</th>
                <th>Waktu</th>
                <th>Lokasi</th>
                <th>Masalah</th>
                @if ($showReporter)<th>Pelapor</th>@endif
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @foreach ($reports as $r)
            <tr>
                <td class="muted">#{{ $r->id }}</td>
                <td>{{ $r->created_at->translatedFormat('d M Y') }}<br><span class="muted small">{{ $r->created_at->format('H:i') }}</span></td>
                <td class="loc"><b>{{ $r->room->name }}</b><span>{{ $r->room->building->name }}</span></td>
                <td>{{ $r->category->name }}</td>
                @if ($showReporter)<td>{{ $r->user->name }}<br><span class="muted small">{{ $r->user->nim }}</span></td>@endif
                <td>@include('partials.status', ['report' => $r])</td>
                <td class="num"><a href="{{ route('laporan.show', $r) }}">Lihat detail</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif
</div>
@if ($reports->hasPages())
    <div class="pager">{{ $reports->links() }}</div>
@endif

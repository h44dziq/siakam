@props([
    'reports',
    'showAge' => false,
    'showReporter' => true,
    'emptyTitle' => 'Belum ada laporan',
    'emptyText' => null,
])

<div class="table-wrap">
    @if ($reports->isEmpty())
        <x-empty-state :title="$emptyTitle" :text="$emptyText" icon="inbox" />
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Laporan</th>
                    @if ($showReporter)<th class="hide-sm">Pelapor</th>@endif
                    <th>Lokasi</th>
                    <th class="hide-sm">Tanggal</th>
                    @if ($showAge)<th>Usia</th>@endif
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reports as $report)
                    <tr>
                        <td>
                            <span class="cell-title">{{ $report->category->name }}</span>
                            <span class="cell-sub"><span class="id-pill">#{{ $report->id }}</span></span>
                        </td>
                        @if ($showReporter)
                            <td class="hide-sm">
                                <span class="cell-title">{{ $report->user->name }}</span>
                                <span class="cell-sub">{{ $report->user->nim ?: $report->user->email }}</span>
                            </td>
                        @endif
                        <td>
                            <span class="cell-title">{{ $report->room->name }}</span>
                            <span class="cell-sub">{{ $report->room->building->name }}</span>
                        </td>
                        <td class="hide-sm nowrap">{{ $report->created_at->translatedFormat('d M Y') }}</td>
                        @if ($showAge)
                            <td class="nowrap">{{ $report->daysOpen() }} hari</td>
                        @endif
                        <td><x-status-badge :status="$report->status" /></td>
                        <td class="num">
                            <a class="btn btn-sm" href="{{ route('laporan.show', $report) }}">Detail</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@if (! $reports->isEmpty() && method_exists($reports, 'links'))
    {{ $reports->links() }}
@endif

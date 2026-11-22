<?php

namespace App\Support;

use App\Models\Report;
use App\Models\ReportStatusHistory;
use Illuminate\Database\Eloquent\Builder;

/**
 * Perhitungan statistik laporan yang dipakai dashboard Admin dan Pemimpin.
 * Semua angka berasal dari database; pengelompokan tanggal dilakukan di PHP
 * agar tetap portabel antara SQLite dan MySQL.
 */
class ReportStats
{
    public const MONTHS = [
        1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
        'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des',
    ];

    public static function period(?int $month, ?int $year): Builder
    {
        return Report::query()
            ->when($month, fn (Builder $q, $m) => $q->whereMonth('reports.created_at', $m))
            ->when($year, fn (Builder $q, $y) => $q->whereYear('reports.created_at', $y));
    }

    /** @return array{reported:int,in_progress:int,resolved:int,total:int} */
    public static function statuses(Builder $query): array
    {
        $rows = (clone $query)->selectRaw('status, count(*) as total')
            ->groupBy('status')->pluck('total', 'status')->all();

        $out = [
            'reported' => (int) ($rows['reported'] ?? 0),
            'in_progress' => (int) ($rows['in_progress'] ?? 0),
            'resolved' => (int) ($rows['resolved'] ?? 0),
        ];
        $out['total'] = array_sum($out);

        return $out;
    }

    /** @return array{labels:array<int,string>,incoming:array<int,int>,resolved:array<int,int>} */
    public static function trend(int $year): array
    {
        $incoming = array_fill(1, 12, 0);
        $resolved = array_fill(1, 12, 0);

        foreach (Report::whereYear('created_at', $year)->pluck('created_at') as $date) {
            $incoming[$date->month]++;
        }
        foreach (ReportStatusHistory::where('to_status', 'resolved')
            ->whereYear('created_at', $year)->pluck('created_at') as $date) {
            $resolved[$date->month]++;
        }

        return [
            'labels' => array_values(self::MONTHS),
            'incoming' => array_values($incoming),
            'resolved' => array_values($resolved),
        ];
    }

    /** @return array<int,array{label:string,value:int}> */
    public static function byCategory(Builder $query, int $limit = 6): array
    {
        return (clone $query)->selectRaw('category_id, count(*) as total')
            ->groupBy('category_id')->orderByDesc('total')->limit($limit)
            ->with('category')->get()
            ->map(fn (Report $r) => ['label' => $r->category?->name ?? '-', 'value' => (int) $r->total])
            ->all();
    }

    /** @return array<int,array{label:string,value:int}> */
    public static function byBuilding(Builder $query, int $limit = 6): array
    {
        return (clone $query)
            ->join('rooms', 'reports.room_id', '=', 'rooms.id')
            ->join('buildings', 'rooms.building_id', '=', 'buildings.id')
            ->selectRaw('buildings.name as label, count(*) as total')
            ->groupBy('buildings.id', 'buildings.name')
            ->orderByDesc('total')->limit($limit)->get()
            ->map(fn ($r) => ['label' => $r->label, 'value' => (int) $r->total])
            ->all();
    }

    /** @return array<int,array{label:string,meta:string,value:int}> */
    public static function topRooms(Builder $query, int $limit = 5): array
    {
        return (clone $query)->selectRaw('room_id, count(*) as total')
            ->groupBy('room_id')->orderByDesc('total')->limit($limit)
            ->with('room.building')->get()
            ->map(fn (Report $r) => [
                'label' => $r->room?->name ?? '-',
                'meta' => $r->room?->building?->name ?? '-',
                'value' => (int) $r->total,
            ])->all();
    }

    /** Rata-rata jam sampai status selesai, dihitung dari riwayat status. */
    public static function avgResolutionHours(Builder $query): ?float
    {
        $reports = (clone $query)
            ->with(['histories' => fn ($q) => $q->where('to_status', 'resolved')->orderBy('id')])
            ->get(['id', 'created_at']);

        $minutes = [];
        foreach ($reports as $report) {
            $done = $report->histories->first()?->created_at;
            if ($done) {
                $minutes[] = $report->created_at->diffInMinutes($done);
            }
        }

        return $minutes === [] ? null : round(array_sum($minutes) / count($minutes) / 60, 1);
    }

    /** @return array<int,Report> */
    public static function attention(Builder $query, int $limit = 5)
    {
        return (clone $query)->whereIn('status', ['reported', 'in_progress'])
            ->with(['room.building', 'category', 'user'])
            ->oldest()->limit($limit)->get();
    }
}

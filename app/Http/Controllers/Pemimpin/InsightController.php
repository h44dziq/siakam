<?php

namespace App\Http\Controllers\Pemimpin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Support\ReportStats;
use Illuminate\Http\Request;

class InsightController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->integer('year') ?: now()->year;
        $base = ReportStats::period(null, $year);
        $stats = ReportStats::statuses($base);
        $total = $stats['total'];

        $building = ReportStats::byBuilding($base, 1)[0] ?? null;
        $category = ReportStats::byCategory($base, 1)[0] ?? null;
        $room = ReportStats::topRooms($base, 1)[0] ?? null;
        $completion = $total ? (int) round($stats['resolved'] / $total * 100) : 0;
        $avgHours = ReportStats::avgResolutionHours($base);

        $month = now()->month;
        $prevMonth = $month === 1 ? 12 : $month - 1;
        $prevYear = $month === 1 ? $year - 1 : $year;
        $currentCount = Report::whereYear('created_at', $year)->whereMonth('created_at', $month)->count();
        $previousCount = Report::whereYear('created_at', $prevYear)->whereMonth('created_at', $prevMonth)->count();
        $growth = $previousCount
            ? (int) round(($currentCount - $previousCount) / $previousCount * 100)
            : ($currentCount ? 100 : 0);

        $unresolved = $stats['reported'] + $stats['in_progress'];
        $oldest = Report::whereIn('status', ['reported', 'in_progress'])->oldest()->first();
        $share = fn (?array $row) => $row && $total ? (int) round($row['value'] / $total * 100) : 0;

        $insights = [
            [
                'icon' => 'building', 'tone' => 'brand',
                'title' => 'Gedung paling sering dilaporkan',
                'value' => $building['label'] ?? 'Belum ada data',
                'text' => $building ? "{$building['value']} laporan ({$share($building)}% dari total tahun {$year})." : 'Belum ada laporan pada tahun ini.',
            ],
            [
                'icon' => 'tag', 'tone' => 'warn',
                'title' => 'Kategori dominan',
                'value' => $category['label'] ?? 'Belum ada data',
                'text' => $category ? "{$category['value']} laporan ({$share($category)}% dari total tahun {$year})." : 'Belum ada laporan pada tahun ini.',
            ],
            [
                'icon' => 'door', 'tone' => 'info',
                'title' => 'Ruangan paling bermasalah',
                'value' => $room['label'] ?? 'Belum ada data',
                'text' => $room ? "{$room['value']} laporan, terbanyak di {$room['meta']}." : 'Belum ada laporan pada tahun ini.',
            ],
            [
                'icon' => $growth >= 0 ? 'trend-up' : 'trend-down', 'tone' => $growth > 0 ? 'danger' : 'success',
                'title' => 'Tren laporan bulan ini',
                'value' => ($growth > 0 ? '+' : '').$growth.'%',
                'text' => "{$currentCount} laporan bulan ini dibanding {$previousCount} bulan lalu.",
            ],
            [
                'icon' => 'check', 'tone' => $completion >= 70 ? 'success' : 'warn',
                'title' => 'Tingkat penyelesaian',
                'value' => $completion.'%',
                'text' => $stats['resolved'].' dari '.$total.' laporan tahun '.$year.' sudah selesai.',
            ],
            [
                'icon' => 'clock', 'tone' => 'brand',
                'title' => 'Rata-rata waktu penanganan',
                'value' => $avgHours === null ? 'Belum ada data' : $avgHours.' jam',
                'text' => 'Dihitung dari laporan yang sudah berstatus selesai.',
            ],
            [
                'icon' => 'alert', 'tone' => $unresolved > 0 ? 'danger' : 'success',
                'title' => 'Laporan belum selesai',
                'value' => (string) $unresolved,
                'text' => $oldest
                    ? 'Laporan tertua berumur '.$oldest->daysOpen().' hari dan masih menunggu tindak lanjut.'
                    : 'Semua laporan sudah ditangani.',
            ],
        ];

        return view('pemimpin.insight', [
            'insights' => $insights,
            'attention' => ReportStats::attention($base, 6),
            'year' => $year,
            'years' => range(now()->year, now()->year - 3),
        ]);
    }
}

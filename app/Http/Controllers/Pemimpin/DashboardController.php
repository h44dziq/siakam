<?php

namespace App\Http\Controllers\Pemimpin;

use App\Http\Controllers\Controller;
use App\Models\ReportStatusHistory;
use App\Support\ReportStats;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->integer('month') ?: now()->month;
        $year = $request->integer('year') ?: now()->year;

        $base = ReportStats::period($month, $year);
        $stats = ReportStats::statuses($base);

        $prevDate = now()->setDate($year, $month, 1)->subMonth();
        $prev = ReportStats::statuses(ReportStats::period($prevDate->month, $prevDate->year));

        $delta = fn (int $now, int $before) => $before === 0
            ? ($now > 0 ? 100 : 0)
            : (int) round(($now - $before) / $before * 100);

        return view('pemimpin.dashboard', [
            'stats' => $stats,
            'compare' => [
                'total' => $delta($stats['total'], $prev['total']),
                'reported' => $delta($stats['reported'], $prev['reported']),
                'resolved' => $delta($stats['resolved'], $prev['resolved']),
                'prevTotal' => $prev['total'],
                'prevReported' => $prev['reported'],
                'prevResolved' => $prev['resolved'],
                'label' => $prevDate->translatedFormat('F Y'),
            ],
            'completion' => $stats['total'] ? (int) round($stats['resolved'] / $stats['total'] * 100) : 0,
            'avgHours' => ReportStats::avgResolutionHours($base),
            'trend' => ReportStats::trend($year),
            'byCategory' => ReportStats::byCategory($base),
            'byBuilding' => ReportStats::byBuilding($base),
            'topRooms' => ReportStats::topRooms($base, 5),
            'attention' => ReportStats::attention($base, 5),
            'activity' => ReportStatusHistory::with(['author', 'report.category'])->latest()->limit(6)->get(),
            'month' => $month,
            'year' => $year,
            'years' => range(now()->year, now()->year - 3),
        ]);
    }
}

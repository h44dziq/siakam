<?php

namespace App\Http\Controllers\Pemimpin;

use App\Http\Controllers\Controller;
use App\Support\ReportStats;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->integer('year') ?: now()->year;
        $base = ReportStats::period(null, $year);
        $stats = ReportStats::statuses($base);

        return view('pemimpin.analytics', [
            'stats' => $stats,
            'completion' => $stats['total'] ? (int) round($stats['resolved'] / $stats['total'] * 100) : 0,
            'avgHours' => ReportStats::avgResolutionHours($base),
            'trend' => ReportStats::trend($year),
            'byCategory' => ReportStats::byCategory($base, 8),
            'byBuilding' => ReportStats::byBuilding($base, 8),
            'topRooms' => ReportStats::topRooms($base, 10),
            'year' => $year,
            'years' => range(now()->year, now()->year - 3),
        ]);
    }
}

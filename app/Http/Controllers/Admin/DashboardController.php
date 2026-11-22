<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReportStatusHistory;
use App\Support\ReportStats;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->integer('month') ?: null;
        $year = $request->integer('year') ?: now()->year;
        $base = ReportStats::period($month, $year);

        return view('admin.dashboard', [
            'stats' => ReportStats::statuses($base),
            'trend' => ReportStats::trend($year),
            'byCategory' => ReportStats::byCategory($base),
            'byBuilding' => ReportStats::byBuilding($base),
            'topRooms' => ReportStats::topRooms($base, 3),
            'recent' => (clone $base)->with(['room.building', 'category', 'user'])->latest()->limit(6)->get(),
            'activity' => ReportStatusHistory::with(['author', 'report.category'])->latest()->limit(6)->get(),
            'month' => $month,
            'year' => $year,
            'years' => range(now()->year, now()->year - 3),
        ]);
    }
}

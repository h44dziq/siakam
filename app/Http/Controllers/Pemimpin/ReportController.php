<?php

namespace App\Http\Controllers\Pemimpin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Category;
use App\Models\Report;
use App\Models\Room;
use App\Support\ReportCsv;
use App\Support\ReportStats;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    private const FILTERS = ['q', 'status', 'from', 'to', 'month', 'year', 'building_id', 'room_id', 'category_id'];

    public function monitoring(Request $request)
    {
        $f = $request->only(['q', 'status', 'building_id', 'category_id']);
        $base = Report::query()->filter(array_intersect_key($f, array_flip(['q', 'building_id', 'category_id'])));

        return view('pemimpin.monitoring', [
            'reports' => (clone $base)->filter(['status' => $f['status'] ?? null])
                ->with(['room.building', 'category', 'user'])
                ->latest()->paginate(12)->withQueryString(),
            'stats' => ReportStats::statuses($base),
            'attention' => ReportStats::attention($base, 4),
            'f' => $f,
            'buildings' => Building::orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function rekapitulasi(Request $request)
    {
        $f = $request->only(self::FILTERS);
        $base = Report::query()->filter(array_intersect_key($f, array_flip(
            ['month', 'year', 'building_id', 'room_id', 'category_id']
        )));

        return view('pemimpin.rekapitulasi', [
            'reports' => (clone $base)->filter(['status' => $f['status'] ?? null])
                ->with(['room.building', 'category', 'user'])
                ->latest()->paginate(15)->withQueryString(),
            'stats' => ReportStats::statuses($base),
            'f' => $f,
            'buildings' => Building::orderBy('name')->get(),
            'rooms' => Room::with('building')->orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function export(Request $request)
    {
        return ReportCsv::download(Report::query()->filter($request->only(self::FILTERS)));
    }
}

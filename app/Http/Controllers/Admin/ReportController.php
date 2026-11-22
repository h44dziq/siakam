<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Category;
use App\Models\Report;
use App\Models\Room;
use App\Support\ReportCsv;
use App\Support\ReportStats;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    private const FILTERS = ['q', 'status', 'from', 'to', 'month', 'year', 'building_id', 'room_id', 'category_id'];

    public function index(Request $request)
    {
        $f = $request->only(self::FILTERS);

        $reports = Report::with(['room.building', 'category', 'user'])
            ->filter($f)
            ->latest()->paginate(15)->withQueryString();

        return view('admin.reports', $this->filterData() + compact('reports', 'f'));
    }

    public function rekapitulasi(Request $request)
    {
        $f = $request->only(self::FILTERS);
        $base = Report::query()->filter(array_intersect_key($f, array_flip(
            ['month', 'year', 'building_id', 'room_id', 'category_id']
        )));

        $stats = ReportStats::statuses($base);
        $reports = (clone $base)->filter(['status' => $f['status'] ?? null])
            ->with(['room.building', 'category', 'user'])
            ->latest()->paginate(15)->withQueryString();

        return view('admin.rekapitulasi', $this->filterData() + compact('reports', 'f', 'stats'));
    }

    public function export(Request $request)
    {
        return ReportCsv::download(Report::query()->filter($request->only(self::FILTERS)));
    }

    public function updateStatus(Request $request, Report $report)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(Report::LABELS))],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $report->advance($request->user(), $data['status'], $data['note'] ?? null);

        return back()->with('ok', 'Status diperbarui menjadi '.$report->fresh()->label().'.');
    }

    /** Data pendukung filter yang dipakai halaman daftar & rekapitulasi. */
    private function filterData(): array
    {
        return [
            'buildings' => Building::orderBy('name')->get(),
            'rooms' => Room::with('building')->orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
        ];
    }
}

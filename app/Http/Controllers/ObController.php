<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Report;
use Illuminate\Http\Request;

class ObController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', 'aktif');

        $reports = Report::with(['room.building', 'category', 'user'])
            ->when($status === 'aktif', fn ($q) => $q->whereIn('status', ['reported', 'in_progress']))
            ->when(array_key_exists($status, Report::LABELS), fn ($q) => $q->where('status', $status))
            ->when($request->building, fn ($q, $b) => $q->whereHas('room', fn ($r) => $r->where('building_id', $b)))
            ->latest()->paginate(12)->withQueryString();

        return view('ob.dashboard', [
            'reports' => $reports,
            'status' => $status,
            'buildings' => Building::orderBy('name')->get(),
        ]);
    }
}

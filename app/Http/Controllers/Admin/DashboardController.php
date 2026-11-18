<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $f = [
            'month' => $request->input('month'),
            'year' => $request->input('year', now()->year),
        ];

        $total = Report::filter($f)->count();
        $byStatus = Report::filter($f)->select('status', DB::raw('count(*) as c'))->groupBy('status')->pluck('c', 'status');

        $topRooms = Report::filter($f)
            ->select('room_id', DB::raw('count(*) as c'))
            ->groupBy('room_id')->orderByDesc('c')->limit(3)
            ->with('room.building')->get();

        $byCategory = Report::filter($f)
            ->select('category_id', DB::raw('count(*) as c'))
            ->groupBy('category_id')->orderByDesc('c')
            ->with('category')->get();

        return view('admin.dashboard', [
            'f' => $f,
            'total' => $total,
            'byStatus' => $byStatus,
            'topRooms' => $topRooms,
            'byCategory' => $byCategory,
            'years' => range(now()->year, now()->year - 3),
        ]);
    }
}

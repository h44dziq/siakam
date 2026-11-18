<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    private const FILTERS = ['q', 'status', 'from', 'to', 'month', 'year'];

    public function index(Request $request)
    {
        $reports = Report::with(['room.building', 'category', 'user'])
            ->filter($request->only(self::FILTERS))
            ->latest()->paginate(15)->withQueryString();

        return view('admin.reports', compact('reports'));
    }

    public function export(Request $request)
    {
        $rows = Report::with(['room.building', 'category', 'user'])
            ->filter($request->only(self::FILTERS))
            ->latest()->cursor();

        $name = 'laporan-siakam-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM agar Excel membaca UTF-8
            fputcsv($out, ['ID', 'Tanggal', 'Pelapor', 'NIM', 'Gedung', 'Ruangan', 'Kategori', 'Deskripsi', 'Status']);
            foreach ($rows as $r) {
                fputcsv($out, [
                    $r->id,
                    $r->created_at->format('Y-m-d H:i'),
                    $r->user->name,
                    $r->user->nim,
                    $r->room->building->name,
                    $r->room->name,
                    $r->category->name,
                    $r->description,
                    $r->label(),
                ]);
            }
            fclose($out);
        }, $name, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}

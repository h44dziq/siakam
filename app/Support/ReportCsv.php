<?php

namespace App\Support;

use App\Models\Report;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportCsv
{
    public static function download(Builder $query): StreamedResponse
    {
        $rows = $query->with(['room.building', 'category', 'user'])->latest()->cursor();
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

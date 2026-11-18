<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Category;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    /** Dashboard mahasiswa: riwayat laporan milik sendiri. */
    public function index(Request $request)
    {
        $reports = Report::with(['room.building', 'category'])
            ->where('user_id', $request->user()->id)
            ->latest()->paginate(10);

        return view('mahasiswa.dashboard', compact('reports'));
    }

    public function create()
    {
        $buildings = Building::with('rooms')->orderBy('name')->get();
        $roomsByBuilding = $buildings->mapWithKeys(fn ($b) => [
            $b->id => $b->rooms->map(fn ($r) => ['id' => $r->id, 'name' => $r->name])->values(),
        ]);

        return view('reports.create', [
            'buildings' => $buildings,
            'roomsByBuilding' => $roomsByBuilding,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'building_id' => ['required', 'exists:buildings,id'],
            // ruangan harus milik gedung yang dipilih (validasi server-side)
            'room_id' => ['required', Rule::exists('rooms', 'id')->where('building_id', $request->input('building_id'))],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['required', 'string', 'min:10', 'max:1000'],
            'photo' => ['nullable', 'image', 'max:4096'],
        ], [
            'room_id.exists' => 'Ruangan tidak sesuai dengan gedung yang dipilih.',
            'description.min' => 'Deskripsi minimal 10 karakter.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.max' => 'Ukuran foto maksimal 4 MB.',
        ]);

        $report = DB::transaction(function () use ($request, $data) {
            $report = Report::create([
                'user_id' => $request->user()->id,
                'room_id' => $data['room_id'],
                'category_id' => $data['category_id'],
                'description' => $data['description'],
                'photo_path' => $request->file('photo')?->store('reports'), // disk private
                'status' => 'reported',
            ]);
            $report->histories()->create([
                'changed_by' => $request->user()->id,
                'from_status' => null,
                'to_status' => 'reported',
            ]);

            return $report;
        });

        return redirect()->route('laporan.show', $report)->with('ok', 'Laporan terkirim. Petugas akan segera menanganinya.');
    }

    /** Detail laporan, dipakai semua role. */
    public function show(Request $request, Report $report)
    {
        $user = $request->user();
        abort_if($user->role === 'mahasiswa' && $report->user_id !== $user->id, 403);

        $report->load(['room.building', 'category', 'user', 'histories.author']);

        return view('reports.show', compact('report'));
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

    /** Foto bukti: hanya lewat controller, tidak ada URL publik. */
    public function photo(Request $request, Report $report)
    {
        $user = $request->user();
        abort_if($user->role === 'mahasiswa' && $report->user_id !== $user->id, 403);
        abort_unless($report->photo_path && Storage::exists($report->photo_path), 404);

        return Storage::response($report->photo_path);
    }
}

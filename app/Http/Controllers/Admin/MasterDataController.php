<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Category;
use App\Models\Room;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    public function buildings()
    {
        return view('admin.buildings', [
            'buildings' => Building::withCount('rooms')->orderBy('name')->get(),
        ]);
    }

    public function rooms(Request $request)
    {
        return view('admin.rooms', [
            'buildings' => Building::orderBy('name')->get(),
            'rooms' => Room::with('building')->withCount('reports')
                ->when($request->building_id, fn ($q, $v) => $q->where('building_id', $v))
                ->orderBy('name')->get(),
            'buildingId' => $request->building_id,
        ]);
    }

    public function categories()
    {
        return view('admin.categories', [
            'categories' => Category::withCount('reports')->orderBy('name')->get(),
        ]);
    }

    public function storeBuilding(Request $request)
    {
        Building::create($request->validate(['name' => ['required', 'string', 'max:100']]));

        return back()->with('ok', 'Gedung ditambahkan.');
    }

    public function updateBuilding(Request $request, Building $building)
    {
        $building->update($request->validate(['name' => ['required', 'string', 'max:100']]));

        return back()->with('ok', 'Gedung diperbarui.');
    }

    public function destroyBuilding(Building $building)
    {
        return $this->safeDelete($building, 'Gedung masih punya ruangan atau laporan, jadi tidak bisa dihapus.');
    }

    public function storeRoom(Request $request)
    {
        $data = $request->validate([
            'building_id' => ['required', 'exists:buildings,id'],
            'name' => ['required', 'string', 'max:100'],
        ]);

        Room::create($data);

        return back()->with('ok', 'Ruangan ditambahkan.');
    }

    public function updateRoom(Request $request, Room $room)
    {
        $room->update($request->validate([
            'building_id' => ['required', 'exists:buildings,id'],
            'name' => ['required', 'string', 'max:100'],
        ]));

        return back()->with('ok', 'Ruangan diperbarui.');
    }

    public function destroyRoom(Room $room)
    {
        return $this->safeDelete($room, 'Ruangan sudah dipakai di laporan, jadi tidak bisa dihapus.');
    }

    public function storeCategory(Request $request)
    {
        Category::create($request->validate(['name' => ['required', 'string', 'max:100']]));

        return back()->with('ok', 'Kategori ditambahkan.');
    }

    public function updateCategory(Request $request, Category $category)
    {
        $category->update($request->validate(['name' => ['required', 'string', 'max:100']]));

        return back()->with('ok', 'Kategori diperbarui.');
    }

    public function destroyCategory(Category $category)
    {
        return $this->safeDelete($category, 'Kategori sudah dipakai di laporan, jadi tidak bisa dihapus.');
    }

    /** Foreign key restrict menjaga laporan lama tetap konsisten. */
    private function safeDelete($model, string $blockedMessage)
    {
        try {
            $model->delete();
        } catch (QueryException $e) {
            return back()->withErrors(['delete' => $blockedMessage]);
        }

        return back()->with('ok', 'Data dihapus.');
    }
}

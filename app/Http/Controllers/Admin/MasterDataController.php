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
    public function index(Request $request)
    {
        return view('admin.master', [
            'tab' => $request->input('tab') === 'kategori' ? 'kategori' : 'fasilitas',
            'buildings' => Building::with('rooms')->orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
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

    public function storeRoom(Request $request, Building $building)
    {
        $building->rooms()->create($request->validate(['name' => ['required', 'string', 'max:100']]));

        return back()->with('ok', 'Ruangan ditambahkan.');
    }

    public function updateRoom(Request $request, Room $room)
    {
        $room->update($request->validate(['name' => ['required', 'string', 'max:100']]));

        return back()->with('ok', 'Ruangan diperbarui.');
    }

    public function destroyRoom(Room $room)
    {
        return $this->safeDelete($room, 'Ruangan sudah dipakai di laporan, jadi tidak bisa dihapus.');
    }

    public function storeCategory(Request $request)
    {
        Category::create($request->validate(['name' => ['required', 'string', 'max:100']]));

        return redirect()->route('admin.master', ['tab' => 'kategori'])->with('ok', 'Kategori ditambahkan.');
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

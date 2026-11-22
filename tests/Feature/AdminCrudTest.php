<?php

namespace Tests\Feature;

use App\Models\Building;
use App\Models\Category;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_manages_buildings_rooms_and_categories(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post(route('admin.gedung.store'), ['name' => 'Gedung D'])
            ->assertSessionHasNoErrors();
        $building = Building::where('name', 'Gedung D')->firstOrFail();

        $this->actingAs($admin)->put(route('admin.gedung.update', $building), ['name' => 'Gedung D Baru'])
            ->assertSessionHasNoErrors();
        $this->assertSame('Gedung D Baru', $building->fresh()->name);

        $this->actingAs($admin)->post(route('admin.ruangan.store'), [
            'building_id' => $building->id,
            'name' => 'Lab Jaringan',
        ])->assertSessionHasNoErrors();
        $room = $building->rooms()->firstOrFail();
        $this->assertSame('Lab Jaringan', $room->name);

        $this->actingAs($admin)->put(route('admin.ruangan.update', $room), [
            'building_id' => $building->id,
            'name' => 'Lab Jaringan 2',
        ])->assertSessionHasNoErrors();
        $this->assertSame('Lab Jaringan 2', $room->fresh()->name);

        $this->actingAs($admin)->post(route('admin.kategori.store'), ['name' => 'Kebocoran'])
            ->assertSessionHasNoErrors();
        $category = Category::where('name', 'Kebocoran')->firstOrFail();

        $this->actingAs($admin)->put(route('admin.kategori.update', $category), ['name' => 'Kebocoran Atap'])
            ->assertSessionHasNoErrors();
        $this->assertSame('Kebocoran Atap', $category->fresh()->name);

        $this->actingAs($admin)->delete(route('admin.kategori.destroy', $category))->assertSessionHasNoErrors();
        $this->assertModelMissing($category);

        $this->actingAs($admin)->delete(route('admin.ruangan.destroy', $room))->assertSessionHasNoErrors();
        $this->assertModelMissing($room);

        $this->actingAs($admin)->delete(route('admin.gedung.destroy', $building))->assertSessionHasNoErrors();
        $this->assertModelMissing($building);
    }

    public function test_master_data_in_use_cannot_be_deleted(): void
    {
        $admin = $this->admin();
        $building = Building::create(['name' => 'Gedung A']);
        $room = $building->rooms()->create(['name' => 'Lab 1']);
        $category = Category::create(['name' => 'AC menyala']);
        $reporter = User::factory()->create();

        Report::create([
            'user_id' => $reporter->id,
            'room_id' => $room->id,
            'category_id' => $category->id,
            'description' => 'AC menyala padahal ruangan kosong.',
            'status' => 'reported',
        ]);

        $this->actingAs($admin)->delete(route('admin.gedung.destroy', $building))
            ->assertSessionHasErrors('delete');
        $this->actingAs($admin)->delete(route('admin.ruangan.destroy', $room))
            ->assertSessionHasErrors('delete');
        $this->actingAs($admin)->delete(route('admin.kategori.destroy', $category))
            ->assertSessionHasErrors('delete');

        $this->assertModelExists($building);
        $this->assertModelExists($room);
        $this->assertModelExists($category);
    }

    public function test_admin_creates_updates_and_toggles_admin_accounts(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post(route('admin.users.store'), [
            'role' => 'admin',
            'name' => 'Admin Baru',
            'email' => 'admin2@siakam.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertSessionHasNoErrors();

        $created = User::where('email', 'admin2@siakam.test')->firstOrFail();
        $this->assertSame('admin', $created->role);
        $this->assertTrue($created->is_active);

        $this->actingAs($admin)->put(route('admin.users.update', $created), [
            'name' => 'Admin Diubah',
            'email' => 'admin3@siakam.test',
            'is_active' => 0,
        ])->assertSessionHasNoErrors();

        $created->refresh();
        $this->assertSame('Admin Diubah', $created->name);
        $this->assertSame('admin3@siakam.test', $created->email);
        $this->assertFalse($created->is_active);

        $this->actingAs($admin)->patch(route('admin.users.toggle', $created))->assertSessionHasNoErrors();
        $this->assertTrue($created->fresh()->is_active);
    }

    public function test_admin_cannot_toggle_own_account(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->patch(route('admin.users.toggle', $admin))->assertForbidden();
        $this->assertTrue($admin->fresh()->is_active);
    }

    public function test_user_can_update_profile_and_password(): void
    {
        $user = User::factory()->create(['name' => 'Nama Lama']);

        $this->actingAs($user)->put(route('profil.update'), ['name' => 'Nama Baru'])
            ->assertSessionHasNoErrors();
        $this->assertSame('Nama Baru', $user->fresh()->name);

        // Password baru wajib disertai password saat ini.
        $this->actingAs($user)->put(route('profil.update'), [
            'name' => 'Nama Baru',
            'password' => 'passwordbaru',
            'password_confirmation' => 'passwordbaru',
        ])->assertSessionHasErrors('current_password');

        $this->actingAs($user)->put(route('profil.update'), [
            'name' => 'Nama Baru',
            'current_password' => 'password',
            'password' => 'passwordbaru',
            'password_confirmation' => 'passwordbaru',
        ])->assertSessionHasNoErrors();

        $this->assertTrue(Hash::check('passwordbaru', $user->fresh()->password));
    }
}

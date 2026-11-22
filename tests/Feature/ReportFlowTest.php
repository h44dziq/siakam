<?php

namespace Tests\Feature;

use App\Models\Building;
use App\Models\Category;
use App\Models\Report;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ReportFlowTest extends TestCase
{
    use RefreshDatabase;

    private function reportFor(User $user): Report
    {
        $building = Building::create(['name' => 'Gedung A']);
        $room = $building->rooms()->create(['name' => 'Lab 1']);
        $category = Category::create(['name' => 'AC menyala']);

        $report = Report::create([
            'user_id' => $user->id,
            'room_id' => $room->id,
            'category_id' => $category->id,
            'description' => 'AC menyala padahal ruangan kosong.',
            'status' => 'reported',
        ]);
        $report->histories()->create([
            'changed_by' => $user->id, 'from_status' => null, 'to_status' => 'reported',
        ]);

        return $report;
    }

    public function test_mahasiswa_can_submit_report_with_photo(): void
    {
        Storage::fake('local');
        $user = User::factory()->create();
        $building = Building::create(['name' => 'Gedung A']);
        $room = $building->rooms()->create(['name' => 'Lab 1']);
        $category = Category::create(['name' => 'Lampu mati']);

        $response = $this->actingAs($user)->post(route('mahasiswa.laporan.store'), [
            'building_id' => $building->id,
            'room_id' => $room->id,
            'category_id' => $category->id,
            'description' => 'Lampu di koridor mati sejak kemarin.',
            'photo' => \Illuminate\Http\UploadedFile::fake()->create('bukti.jpg', 120, 'image/jpeg'),
        ]);

        $report = Report::first();
        $response->assertRedirect(route('laporan.show', $report));
        $this->assertNotNull($report->photo_path);
        $this->assertSame('reported', $report->status);
        $this->assertDatabaseHas('report_status_histories', ['report_id' => $report->id, 'to_status' => 'reported']);
        Storage::assertExists($report->photo_path);
    }

    public function test_mahasiswa_only_sees_their_own_report(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $report = $this->reportFor($owner);

        $this->actingAs($owner)->get(route('laporan.show', $report))->assertOk();
        $this->actingAs($other)->get(route('laporan.show', $report))->assertForbidden();
    }

    public function test_admin_and_pemimpin_can_view_any_report(): void
    {
        $report = $this->reportFor(User::factory()->create());
        $admin = User::factory()->create(['role' => 'admin']);
        $pemimpin = User::factory()->create(['role' => 'pemimpin']);

        $this->actingAs($admin)->get(route('laporan.show', $report))->assertOk();
        $this->actingAs($pemimpin)->get(route('laporan.show', $report))->assertOk();
    }

    public function test_admin_advances_status_one_step_at_a_time(): void
    {
        $report = $this->reportFor(User::factory()->create());
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->patch(route('admin.laporan.status', $report), ['status' => 'in_progress', 'note' => 'Petugas menuju lokasi.'])
            ->assertRedirect();
        $this->assertSame('in_progress', $report->fresh()->status);

        $this->actingAs($admin)
            ->patch(route('admin.laporan.status', $report), ['status' => 'resolved', 'note' => 'Sudah diperbaiki.'])
            ->assertRedirect();
        $this->assertSame('resolved', $report->fresh()->status);
        $this->assertDatabaseCount('report_status_histories', 3);

        // Status tidak boleh mundur.
        $this->actingAs($admin)
            ->patch(route('admin.laporan.status', $report), ['status' => 'in_progress'])
            ->assertSessionHasErrors('status');
        $this->assertSame('resolved', $report->fresh()->status);
    }

    public function test_mahasiswa_and_pemimpin_cannot_change_status(): void
    {
        $report = $this->reportFor(User::factory()->create());

        $this->actingAs(User::factory()->create())
            ->patch(route('admin.laporan.status', $report), ['status' => 'in_progress'])
            ->assertForbidden();

        $this->actingAs(User::factory()->create(['role' => 'pemimpin']))
            ->patch(route('admin.laporan.status', $report), ['status' => 'in_progress'])
            ->assertForbidden();

        $this->assertSame('reported', $report->fresh()->status);
    }

    public function test_photo_is_only_available_to_authorized_users(): void
    {
        Storage::fake('local');
        $owner = User::factory()->create();
        $report = $this->reportFor($owner);
        $report->update(['photo_path' => 'reports/bukti.jpg']);
        Storage::put('reports/bukti.jpg', 'image-bytes');

        $this->actingAs($owner)->get(route('foto.show', $report))->assertOk();
        $this->actingAs(User::factory()->create())->get(route('foto.show', $report))->assertForbidden();
        $this->actingAs(User::factory()->create(['role' => 'admin']))->get(route('foto.show', $report))->assertOk();
    }
}

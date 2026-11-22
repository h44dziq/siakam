<?php

namespace Tests\Feature;

use App\Models\Building;
use App\Models\Category;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    private function seedReports(): void
    {
        $building = Building::create(['name' => 'Gedung A']);
        $room = $building->rooms()->create(['name' => 'Lab 1']);
        $category = Category::create(['name' => 'AC menyala']);
        $user = User::factory()->create();

        foreach (['reported', 'in_progress', 'resolved'] as $status) {
            $report = Report::create([
                'user_id' => $user->id,
                'room_id' => $room->id,
                'category_id' => $category->id,
                'description' => 'AC menyala padahal ruangan kosong.',
                'status' => $status,
            ]);
            $report->histories()->create([
                'changed_by' => $user->id, 'from_status' => null, 'to_status' => 'reported',
            ]);
        }
    }

    public function test_mahasiswa_pages_render(): void
    {
        $this->seedReports();
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('mahasiswa.dashboard'))->assertOk();
        $this->actingAs($user)->get(route('mahasiswa.riwayat'))->assertOk();
        $this->actingAs($user)->get(route('mahasiswa.laporan.create'))->assertOk();
        $this->actingAs($user)->get(route('profil.edit'))->assertOk();
    }

    public function test_admin_pages_render(): void
    {
        $this->seedReports();
        $admin = User::factory()->create(['role' => 'admin']);

        foreach ([
            'admin.dashboard', 'admin.laporan.index', 'admin.rekapitulasi',
            'admin.gedung.index', 'admin.ruangan.index', 'admin.kategori.index', 'admin.users',
        ] as $route) {
            $this->actingAs($admin)->get(route($route))->assertOk();
        }

        $this->actingAs($admin)->get(route('admin.laporan.index', ['status' => 'resolved']))->assertOk();
        $this->actingAs($admin)->get(route('admin.rekapitulasi', ['month' => now()->month, 'year' => now()->year]))->assertOk();
    }

    public function test_pemimpin_pages_render_with_real_statistics(): void
    {
        $this->seedReports();
        $pemimpin = User::factory()->create(['role' => 'pemimpin']);

        foreach ([
            'pemimpin.dashboard', 'pemimpin.monitoring', 'pemimpin.analytics',
            'pemimpin.rekapitulasi', 'pemimpin.insight',
        ] as $route) {
            $this->actingAs($pemimpin)->get(route($route))->assertOk();
        }

        $this->actingAs($pemimpin)
            ->get(route('pemimpin.dashboard'))
            ->assertSee('Total laporan')
            ->assertSee('1');
    }

    public function test_reports_can_be_exported_as_csv(): void
    {
        $this->seedReports();
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.laporan.export'));
        $response->assertOk();
        $this->assertStringContainsString('text/csv', $response->headers->get('content-type'));
    }
}

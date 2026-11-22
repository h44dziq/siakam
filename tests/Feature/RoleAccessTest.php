<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/')->assertRedirect(route('login'));
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->get(route('pemimpin.dashboard'))->assertRedirect(route('login'));
    }

    public function test_each_role_lands_on_its_own_dashboard(): void
    {
        $cases = [
            'mahasiswa' => 'mahasiswa.dashboard',
            'admin' => 'admin.dashboard',
            'pemimpin' => 'pemimpin.dashboard',
        ];

        foreach ($cases as $role => $route) {
            $user = User::factory()->create(['role' => $role, 'email' => $role.'@siakam.test']);

            $this->post('/login', ['email' => $user->email, 'password' => 'password'])
                ->assertRedirect(route($route));

            $this->assertAuthenticatedAs($user);
            auth()->logout();
        }
    }

    public function test_inactive_account_cannot_login(): void
    {
        $user = User::factory()->create(['is_active' => false]);

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_mahasiswa_cannot_open_admin_or_pemimpin_pages(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('admin.dashboard'))->assertForbidden();
        $this->actingAs($user)->get(route('admin.laporan.index'))->assertForbidden();
        $this->actingAs($user)->get(route('pemimpin.dashboard'))->assertForbidden();
        $this->actingAs($user)->get(route('admin.users'))->assertForbidden();
    }

    public function test_admin_cannot_open_pemimpin_pages(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get(route('pemimpin.dashboard'))->assertForbidden();
        $this->actingAs($admin)->get(route('pemimpin.analytics'))->assertForbidden();
        $this->actingAs($admin)->get(route('pemimpin.rekapitulasi'))->assertForbidden();
    }

    public function test_pemimpin_is_read_only(): void
    {
        $pemimpin = User::factory()->create(['role' => 'pemimpin']);

        $this->actingAs($pemimpin)->get(route('admin.dashboard'))->assertForbidden();
        $this->actingAs($pemimpin)->get(route('admin.gedung.index'))->assertForbidden();
        $this->actingAs($pemimpin)->get(route('admin.users'))->assertForbidden();
        $this->actingAs($pemimpin)->post(route('admin.gedung.store'), ['name' => 'Gedung X'])->assertForbidden();
    }

    public function test_admin_cannot_manage_pemimpin_account(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $pemimpin = User::factory()->create(['role' => 'pemimpin']);

        $this->actingAs($admin)
            ->patch(route('admin.users.toggle', $pemimpin))
            ->assertForbidden();
    }

    public function test_registration_requires_campus_email(): void
    {
        $this->post('/register', [
            'username' => 'budi',
            'email' => 'budi@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();

        $this->post('/register', [
            'username' => 'budi',
            'email' => '2310512999@mahasiswa.upnvj.ac.id',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('mahasiswa.dashboard'));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => '2310512999@mahasiswa.upnvj.ac.id',
            'role' => 'mahasiswa',
            'nim' => '2310512999',
        ]);
    }
}

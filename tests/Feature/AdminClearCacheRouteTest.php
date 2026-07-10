<?php

namespace Tests\Feature;

use App\Models\Backend\User;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class AdminClearCacheRouteTest extends TestCase
{
    private function createAdmin(): User
    {
        return User::create([
            'name' => 'Cache Admin',
            'username' => 'cache_admin_'.time(),
            'email' => 'cache_admin_'.time().'@example.com',
            'password' => bcrypt('password'),
            'status' => 1,
        ]);
    }

    public function test_cache_clear_get_route_is_not_available(): void
    {
        $this->get('/admin/cc')->assertStatus(405);
    }

    public function test_guest_cannot_clear_admin_cache(): void
    {
        Artisan::shouldReceive('call')->never();

        $this->post(route('admin.cache.clear'))->assertRedirect('/admin/login');
    }

    public function test_admin_can_clear_cache_from_protected_route(): void
    {
        $admin = $this->createAdmin();

        Artisan::shouldReceive('call')
            ->once()
            ->with('optimize:clear')
            ->andReturn(0);

        $response = $this
            ->actingAs($admin, 'admin')
            ->post(route('admin.cache.clear'));

        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('success', 'Đã xóa cache hệ thống.');

        $admin->delete();
    }

    public function test_sidebar_contains_cache_clear_action(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Xóa cache', false);
        $response->assertSee('action="'.route('admin.cache.clear').'"', false);

        $admin->delete();
    }
}

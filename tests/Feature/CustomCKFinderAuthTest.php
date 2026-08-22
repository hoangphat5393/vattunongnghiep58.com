<?php

namespace Tests\Feature;

use App\Http\Middleware\CustomCKFinderAuth;
use App\Models\Backend\Role;
use App\Models\Backend\User;
use Illuminate\Http\Request;
use Tests\TestCase;

class CustomCKFinderAuthTest extends TestCase
{
    private function createAdmin(): User
    {
        $admin = User::create([
            'name' => 'CKFinder Admin',
            'username' => 'ckfinder_admin_'.time(),
            'email' => 'ckfinder_admin_'.time().'@example.com',
            'password' => bcrypt('password'),
            'status' => 1,
        ]);

        $role = Role::firstOrCreate(['slug' => 'administrator'], ['name' => 'Administrator']);
        $admin->roles()->sync([$role->id]);

        return $admin;
    }

    public function test_ckfinder_auth_callback_denies_guest(): void
    {
        $middleware = new CustomCKFinderAuth;
        $request = Request::create('/ckfinder/connector', 'POST');

        $middleware->handle($request, function () {
            $callback = config('ckfinder.authentication');

            $this->assertIsCallable($callback);
            $this->assertFalse($callback());

            return response('ok');
        });
    }

    public function test_ckfinder_auth_callback_allows_authenticated_admin(): void
    {
        $admin = $this->createAdmin();
        $this->actingAs($admin, 'admin');

        $middleware = new CustomCKFinderAuth;
        $request = Request::create('/ckfinder/connector', 'POST');

        $middleware->handle($request, function () {
            $callback = config('ckfinder.authentication');

            $this->assertIsCallable($callback);
            $this->assertTrue($callback());

            return response('ok');
        });

        $admin->delete();
    }

    public function test_guest_cannot_access_ckfinder_connector(): void
    {
        $middleware = new CustomCKFinderAuth;
        $request = Request::create('/ckfinder/connector', 'POST');
        $handled = false;

        $middleware->handle($request, function () use (&$handled) {
            $handled = true;
            $callback = config('ckfinder.authentication');
            $this->assertFalse($callback());

            return response('Access Denied', 403);
        });

        $this->assertTrue($handled);
    }

    public function test_authenticated_admin_can_access_album_library_with_ckfinder(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin, 'admin')
            ->get(route('admin.album.library'))
            ->assertOk();

        $admin->delete();
    }

    public function test_ckfinder_browser_page_loads_assets_plugin_script(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin, 'admin')->get(route('ckfinder_browser'));

        $response->assertOk();
        $response->assertSee(asset('assets/plugin/ckfinder/ckfinder.js'), false);

        $admin->delete();
    }

    public function test_ckfinder_routes_use_web_middleware(): void
    {
        $route = app('router')->getRoutes()->getByName('ckfinder_connector');

        $this->assertNotNull($route);
        $this->assertContains('web', $route->gatherMiddleware());
    }
}

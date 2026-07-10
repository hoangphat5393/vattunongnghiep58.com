<?php

namespace Tests\Feature;

use App\Models\Backend\AdminMenu;
use App\Models\Backend\Role;
use App\Models\Backend\User;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AdminAxiosP1Test extends TestCase
{
    private function createAdmin(string $password = 'password'): User
    {
        $admin = User::create([
            'name' => 'Admin Axios Test',
            'username' => 'admin_axios_'.time(),
            'email' => 'admin_axios_'.time().'@example.com',
            'password' => bcrypt($password),
            'status' => 1,
        ]);

        $role = Role::firstOrCreate(['slug' => 'administrator'], ['name' => 'Administrator']);
        $admin->roles()->sync([$role->id]);

        return $admin;
    }

    public function test_check_password_returns_error_for_wrong_password(): void
    {
        if (! Route::has('admin.checkPassword')) {
            $this->markTestSkipped('Route admin.checkPassword is not registered.');
        }

        $admin = $this->createAdmin('correct-password');

        ob_start();
        $response = $this->actingAs($admin, 'admin')->get(route('admin.checkPassword', [
            'current_password' => 'wrong-password',
        ]));
        $output = ob_get_clean();

        $response->assertStatus(200);
        $this->assertStringContainsString('Mật khẩu hiện tại không chính xác', $output);

        $admin->delete();
    }

    public function test_check_password_returns_empty_for_correct_password(): void
    {
        if (! Route::has('admin.checkPassword')) {
            $this->markTestSkipped('Route admin.checkPassword is not registered.');
        }

        $admin = $this->createAdmin('correct-password');

        ob_start();
        $response = $this->actingAs($admin, 'admin')->get(route('admin.checkPassword', [
            'current_password' => 'correct-password',
        ]));
        $output = ob_get_clean();

        $response->assertStatus(200);
        $this->assertSame('', trim($output));

        $admin->delete();
    }

    public function test_admin_menu_update_sort_accepts_nested_tree(): void
    {
        if (! Route::has('admin.admin-menu.update_sort')) {
            $this->markTestSkipped('Route admin.admin-menu.update_sort is not registered.');
        }

        $admin = $this->createAdmin();

        $parent = AdminMenu::create([
            'parent_id' => 0,
            'title' => 'Parent Menu '.time(),
            'uri' => null,
            'icon' => 'bi bi-circle',
            'type' => 0,
            'sort' => 1,
            'hidden' => 0,
        ]);

        $child = AdminMenu::create([
            'parent_id' => $parent->id,
            'title' => 'Child Menu '.time(),
            'uri' => 'admin.dashboard',
            'icon' => 'bi bi-circle',
            'type' => 0,
            'sort' => 1,
            'hidden' => 0,
        ]);

        $menuTree = json_encode([
            [
                'id' => (string) $parent->id,
                'children' => [
                    ['id' => (string) $child->id],
                ],
            ],
        ]);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.admin-menu.update_sort'), [
            'menu' => $menuTree,
        ]);

        $response->assertStatus(200);
        $data = $response->json();

        if (($data['error'] ?? 1) === 1 && (
            str_contains((string) ($data['msg'] ?? ''), 'Unknown database')
            || str_contains((string) ($data['msg'] ?? ''), 'actively refused')
        )) {
            $child->delete();
            $parent->delete();
            $admin->delete();
            $this->markTestSkipped('AdminMenu::reSort uses mysql connection; skipped on sqlite test DB.');
        }

        $response->assertJson(['error' => 0]);

        $child->refresh();
        $parent->refresh();

        $this->assertSame($parent->id, $child->parent_id);
        $this->assertSame(1, $parent->sort);
        $this->assertSame(1, $child->sort);

        $child->delete();
        $parent->delete();
        $admin->delete();
    }

    public function test_change_password_page_uses_axios_not_jquery_ajax(): void
    {
        if (! Route::has('admin.change-password')) {
            $this->markTestSkipped('Route admin.change-password is not registered.');
        }

        $admin = $this->createAdmin();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.change-password'));

        $response->assertStatus(200);
        $response->assertSee('axios.get(admin_url + "/check-password"', false);
        $response->assertDontSee('$.ajax', false);

        $admin->delete();
    }

    public function test_admin_menu_page_uses_axios_not_jquery_ajax(): void
    {
        if (! Route::has('admin.admin-menu.index')) {
            $this->markTestSkipped('Route admin.admin-menu.index is not registered.');
        }

        $admin = $this->createAdmin();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.admin-menu.index'));

        $response->assertStatus(200);
        $response->assertSee("axios.post('".route('admin.admin-menu.update_sort')."'", false);
        $response->assertDontSee('$.ajax', false);

        $admin->delete();
    }
}

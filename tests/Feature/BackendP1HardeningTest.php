<?php

namespace Tests\Feature;

use App\Models\Backend\Page;
use App\Models\Backend\Role;
use App\Models\Backend\User;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class BackendP1HardeningTest extends TestCase
{
    private function createAdministrator(): User
    {
        $admin = User::create([
            'name' => 'P1 Admin',
            'username' => 'p1_admin_'.time(),
            'email' => 'p1_admin_'.time().'@example.com',
            'password' => bcrypt('password'),
            'status' => 1,
        ]);

        $role = Role::firstOrCreate(['slug' => 'administrator'], ['name' => 'Administrator']);
        $admin->roles()->sync([$role->id]);

        return $admin;
    }

    public function test_frontend_header_uses_user_login_route(): void
    {
        $this->assertTrue(Route::has('customer.login'));

        $response = $this->get(route('index'));

        $response->assertOk();
        $response->assertDontSee(route('customer.login'), false);
    }

    public function test_guest_customer_route_redirects_to_customer_login(): void
    {
        $this->get(route('customer.dashboard'))
            ->assertRedirect(route('customer.login'));
    }

    public function test_ajax_delete_post_does_not_require_alter_table(): void
    {
        $admin = $this->createAdministrator();
        $post = Page::create([
            'name' => 'Ajax Delete Post',
            'slug' => 'ajax-delete-post-'.time(),
            'type' => 'post',
            'status' => 1,
            'sort' => 1,
        ]);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.bulk.delete'), [
            'type' => 'post',
            'seq_list' => [$post->id],
        ]);

        $response->assertOk();
        $this->assertDatabaseMissing('pages', ['id' => $post->id]);

        $admin->delete();
    }

    public function test_ajax_delete_page_removes_records_successfully(): void
    {
        $admin = $this->createAdministrator();
        $page = Page::create([
            'name' => 'Ajax Delete Test Page',
            'slug' => 'ajax-delete-test-page-'.time(),
            'type' => 'page',
            'status' => 1,
            'sort' => 1,
        ]);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.bulk.delete'), [
            'type' => 'page',
            'seq_list' => [$page->id],
        ]);

        $response->assertOk();
        $this->assertDatabaseMissing('pages', ['id' => $page->id]);

        $admin->delete();
    }

    public function test_user_without_permission_cannot_access_product_index(): void
    {
        $user = User::create([
            'name' => 'No Product Perm',
            'username' => 'noprod_'.time(),
            'email' => 'noprod_'.time().'@example.com',
            'password' => bcrypt('password'),
            'status' => 1,
        ]);

        $this->actingAs($user, 'admin')
            ->get(route('admin.product.index'))
            ->assertForbidden();

        $user->delete();
    }

    public function test_user_without_permission_can_still_change_password_page(): void
    {
        $user = User::create([
            'name' => 'Change Pass User',
            'username' => 'changepass_'.time(),
            'email' => 'changepass_'.time().'@example.com',
            'password' => bcrypt('password'),
            'status' => 1,
        ]);

        $this->actingAs($user, 'admin')
            ->get(route('admin.change-password'))
            ->assertOk();

        $user->delete();
    }

    public function test_user_model_ignores_non_fillable_fields_on_create(): void
    {
        $user = User::create([
            'name' => 'Fillable Test',
            'username' => 'fillable_'.time(),
            'email' => 'fillable_'.time().'@example.com',
            'password' => bcrypt('password'),
            'status' => 1,
            'remember_token' => 'should-not-persist-via-mass-assign',
        ]);

        $this->assertNull($user->remember_token);

        $user->delete();
    }

    public function test_customer_login_is_rate_limited(): void
    {
        $payload = [
            'email' => 'rate_limit_'.time().'@example.com',
            'password' => 'wrong-password',
        ];

        for ($i = 0; $i < 6; $i++) {
            $this->post(route('customer.login.submit'), $payload);
        }

        $this->post(route('customer.login.submit'), $payload)->assertStatus(429);
    }
}

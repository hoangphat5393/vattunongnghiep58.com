<?php

namespace Tests\Feature;

use App\Models\Backend\Category;
use App\Models\Backend\Page;
use App\Models\Backend\Role;
use App\Models\Backend\User;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class BackendP2HardeningTest extends TestCase
{
    private function createAdministrator(): User
    {
        $admin = User::create([
            'name' => 'P2 Admin',
            'username' => 'p2_admin_'.time(),
            'email' => 'p2_admin_'.time().'@example.com',
            'password' => bcrypt('password'),
            'status' => 1,
        ]);

        $role = Role::firstOrCreate(['slug' => 'administrator'], ['name' => 'Administrator']);
        $admin->roles()->sync([$role->id]);

        return $admin;
    }

    public function test_post_category_routes_are_registered(): void
    {
        $this->assertTrue(Route::has('admin.post-category.index'));
        $this->assertTrue(Route::has('admin.post-category.edit'));
        $this->assertTrue(Route::has('admin.post-category.destroy'));
    }

    public function test_administrator_can_access_post_category_index(): void
    {
        $admin = $this->createAdministrator();

        $this->actingAs($admin, 'admin')
            ->get(route('admin.post-category.index'))
            ->assertOk();

        $admin->delete();
    }

    public function test_page_index_reports_total_not_page_count(): void
    {
        $admin = $this->createAdministrator();
        $beforeTotal = Page::pages()->count();

        for ($i = 0; $i < 21; $i++) {
            Page::create([
                'name' => 'P2 Page '.$i,
                'slug' => 'p2-page-'.time().'-'.$i,
                'type' => 'page',
                'status' => 1,
                'sort' => $i,
            ]);
        }

        $response = $this->actingAs($admin, 'admin')->get(route('admin.page.index'));

        $response->assertOk();
        $response->assertViewHas('total_item', $beforeTotal + 21);

        Page::where('slug', 'like', 'p2-page-%')->delete();
        $admin->delete();
    }

    public function test_administrator_can_delete_post_category(): void
    {
        $admin = $this->createAdministrator();
        $category = Category::create([
            'name' => 'Post Cat Delete',
            'slug' => 'post-cat-delete-'.time(),
            'parent' => 0,
            'sort' => 1,
            'status' => 1,
        ]);

        $this->actingAs($admin, 'admin')
            ->delete(route('admin.post-category.destroy', $category->id))
            ->assertRedirect(route('admin.post-category.index'));

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);

        $admin->delete();
    }
}

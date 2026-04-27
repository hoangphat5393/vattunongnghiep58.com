<?php

namespace Tests\Feature;

use App\Models\Backend\AdminMenu;
use App\Models\Backend\Role;
use App\Models\Backend\User;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    public function test_admin_can_access_dashboard(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'username' => 'admin_test',
            'email' => 'admin_dashboard@example.com',
            'password' => bcrypt('password'),
            'status' => 1,
        ]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));

        $response->assertStatus(200);

        $admin->delete();
    }

    public function test_sidebar_opens_product_menu_on_product_category_page(): void
    {
        if (! Route::has('admin.product-category.index') || ! Route::has('admin.product.index')) {
            $this->markTestSkipped('Missing required admin product routes.');
        }

        $role = Role::firstOrCreate(['slug' => 'administrator'], ['name' => 'Administrator']);

        $admin = User::create([
            'name' => 'Admin User',
            'username' => 'admin_sidebar_test',
            'email' => 'admin_sidebar_'.time().'@example.com',
            'password' => bcrypt('password'),
            'status' => 1,
        ]);

        $admin->roles()->sync([$role->id]);

        $menuTitle = 'Sản phẩm Test '.time();

        $parent = AdminMenu::create([
            'parent_id' => 0,
            'title' => $menuTitle,
            'uri' => null,
            'icon' => 'bi bi-box-seam',
            'type' => 0,
            'sort' => 0,
            'hidden' => 0,
        ]);

        $childProduct = AdminMenu::create([
            'parent_id' => $parent->id,
            'title' => 'Tất cả sản phẩm',
            'uri' => 'admin.product.index',
            'icon' => 'bi bi-circle',
            'type' => 0,
            'sort' => 0,
            'hidden' => 0,
        ]);

        $childProductCategory = AdminMenu::create([
            'parent_id' => $parent->id,
            'title' => 'Danh mục',
            'uri' => 'admin.product-category.index',
            'icon' => 'bi bi-circle',
            'type' => 0,
            'sort' => 0,
            'hidden' => 0,
        ]);

        $adminMenuReflection = new \ReflectionClass(AdminMenu::class);
        $adminMenuCache = $adminMenuReflection->getProperty('getList');
        $adminMenuCache->setAccessible(true);
        $adminMenuCache->setValue(null);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.product-category.index'));

        $response->assertStatus(200);
        $response->assertSee($menuTitle, false);
        $response->assertSee('menu-open', false);

        $childProductCategory->delete();
        $childProduct->delete();
        $parent->delete();
        $admin->delete();
    }
}

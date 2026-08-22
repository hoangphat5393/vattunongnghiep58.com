<?php

namespace Tests\Feature;

use App\Models\Backend\Category;
use App\Models\Backend\Menu;
use App\Models\Backend\MenuItems;
use App\Models\Backend\Role;
use App\Models\Backend\User;
use Tests\TestCase;

class AdminMenuItemStoreTest extends TestCase
{
    private function createAdministrator(): User
    {
        $admin = User::create([
            'name' => 'Menu Admin',
            'username' => 'menu_admin_'.uniqid(),
            'email' => 'menu_admin_'.uniqid('', true).'@example.com',
            'password' => bcrypt('password'),
            'status' => 1,
        ]);

        $role = Role::firstOrCreate(['slug' => 'administrator'], ['name' => 'Administrator']);
        $admin->roles()->sync([$role->id]);

        return $admin;
    }

    public function test_admin_menu_builder_renders_adminlte_refactor_without_losing_core_selectors(): void
    {
        $admin = $this->createAdministrator();

        $menu = Menu::create([
            'name' => 'menu-ui-test-'.uniqid('', true),
        ]);

        $menuItem = MenuItems::create([
            'menu_id' => $menu->id,
            'label' => 'UI Test Item',
            'link' => '/ui-test',
            'parent' => 0,
            'sort' => 0,
            'depth' => 0,
        ]);

        $category = Category::create([
            'name' => 'Long Category Name For Menu Source Filter',
            'slug' => 'long-category-name-for-menu-source-filter-'.uniqid(),
            'parent' => 0,
            'sort' => 0,
            'status' => 1,
        ]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.menu.index', ['menu' => $menu->id]));

        $response->assertOk();
        $response->assertViewIs('backend.setting.menu');
        $response->assertSee('admin-menu-builder', false);
        $response->assertSee('menu-builder.css', false);
        $response->assertDontSee('laravel-menu/style.css', false);
        $response->assertSee('card card-primary card-outline', false);
        $response->assertSee('menu-item-data-db-id', false);
        $response->assertSee('menu-item-data-parent-id', false);
        $response->assertSee('id="update-nav-menu"', false);
        $response->assertSee('id="menu-to-edit"', false);
        $response->assertSee('add_custom_menu', false);
        $response->assertSee('menu-save', false);
        $response->assertSee('menu-source-filter', false);
        $response->assertSee('menu-source-list', false);
        $response->assertSee('menu-source-item', false);
        $response->assertSee('menu-source-title', false);
        $response->assertSee('item-name-'.$category->id, false);
        $response->assertSee('item-slug-'.$category->id, false);
        $response->assertSee('item-url-'.$category->id, false);
        $response->assertSee('item-type-'.$category->id, false);

        MenuItems::where('menu_id', $menu->id)->delete();
        $category->delete();
        $menu->delete();
        $admin->delete();
    }

    public function test_menu_zero_renders_create_state_without_edit_actions(): void
    {
        $admin = $this->createAdministrator();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.menu.index', ['menu' => 0]));

        $response->assertOk();
        $response->assertSee('Khởi Tạo Menu', false);
        $response->assertSee('is-create-menu', false);
        $response->assertSee('Tạo Menu', false);
        $response->assertDontSee('Cấu Trúc Menu', false);
        $response->assertDontSee('id="menu-settings-column"', false);

        $admin->delete();
    }

    public function test_administrator_can_store_custom_menu_item_with_slugmenu_payload(): void
    {
        $role = Role::firstOrCreate(['slug' => 'administrator'], ['name' => 'Administrator']);

        $admin = User::create([
            'name' => 'Menu Admin',
            'username' => 'menu_admin_'.uniqid(),
            'email' => 'menu_admin_'.uniqid('', true).'@example.com',
            'password' => bcrypt('password'),
            'status' => 1,
        ]);
        $admin->roles()->sync([$role->id]);

        $menu = Menu::create([
            'name' => 'menu-test-'.uniqid('', true),
        ]);

        $response = $this->actingAs($admin, 'admin')->post(
            route('admin.menu.menuItem.store', ['menu' => $menu->id]),
            [
                'labelmenu' => 'Custom Label',
                'slugmenu' => 'custom-slug',
                'linkmenu' => 'https://example.com/page',
                'targetmenu' => '_blank',
                'relmenu' => 'nofollow',
            ]
        );

        $response->assertOk();
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('menu_items', [
            'menu_id' => $menu->id,
            'label' => 'Custom Label',
            'slug' => 'custom-slug',
            'link' => 'https://example.com/page',
            'target' => '_blank',
            'rel' => 'nofollow',
        ]);

        MenuItems::where('menu_id', $menu->id)->delete();
        $menu->delete();
        $admin->delete();
    }

    public function test_administrator_can_save_menu_item_tree_structure(): void
    {
        $admin = $this->createAdministrator();

        $menu = Menu::create([
            'name' => 'menu-tree-test-'.uniqid('', true),
        ]);

        $parent = MenuItems::create([
            'menu_id' => $menu->id,
            'label' => 'Parent Item',
            'link' => '/',
            'parent' => 0,
            'sort' => 0,
            'depth' => 0,
        ]);

        $child = MenuItems::create([
            'menu_id' => $menu->id,
            'label' => 'Child Item',
            'link' => '/child',
            'parent' => 0,
            'sort' => 1,
            'depth' => 0,
        ]);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.menu.generate'), [
            'idmenu' => $menu->id,
            'menuname' => 'Updated Tree Menu',
            'arraydata' => [
                [
                    'id' => $parent->id,
                    'parent' => 0,
                    'sort' => 0,
                    'depth' => 0,
                ],
                [
                    'id' => $child->id,
                    'parent' => $parent->id,
                    'sort' => 1,
                    'depth' => 1,
                ],
            ],
        ]);

        $response->assertOk();
        $response->assertJson(['resp' => 1]);

        $this->assertDatabaseHas('menus', [
            'id' => $menu->id,
            'name' => 'Updated Tree Menu',
        ]);

        $this->assertDatabaseHas('menu_items', [
            'id' => $parent->id,
            'menu_id' => $menu->id,
            'parent' => 0,
            'sort' => 0,
            'depth' => 0,
        ]);

        $this->assertDatabaseHas('menu_items', [
            'id' => $child->id,
            'menu_id' => $menu->id,
            'parent' => $parent->id,
            'sort' => 1,
            'depth' => 1,
        ]);

        MenuItems::where('menu_id', $menu->id)->delete();
        $menu->delete();
        $admin->delete();
    }

    public function test_administrator_can_update_menu_item_target_and_rel(): void
    {
        $admin = $this->createAdministrator();

        $menu = Menu::create([
            'name' => 'menu-item-update-test-'.uniqid('', true),
        ]);

        $item = MenuItems::create([
            'menu_id' => $menu->id,
            'label' => 'Old Label',
            'slug' => 'old-slug',
            'link' => '/old',
        ]);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.menu.menuItem.update', ['menu' => $menu->id]), [
            'id' => $item->id,
            'label' => 'New Label',
            'image' => 'new-image.jpg',
            'slug' => 'new-slug',
            'url' => '/new',
            'class' => 'new-class',
            'target' => '_blank',
            'rel' => 'noopener',
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('menu_items', [
            'id' => $item->id,
            'menu_id' => $menu->id,
            'label' => 'New Label',
            'image' => 'new-image.jpg',
            'slug' => 'new-slug',
            'link' => '/new',
            'class' => 'new-class',
            'target' => '_blank',
            'rel' => 'noopener',
        ]);

        MenuItems::where('menu_id', $menu->id)->delete();
        $menu->delete();
        $admin->delete();
    }

    public function test_generate_menu_returns_not_found_for_missing_menu(): void
    {
        $admin = $this->createAdministrator();

        $response = $this->actingAs($admin, 'admin')->post(route('admin.menu.generate'), [
            'idmenu' => 999999999,
            'menuname' => 'Ghost Menu',
            'arraydata' => [],
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['idmenu']);

        $admin->delete();
    }

    public function test_administrator_cannot_delete_menu_item_from_another_menu(): void
    {
        $admin = $this->createAdministrator();

        $menu = Menu::create([
            'name' => 'menu-delete-test-'.uniqid('', true),
        ]);

        $otherMenu = Menu::create([
            'name' => 'other-menu-delete-test-'.uniqid('', true),
        ]);

        $otherItem = MenuItems::create([
            'menu_id' => $otherMenu->id,
            'label' => 'Other Menu Item',
            'link' => '/other',
        ]);

        $response = $this->actingAs($admin, 'admin')->delete(route('admin.menu.menuItem.destroy', [
            'menu' => $menu->id,
            'menuitems' => $otherItem->id,
        ]));

        $response->assertNotFound();

        $this->assertDatabaseHas('menu_items', [
            'id' => $otherItem->id,
            'menu_id' => $otherMenu->id,
        ]);

        MenuItems::whereIn('menu_id', [$menu->id, $otherMenu->id])->delete();
        $menu->delete();
        $otherMenu->delete();
        $admin->delete();
    }
}

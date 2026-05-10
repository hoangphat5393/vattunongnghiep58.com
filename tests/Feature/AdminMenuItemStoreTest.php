<?php

namespace Tests\Feature;

use App\Models\Backend\Menu;
use App\Models\Backend\MenuItems;
use App\Models\Backend\Role;
use App\Models\Backend\User;
use Tests\TestCase;

class AdminMenuItemStoreTest extends TestCase
{
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
            ]
        );

        $response->assertOk();
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('menu_items', [
            'menu_id' => $menu->id,
            'label' => 'Custom Label',
            'slug' => 'custom-slug',
            'link' => 'https://example.com/page',
        ]);

        MenuItems::where('menu_id', $menu->id)->delete();
        $menu->delete();
        $admin->delete();
    }
}

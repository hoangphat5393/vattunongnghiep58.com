<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RouteAliasTest extends TestCase
{
    public function test_cart_remove_routes_use_new_name_and_keep_legacy_alias(): void
    {
        $this->assertTrue(Route::has('cart.remove-item'));
        $this->assertTrue(Route::has('cart.ajax.remove'));

        $newRoute = Route::getRoutes()->getByName('cart.remove-item');
        $legacyRoute = Route::getRoutes()->getByName('cart.ajax.remove');

        $this->assertNotNull($newRoute);
        $this->assertNotNull($legacyRoute);
        $this->assertStringEndsWith('cart/remove-item', $newRoute->uri());
        $this->assertStringEndsWith('cart/ajax/remove', $legacyRoute->uri());
    }

    public function test_admin_bulk_routes_use_new_names_and_keep_legacy_aliases(): void
    {
        $this->assertTrue(Route::has('admin.bulk.delete'));
        $this->assertTrue(Route::has('admin.ajax_delete'));
        $this->assertTrue(Route::has('admin.bulk.replicate'));
        $this->assertTrue(Route::has('admin.ajax_replicate'));
        $this->assertTrue(Route::has('admin.quick-change'));

        $bulkDelete = Route::getRoutes()->getByName('admin.bulk.delete');
        $legacyDelete = Route::getRoutes()->getByName('admin.ajax_delete');

        $this->assertNotNull($bulkDelete);
        $this->assertNotNull($legacyDelete);
        $this->assertStringEndsWith('admin/bulk-delete', $bulkDelete->uri());
        $this->assertStringEndsWith('admin/delete-id', $legacyDelete->uri());
    }

    public function test_admin_sort_routes_have_clean_names_and_legacy_aliases(): void
    {
        $this->assertTrue(Route::has('admin.albumItem.update_sort'));
        $this->assertTrue(Route::has('admin.albumItem.ajax_update_sort'));
        $this->assertTrue(Route::has('admin.theme-option.update_sort'));
        $this->assertTrue(Route::has('admin.theme-option.ajax_update_sort'));
    }
}

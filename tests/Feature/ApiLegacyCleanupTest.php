<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ApiLegacyCleanupTest extends TestCase
{
    public function test_legacy_api_controller_is_removed(): void
    {
        $this->assertFileDoesNotExist(app_path('Http/Controllers/ApiController.php'));
    }

    public function test_legacy_api_route_names_are_not_registered(): void
    {
        $this->assertFalse(Route::has('slide.api'));
        $this->assertFalse(Route::has('products.api'));
        $this->assertFalse(Route::has('category-products.api'));
    }

    public function test_legacy_api_paths_return_not_found(): void
    {
        $this->get('/api/slider')->assertNotFound();
        $this->get('/api/products')->assertNotFound();
        $this->get('/api/category-products')->assertNotFound();
    }
}

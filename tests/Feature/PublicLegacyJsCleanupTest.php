<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicLegacyJsCleanupTest extends TestCase
{
    public function test_duplicate_public_custom_js_is_removed(): void
    {
        $this->assertFileDoesNotExist(public_path('assets/js/custom.js'));
    }

    public function test_legacy_webpack_app_js_bundle_is_removed(): void
    {
        $this->assertFileDoesNotExist(public_path('assets/js/app.js'));
    }

    public function test_frontend_layout_uses_vite_not_legacy_public_js(): void
    {
        $master = file_get_contents(resource_path('views/frontend/layouts/master.blade.php'));

        $this->assertIsString($master);
        $this->assertStringContainsString("@vite(['resources/css/app.css', 'resources/scss/style.scss', 'resources/js/app.js'])", $master);
        $this->assertStringNotContainsString("asset('assets/js/app.js')", $master);
        $this->assertStringNotContainsString("asset('assets/js/custom.js')", $master);
        $this->assertStringNotContainsString("asset('assets/js/main.js')", $master);
    }

    public function test_legacy_template_main_js_is_removed(): void
    {
        $this->assertFileDoesNotExist(public_path('assets/js/main.js'));
    }

    public function test_login_main_js_remains_separate_from_legacy_template_main(): void
    {
        $this->assertFileExists(public_path('assets/login/js/main.js'));
    }

    public function test_vite_manifest_still_has_frontend_app_entry(): void
    {
        $manifestPath = public_path('build/manifest.json');

        $this->assertFileExists($manifestPath);

        $manifest = json_decode((string) file_get_contents($manifestPath), true);

        $this->assertIsArray($manifest);
        $this->assertArrayHasKey('resources/js/app.js', $manifest);
    }

    public function test_active_public_js_assets_remain_for_admin(): void
    {
        $this->assertFileExists(public_path('assets/js/jquery-3.7.1.min.js'));
        $this->assertFileExists(public_path('assets/js/js_admin.js'));
    }

    public function test_menu_no_delete_js_is_removed(): void
    {
        $this->assertFileDoesNotExist(public_path('assets/laravel-menu/menu_no_delete.js'));
    }
}

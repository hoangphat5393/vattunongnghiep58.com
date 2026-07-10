<?php

namespace Tests\Feature;

use Tests\TestCase;

class DeadVueComponentsCleanupTest extends TestCase
{
    /** @var list<string> */
    private array $removedComponents = [
        'Load_Product.vue',
        'Flash_Home.vue',
        'Pagination.vue',
        'Search_Vue.vue',
        'Slider_Ajax.vue',
        'Offer_Home.vue',
        'Selling_Home.vue',
        'Product.vue',
    ];

    public function test_dead_vue_components_directory_has_no_vue_files(): void
    {
        $componentsPath = resource_path('js/components');

        if (! is_dir($componentsPath)) {
            $this->assertTrue(true);

            return;
        }

        $vueFiles = glob($componentsPath.'/*.vue') ?: [];

        $this->assertSame([], $vueFiles);
    }

    public function test_removed_vue_component_files_do_not_exist(): void
    {
        foreach ($this->removedComponents as $filename) {
            $this->assertFileDoesNotExist(resource_path('js/components/'.$filename));
        }
    }

    public function test_vite_entry_does_not_import_vue_components(): void
    {
        $appJs = file_get_contents(resource_path('js/app.js'));

        $this->assertIsString($appJs);
        $this->assertStringNotContainsString('.vue', $appJs);
        $this->assertStringNotContainsString('components/', $appJs);
    }

    public function test_package_json_does_not_depend_on_vue(): void
    {
        $packageJson = json_decode((string) file_get_contents(base_path('package.json')), true);

        $this->assertIsArray($packageJson);

        $dependencies = array_merge(
            $packageJson['dependencies'] ?? [],
            $packageJson['devDependencies'] ?? [],
        );

        $this->assertArrayNotHasKey('vue', $dependencies);
        $this->assertArrayNotHasKey('@vitejs/plugin-vue', $dependencies);
    }
}

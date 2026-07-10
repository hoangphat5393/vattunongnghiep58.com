<?php

namespace Tests\Feature;

use App\Models\Backend\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProductCategoryCategoryTreeViewsTest extends TestCase
{
    /**
     * @return array{childrenMap: Collection, root: Collection}
     */
    private function seedFourLevelCategoryTree(): array
    {
        $level1 = Category::create([
            'name' => 'Root',
            'slug' => 'root',
            'status' => 1,
            'parent' => 0,
            'sort' => 1,
        ]);

        $level2 = Category::create([
            'name' => 'Child',
            'slug' => 'child',
            'status' => 1,
            'parent' => $level1->id,
            'sort' => 1,
        ]);

        $level3 = Category::create([
            'name' => 'Grandchild',
            'slug' => 'grandchild',
            'status' => 1,
            'parent' => $level2->id,
            'sort' => 1,
        ]);

        Category::create([
            'name' => 'Great Grandchild',
            'slug' => 'great-grandchild',
            'status' => 1,
            'parent' => $level3->id,
            'sort' => 1,
        ]);

        $childrenMap = Category::query()->orderBy('sort')->get()->groupBy('parent');

        return [
            'childrenMap' => $childrenMap,
            'root' => $childrenMap->get(0, collect()),
        ];
    }

    public function test_product_category_tree_views_render_without_errors(): void
    {
        $tree = $this->seedFourLevelCategoryTree();

        $selectHtml = view('backend.product-category.includes.select-category', [
            'parent' => 0,
            'childrenMap' => $tree['childrenMap'],
        ])->render();

        $this->assertStringContainsString('<select', $selectHtml);
        $this->assertStringContainsString('Great Grandchild', $selectHtml);

        $categoryRowsHtml = view('backend.product-category.includes.category_item', [
            'categories' => $tree['root'],
            'level' => 0,
            'childrenMap' => $tree['childrenMap'],
        ])->render();

        $this->assertStringContainsString('item-level-0', $categoryRowsHtml);
        $this->assertStringContainsString('item-level-3', $categoryRowsHtml);
        $this->assertStringContainsString('Great Grandchild', $categoryRowsHtml);

        $categoryCheckboxTreeHtml = view('backend.partials.category-item', [
            'categories' => $tree['root'],
            'level' => 0,
            'array_checked' => [],
            'category_type' => null,
            'childrenMap' => $tree['childrenMap'],
        ])->render();

        $this->assertStringContainsString('category_menu_list', $categoryCheckboxTreeHtml);
        $this->assertStringContainsString('Great Grandchild', $categoryCheckboxTreeHtml);
    }

    public function test_category_tree_views_do_not_query_database_during_render(): void
    {
        $tree = $this->seedFourLevelCategoryTree();

        DB::enableQueryLog();

        view('backend.product-category.includes.category_item', [
            'categories' => $tree['root'],
            'level' => 0,
            'childrenMap' => $tree['childrenMap'],
        ])->render();

        view('backend.product-category.includes.select-category', [
            'parent' => 0,
            'childrenMap' => $tree['childrenMap'],
        ])->render();

        view('backend.partials.category-item', [
            'categories' => $tree['root'],
            'level' => 0,
            'array_checked' => [],
            'childrenMap' => $tree['childrenMap'],
        ])->render();

        $this->assertCount(0, DB::getQueryLog());
    }

    public function test_top_pagination_is_hidden_in_list_views(): void
    {
        $files = [
            base_path('resources/views/backend/product-category/index.blade.php') => '{!! $categories->links() !!}',
            base_path('resources/views/backend/post/index.blade.php') => '{!! $data->links() !!}',
            base_path('resources/views/backend/orders/filter.blade.php') => '{!! $data_order->links() !!}',
            base_path('resources/views/backend/product/filter.blade.php') => '{!! $data_product->appends(request()->except(\'page\'))->links() !!}',
        ];

        foreach ($files as $path => $topLinksCall) {
            $contents = file_get_contents($path);
            $this->assertNotFalse($contents);

            $pos = strpos($contents, $topLinksCall);
            $this->assertNotFalse($pos);

            $contextStart = max(0, $pos - 200);
            $context = substr($contents, $contextStart, 200);

            $this->assertStringContainsString('@if (false)', $context);
        }
    }

    public function test_backend_list_views_follow_page_list_ui_structure(): void
    {
        $files = [
            base_path('resources/views/backend/page/index.blade.php'),
            base_path('resources/views/backend/product/index.blade.php'),
            base_path('resources/views/backend/product-category/index.blade.php'),
            base_path('resources/views/backend/post/index.blade.php'),
            base_path('resources/views/backend/album/index.blade.php'),
            base_path('resources/views/backend/orders/index.blade.php'),
            base_path('resources/views/backend/orders/filter.blade.php'),
            base_path('resources/views/backend/product/filter.blade.php'),
            base_path('resources/views/backend/user/index.blade.php'),
            base_path('resources/views/backend/email-template/index.blade.php'),
            base_path('resources/views/backend/contact/index.blade.php'),
            base_path('resources/views/backend/role/index.blade.php'),
            base_path('resources/views/backend/permission/index.blade.php'),
        ];

        foreach ($files as $path) {
            $contents = file_get_contents($path);
            $this->assertNotFalse($contents);

            $this->assertStringContainsString('app-content-header', $contents);
            $this->assertStringContainsString('h1 class="mb-0"', $contents);
            $this->assertStringContainsString('card-primary card-outline', $contents);
            $this->assertStringContainsString("links('backend.pagination.custom')", $contents);
        }
    }

    public function test_backend_single_views_follow_page_create_ui_structure(): void
    {
        $structureFiles = [
            base_path('resources/views/backend/page/single.blade.php'),
            base_path('resources/views/backend/product/single.blade.php'),
            base_path('resources/views/backend/product-category/single.blade.php'),
            base_path('resources/views/backend/post/single.blade.php'),
            base_path('resources/views/backend/album/single.blade.php'),
            base_path('resources/views/backend/orders/single.blade.php'),
            base_path('resources/views/backend/user/single.blade.php'),
            base_path('resources/views/backend/email-template/single.blade.php'),
            base_path('resources/views/backend/contact/single.blade.php'),
            base_path('resources/views/backend/role/single.blade.php'),
            base_path('resources/views/backend/permission/single.blade.php'),
        ];

        foreach ($structureFiles as $path) {
            $contents = file_get_contents($path);
            $this->assertNotFalse($contents);

            $this->assertStringContainsString('app-content-header', $contents);
            $this->assertStringContainsString('h1 class="mb-0"', $contents);
            $this->assertStringContainsString('card-primary card-outline', $contents);
        }

        $formLabelFiles = [
            base_path('resources/views/backend/page/single.blade.php'),
            base_path('resources/views/backend/product/single.blade.php'),
            base_path('resources/views/backend/product-category/single.blade.php'),
            base_path('resources/views/backend/post/single.blade.php'),
            base_path('resources/views/backend/album/single.blade.php'),
            base_path('resources/views/backend/user/single.blade.php'),
            base_path('resources/views/backend/email-template/single.blade.php'),
            base_path('resources/views/backend/contact/single.blade.php'),
            base_path('resources/views/backend/role/single.blade.php'),
            base_path('resources/views/backend/permission/single.blade.php'),
        ];

        foreach ($formLabelFiles as $path) {
            $contents = file_get_contents($path);
            $this->assertNotFalse($contents);

            $this->assertStringContainsString('form-label', $contents);
        }
    }

    public function test_backend_category_tree_views_do_not_query_children_in_blade(): void
    {
        $bladeFiles = [
            base_path('resources/views/backend/product-category/includes/select-category.blade.php'),
            base_path('resources/views/backend/product-category/includes/category_item.blade.php'),
            base_path('resources/views/backend/partials/category-item.blade.php'),
            base_path('resources/views/backend/setting/includes/category_items.blade.php'),
        ];

        foreach ($bladeFiles as $path) {
            $contents = file_get_contents($path);
            $this->assertNotFalse($contents);

            $this->assertStringNotContainsString('children()->', $contents);
            $this->assertStringNotContainsString('children()', $contents);
        }

        $selectContents = file_get_contents(
            base_path('resources/views/backend/product-category/includes/select-category.blade.php')
        );
        $this->assertNotFalse($selectContents);

        $this->assertStringNotContainsString('App\\Models\\Backend\\Category::where', $selectContents);

        $partialContents = file_get_contents(
            base_path('resources/views/backend/partials/category-item.blade.php')
        );
        $this->assertNotFalse($partialContents);

        $this->assertStringNotContainsString('App\\Models\\Backend\\Category::where', $partialContents);

        $settingCategoryContents = file_get_contents(
            base_path('resources/views/backend/setting/includes/category_items.blade.php')
        );
        $this->assertNotFalse($settingCategoryContents);

        $this->assertStringNotContainsString('App\\Models\\Backend\\Category::where', $settingCategoryContents);
    }

    public function test_permission_index_does_not_override_permissions_paginator_variable(): void
    {
        $path = base_path('resources/views/backend/permission/index.blade.php');
        $contents = file_get_contents($path);
        $this->assertNotFalse($contents);

        $this->assertStringNotContainsString('$permissions =', $contents);
        $this->assertStringNotContainsString('{!! $permissions !!}', $contents);
    }
}

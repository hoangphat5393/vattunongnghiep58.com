<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class FrontendPagesTest extends TestCase
{
    public function test_home_page_is_accessible()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_frontend_layout_has_single_main_wrapper_and_home_inner_is_div(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $html = $response->getContent();

        $this->assertStringContainsString('<main id="app"', $html);
        $this->assertStringContainsString('<div id="main"', $html);
        $this->assertStringNotContainsString('<main id="main"', $html);

        $mainTagCount = preg_match_all('/<main\s/', $html);
        $this->assertSame(1, $mainTagCount, 'Expected exactly one <main> (layout wrapper only).');
    }

    public function test_mobile_menu_fixed_layers_render_after_header_element(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $html = $response->getContent();

        $this->assertMatchesRegularExpression(
            '#</header>\s*<div id="mobile-menu-overlay"#',
            $html,
            'Mobile overlay must immediately follow </header> (outside backdrop-blur stacking context).'
        );

        $this->assertStringContainsString(
            'id="close-menu-btn" class="rounded-md border-0 bg-transparent',
            $html,
            'Mobile drawer close control should reset native button chrome (match header markup).'
        );

        $appCss = file_get_contents(resource_path('css/app.css'));

        $this->assertStringContainsString('#mobile-menu-overlay', $appCss);
        $this->assertStringContainsString('z-index: 99998', $appCss);
        $this->assertStringContainsString('#mobile-menu', $appCss);
        $this->assertStringContainsString('z-index: 99999', $appCss);
    }

    public function test_product_list_page_is_accessible()
    {
        $response = $this->get(route('product'));

        $response->assertStatus(200);
    }

    public function test_news_list_page_is_accessible()
    {
        $response = $this->get(route('news'));

        $response->assertStatus(200);
    }

    public function test_contact_page_is_accessible()
    {
        $response = $this->get('/contact');

        $response->assertStatus(200);
    }

    public function test_header_renders_two_level_menu_when_menu_main_has_children(): void
    {
        if (config('database.default') !== 'sqlite') {
            $this->markTestSkipped('Requires sqlite in-memory schema from TestCase.');
        }

        $childLabel = 'Đất trồng — submenu test';

        DB::table('menus')->updateOrInsert(
            ['name' => 'Menu-main'],
            ['title' => 'Main', 'created_at' => now(), 'updated_at' => now()]
        );

        $menuId = (int) DB::table('menus')->where('name', 'Menu-main')->value('id');

        DB::table('menu_items')->where('menu_id', $menuId)->delete();

        $parentId = DB::table('menu_items')->insertGetId([
            'menu_id' => $menuId,
            'slug' => null,
            'label' => 'Sản phẩm',
            'link' => url('/product'),
            'image' => null,
            'parent' => 0,
            'sort' => 1,
            'class' => null,
            'depth' => 0,
            'rel' => null,
            'target' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('menu_items')->insert([
            'menu_id' => $menuId,
            'slug' => null,
            'label' => $childLabel,
            'link' => url('/product') . '?cat=test',
            'image' => null,
            'parent' => $parentId,
            'sort' => 1,
            'class' => null,
            'depth' => 1,
            'rel' => null,
            'target' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);

        $html = $response->getContent();

        $this->assertStringContainsString('data-nav-dropdown', $html);
        $this->assertStringContainsString('nav-mobile-details', $html);
        $this->assertStringContainsString($childLabel, $html);
        $this->assertStringContainsString('nav-sub-', $html);
    }
}

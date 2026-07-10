<?php

namespace Tests\Feature;

use Tests\TestCase;

class CheckoutLegacyCleanupTest extends TestCase
{
    public function test_checkout_process_redirects_to_modern_checkout(): void
    {
        $response = $this->post(route('cart_checkout.process'));

        $response->assertRedirect(route('cart.checkout'));
    }

    public function test_quick_buy_confirm_routes_redirect_to_checkout(): void
    {
        $this->get(route('quick_buy.get.confirm'))
            ->assertRedirect(route('cart.checkout'));

        $this->post(route('quick_buy.checkout.confirm'))
            ->assertRedirect(route('cart.checkout'));
    }

    public function test_cart_page_loads(): void
    {
        $this->get(route('cart'))
            ->assertStatus(200);
    }

    public function test_empty_cart_checkout_shows_cart_page(): void
    {
        $this->get(route('cart.checkout'))
            ->assertOk()
            ->assertViewIs('frontend.cart.cart');
    }

    public function test_legacy_quick_buy_blades_are_removed(): void
    {
        $legacyBlades = [
            'resources/views/frontend/cart/quick-buy.blade.php',
            'resources/views/frontend/cart/quick-buy-confirm.blade.php',
            'resources/views/frontend/cart/quick-buy-list.blade.php',
            'resources/views/frontend/cart/cart-confirm.blade.php',
            'resources/views/frontend/cart/cart-confirm-list.blade.php',
            'resources/views/frontend/cart/includes/legacy-checkout-redirect.blade.php',
        ];

        foreach ($legacyBlades as $blade) {
            $this->assertFileDoesNotExist(base_path($blade), $blade);
        }
    }

    public function test_legacy_scss_and_bootstrap_js_are_removed(): void
    {
        $this->assertFileDoesNotExist(resource_path('scss/app.scss'));
        $this->assertFileDoesNotExist(resource_path('scss/_variables.scss'));
        $this->assertFileDoesNotExist(resource_path('js/bootstrap.js'));
    }

    public function test_unused_home_includes_are_removed(): void
    {
        $unusedIncludes = [
            'hero_section.blade.php',
            'hot_slider.blade.php',
            'partner.blade.php',
            'collection.blade.php',
            'counter.blade.php',
            'main-category.blade.php',
            'subscribe.blade.php',
            'services.blade.php',
            'categories_sidebar.blade.php',
        ];

        foreach ($unusedIncludes as $include) {
            $this->assertFileDoesNotExist(resource_path('views/frontend/includes/'.$include), $include);
        }
    }
}

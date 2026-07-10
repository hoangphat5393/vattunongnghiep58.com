<?php

namespace Tests\Feature;

use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Auth\CustomerAuthController;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class CustomerAccountRoutesTest extends TestCase
{
    public function test_customer_auth_routes_are_registered(): void
    {
        $this->assertTrue(Route::has('customer.login'));
        $this->assertTrue(Route::has('customer.login.submit'));
        $this->assertTrue(Route::has('customer.register'));
        $this->assertTrue(Route::has('customer.register.submit'));
        $this->assertTrue(Route::has('customer.logout'));
        $this->assertTrue(Route::has('customer.password.forgot'));
        $this->assertTrue(Route::has('customer.orders.index'));
        $this->assertTrue(Route::has('customer.orders.show'));
        $this->assertTrue(Route::has('customer.password.edit'));
    }

    public function test_customer_account_routes_use_refactored_controllers(): void
    {
        $this->assertRouteUsesController('customer.login', CustomerAuthController::class.'@showLoginForm');
        $this->assertRouteUsesController('customer.login.submit', CustomerAuthController::class.'@postLogin');
        $this->assertRouteUsesController('customer.register', CustomerAuthController::class.'@registerCustomer');
        $this->assertRouteUsesController('customer.logout', CustomerAuthController::class.'@logoutCustomer');
        $this->assertRouteUsesController('customer.profile', AccountController::class.'@profile');
        $this->assertRouteUsesController('customer.orders.index', AccountController::class.'@myOrder');
        $this->assertRouteUsesController('customer.password.edit', AccountController::class.'@changePassword');
    }

    /**
     * @param  class-string  $expectedAction
     */
    private function assertRouteUsesController(string $routeName, string $expectedAction): void
    {
        $route = Route::getRoutes()->getByName($routeName);
        $this->assertNotNull($route);
        $this->assertSame($expectedAction, $route->getAction('controller'));
    }

    public function test_legacy_customer_urls_redirect_to_account_urls(): void
    {
        $this->get('/customer/my-orders')
            ->assertRedirect('/account/orders');

        $this->get('/customer/thong-tin')
            ->assertRedirect('/account/profile');

        $this->get('/customer/change-pass')
            ->assertRedirect('/account/password');

        $this->get('/forget/password')
            ->assertRedirect('/auth/forgot-password');
    }

    public function test_guest_account_route_redirects_to_customer_login(): void
    {
        $this->get(route('customer.dashboard'))
            ->assertRedirect(route('customer.login'));
    }
}

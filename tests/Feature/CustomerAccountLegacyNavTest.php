<?php

namespace Tests\Feature;

use App\Http\Middleware\Currency;
use App\Models\Frontend\User;
use Tests\TestCase;

class CustomerAccountLegacyNavTest extends TestCase
{
    /**
     * @return array<int, string>
     */
    private function legacyAccountPaths(): array
    {
        return [
            '/account/messages',
            '/account/quan-ly-tin-dang',
            '/account/payment-point',
            '/account/my-reviews',
        ];
    }

    private function createActiveUser(): User
    {
        return User::create([
            'fullname' => 'Legacy Nav User',
            'email' => 'legacy-nav@example.com',
            'password' => bcrypt('secret123'),
            'status' => 1,
        ]);
    }

    public function test_account_pages_do_not_link_to_legacy_sidebar_routes(): void
    {
        $this->withoutMiddleware(Currency::class);

        $user = $this->createActiveUser();

        $pages = [
            route('customer.profile'),
            route('customer.orders.index'),
            route('customer.password.edit'),
        ];

        foreach ($pages as $url) {
            $response = $this->actingAs($user)->get($url);

            $response->assertOk();

            foreach ($this->legacyAccountPaths() as $legacyPath) {
                $response->assertDontSee($legacyPath, false);
            }

            $response->assertDontSee('Danh sách yêu thích', false);
            $response->assertDontSee('Messages', false);
        }
    }

    public function test_account_nav_shows_core_items_only(): void
    {
        $this->withoutMiddleware(Currency::class);

        $user = $this->createActiveUser();

        $response = $this->actingAs($user)->get(route('customer.profile'));

        $response->assertOk();
        $response->assertSee('Thông tin cá nhân', false);
        $response->assertSee('Đơn hàng của tôi', false);
        $response->assertSee('Đổi mật khẩu', false);
        $response->assertSee('Đăng xuất', false);
        $response->assertSee(route('customer.profile'), false);
        $response->assertSee(route('customer.orders.index'), false);
        $response->assertSee(route('customer.password.edit'), false);
    }
}

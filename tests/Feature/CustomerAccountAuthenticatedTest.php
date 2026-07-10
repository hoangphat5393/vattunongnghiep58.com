<?php

namespace Tests\Feature;

use App\Http\Middleware\Currency;
use App\Models\Addtocard;
use App\Models\Frontend\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CustomerAccountAuthenticatedTest extends TestCase
{
    private function createActiveUser(array $overrides = []): User
    {
        return User::create(array_merge([
            'fullname' => 'Test Customer',
            'email' => 'customer@example.com',
            'password' => bcrypt('secret123'),
            'phone' => '0900000000',
            'address' => '123 Test Street',
            'status' => 1,
        ], $overrides));
    }

    public function test_guest_is_redirected_from_account_pages(): void
    {
        $this->withoutMiddleware(Currency::class);

        $this->get(route('customer.profile'))->assertRedirect();
        $this->get(route('customer.orders.index'))->assertRedirect();
        $this->get(route('customer.password.edit'))->assertRedirect();
    }

    public function test_authenticated_user_can_view_profile_page(): void
    {
        $this->withoutMiddleware(Currency::class);

        $user = $this->createActiveUser();

        $this->actingAs($user)
            ->get(route('customer.profile'))
            ->assertOk()
            ->assertSee('Thông tin cá nhân', false)
            ->assertSee($user->email, false);
    }

    public function test_authenticated_user_can_update_profile(): void
    {
        $this->withoutMiddleware(Currency::class);

        $user = $this->createActiveUser();

        $this->actingAs($user)
            ->post(route('customer.profile.update'), [
                'fullname' => 'Updated Name',
                'phone' => '0911111111',
                'address' => 'New address',
            ])
            ->assertRedirect(route('customer.profile'));

        $user->refresh();

        $this->assertSame('Updated Name', $user->fullname);
        $this->assertSame('0911111111', $user->phone);
        $this->assertSame('New address', $user->address);
    }

    public function test_authenticated_user_can_view_orders_list(): void
    {
        $this->withoutMiddleware(Currency::class);

        $user = $this->createActiveUser(['email' => 'orders@example.com']);

        Addtocard::create([
            'user_id' => $user->id,
            'name' => 'Test Customer',
            'cart_email' => 'orders@example.com',
            'cart_code' => 'ORD-P1-001',
            'cart_status' => 0,
            'cart_total' => 250000,
        ]);

        $this->actingAs($user)
            ->get(route('customer.orders.index'))
            ->assertOk()
            ->assertSee('Đơn hàng của tôi', false)
            ->assertSee('ORD-P1-001', false);
    }

    public function test_authenticated_user_can_view_change_password_page(): void
    {
        $this->withoutMiddleware(Currency::class);

        $user = $this->createActiveUser(['email' => 'password@example.com']);

        $this->actingAs($user)
            ->get(route('customer.password.edit'))
            ->assertOk()
            ->assertSee('Đổi mật khẩu', false)
            ->assertSee(route('customer.password.update'), false);
    }

    public function test_authenticated_user_can_change_password(): void
    {
        $this->withoutMiddleware(Currency::class);

        $user = $this->createActiveUser(['email' => 'changepw@example.com']);

        $this->actingAs($user)
            ->post(route('customer.password.update'), [
                'current_password' => 'secret123',
                'new_password' => 'newsecret456',
                'confirm_password' => 'newsecret456',
            ])
            ->assertRedirect(route('customer.password.edit'));

        $user->refresh();

        $this->assertTrue(Hash::check('newsecret456', $user->password));
    }

    public function test_header_shows_account_links_when_authenticated(): void
    {
        $this->withoutMiddleware(Currency::class);

        $user = $this->createActiveUser(['email' => 'header@example.com']);

        $this->actingAs($user)
            ->get(route('index'))
            ->assertOk()
            ->assertSee('Tài khoản', false)
            ->assertSee('Đơn hàng', false)
            ->assertSee('Đăng xuất', false);
    }

    public function test_header_shows_login_register_when_guest(): void
    {
        $this->withoutMiddleware(Currency::class);

        $this->get(route('index'))
            ->assertOk()
            ->assertSee('Đăng nhập', false)
            ->assertSee('Đăng ký', false);
    }

    public function test_customer_can_logout(): void
    {
        $this->withoutMiddleware(Currency::class);

        $user = $this->createActiveUser(['email' => 'logout@example.com']);

        $this->actingAs($user)
            ->post(route('customer.logout'))
            ->assertRedirect();

        $this->assertGuest();
    }
}

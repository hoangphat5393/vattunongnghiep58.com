<?php

namespace Tests\Feature;

use App\Models\Frontend\User;
use Tests\TestCase;

class CustomerAccountViewTest extends TestCase
{
    public function test_login_page_is_accessible(): void
    {
        $response = $this->get(route('customer.login'));

        $response->assertOk();
        $response->assertSee('id="form-login-page"', false);
        $response->assertSee(route('customer.login.submit'), false);
    }

    public function test_register_page_is_accessible(): void
    {
        $response = $this->get(route('customer.register'));

        $response->assertOk();
        $response->assertSee('id="page-customer-register"', false);
        $response->assertSee(route('customer.register.submit'), false);
    }

    public function test_login_page_uses_frontend_layout(): void
    {
        $response = $this->get(route('customer.login'));

        $response->assertOk();
        $response->assertSee('<main id="app"', false);
    }

    public function test_forgot_password_page_is_accessible(): void
    {
        $response = $this->get(route('customer.password.forgot'));

        $response->assertOk();
        $response->assertSee('Quên mật khẩu', false);
        $response->assertSee(route('customer.password.forgot.submit'), false);
    }

    public function test_register_success_page_uses_frontend_layout(): void
    {
        $user = User::create([
            'fullname' => 'Nguyen Van A',
            'email' => 'register-success@example.com',
            'password' => bcrypt('secret123'),
            'status' => 1,
        ]);

        $response = $this->actingAs($user)->get(route('customer.register.success'));

        $response->assertOk();
        $response->assertSee('<main id="app"', false);
        $response->assertSee('Đăng ký thành công', false);
        $response->assertSee('Nguyen Van A', false);
        $response->assertSee(route('customer.dashboard'), false);
    }
}

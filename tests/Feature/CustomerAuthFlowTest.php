<?php

namespace Tests\Feature;

use App\Http\Middleware\Currency;
use App\Models\Frontend\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CustomerAuthFlowTest extends TestCase
{
    public function test_active_customer_can_login(): void
    {
        $this->withoutMiddleware(Currency::class);

        User::create([
            'fullname' => 'Active User',
            'email' => 'active@example.com',
            'password' => bcrypt('secret123'),
            'status' => 1,
        ]);

        $response = $this->postJson(route('customer.login.submit'), [
            'email' => 'active@example.com',
            'password' => 'secret123',
        ]);

        $response->assertOk();
        $response->assertJson(['error' => 0]);
        $this->assertAuthenticated();
    }

    public function test_login_rejects_wrong_password(): void
    {
        $this->withoutMiddleware(Currency::class);

        User::create([
            'fullname' => 'Active User',
            'email' => 'active2@example.com',
            'password' => bcrypt('secret123'),
            'status' => 1,
        ]);

        $response = $this->postJson(route('customer.login.submit'), [
            'email' => 'active2@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertOk();
        $response->assertJson(['error' => 1]);
        $this->assertGuest();
    }

    public function test_registration_creates_active_user_and_logs_in(): void
    {
        $this->withoutMiddleware(Currency::class);

        $response = $this->postJson(route('customer.register.submit'), [
            'name' => 'New Customer',
            'email' => 'new@example.com',
            'phone' => '0900000001',
            'password' => 'secret123',
            'password_confirm' => 'secret123',
        ]);

        $response->assertOk();
        $response->assertJson(['error' => 0]);

        $user = User::where('email', 'new@example.com')->first();
        $this->assertNotNull($user);
        $this->assertSame('New Customer', $user->fullname);
        $this->assertSame(1, (int) $user->status);
        $this->assertTrue(Hash::check('secret123', $user->password));
        $this->assertAuthenticated();
    }

    public function test_registered_user_can_login_afterwards(): void
    {
        $this->withoutMiddleware(Currency::class);

        $this->postJson(route('customer.register.submit'), [
            'name' => 'Repeat Customer',
            'email' => 'repeat@example.com',
            'phone' => '0900000002',
            'password' => 'secret123',
            'password_confirm' => 'secret123',
        ])->assertOk();

        auth()->logout();
        $this->assertGuest();

        $response = $this->postJson(route('customer.login.submit'), [
            'email' => 'repeat@example.com',
            'password' => 'secret123',
        ]);

        $response->assertOk();
        $response->assertJson(['error' => 0]);
        $this->assertAuthenticated();
    }
}

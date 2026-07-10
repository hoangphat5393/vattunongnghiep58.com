<?php

namespace Tests\Feature;

use App\Http\Middleware\Currency;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ForgotPasswordFlowTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('email')->unique();
                $table->string('first_name')->nullable();
                $table->string('password')->nullable();
                $table->timestamps();
            });
        }
    }

    public function test_forgot_password_requires_email(): void
    {
        $this->withoutMiddleware(Currency::class);

        $response = $this
            ->from(route('customer.password.forgot'))
            ->post(route('customer.password.forgot.submit'), [
                'email' => '',
            ]);

        $response->assertRedirect(route('customer.password.forgot'));
        $response->assertSessionHasErrors(['email']);
    }

    public function test_forgot_password_returns_error_when_email_not_found(): void
    {
        $this->withoutMiddleware(Currency::class);

        $response = $this
            ->from(route('customer.password.forgot'))
            ->post(route('customer.password.forgot.submit'), [
                'email' => 'not-exist@example.com',
            ]);

        $response->assertRedirect(route('customer.password.forgot'));
        $response->assertSessionHasErrors(['email']);
    }
}

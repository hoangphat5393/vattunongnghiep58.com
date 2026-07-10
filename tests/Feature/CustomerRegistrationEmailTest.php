<?php

namespace Tests\Feature;

use App\Http\Middleware\Currency;
use App\Models\Frontend\EmailTemplate;
use App\Models\Frontend\User;
use App\Services\CustomerRegistrationEmailService;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mime\Email;
use Tests\TestCase;

class CustomerRegistrationEmailTest extends TestCase
{
    public function test_registration_triggers_welcome_email_when_new_register_template_is_published(): void
    {
        $this->withoutMiddleware(Currency::class);

        EmailTemplate::query()->create([
            'name' => 'Chào mừng đăng ký tài khoản',
            'code' => 'new_register',
            'text' => '<p>Xin chào {{$fullname}}, email: {{$email}}</p>',
            'status' => 1,
            'sort' => 1,
        ]);

        Mail::shouldReceive('send')
            ->once()
            ->with([], [], \Mockery::type('Closure'))
            ->andReturnNull();

        $this->postJson(route('customer.register.submit'), [
            'name' => 'New Customer',
            'email' => 'welcome@example.com',
            'phone' => '0900000099',
            'password' => 'secret123',
            'password_confirm' => 'secret123',
        ])->assertOk()->assertJson(['error' => 0]);

        $this->assertDatabaseHas('users', ['email' => 'welcome@example.com']);
    }

    public function test_registration_uses_legacy_customer_register_template_when_new_register_missing(): void
    {
        $this->withoutMiddleware(Currency::class);

        EmailTemplate::query()->create([
            'name' => 'Legacy welcome',
            'code' => 'customer_register',
            'text' => '<p>Legacy {{$email}}</p>',
            'status' => 1,
            'sort' => 1,
        ]);

        Mail::shouldReceive('send')
            ->once()
            ->with([], [], \Mockery::type('Closure'))
            ->andReturnNull();

        $this->postJson(route('customer.register.submit'), [
            'name' => 'Legacy User',
            'email' => 'legacy@example.com',
            'phone' => '0900000098',
            'password' => 'secret123',
            'password_confirm' => 'secret123',
        ])->assertOk()->assertJson(['error' => 0]);
    }

    public function test_registration_skips_email_when_template_is_missing_or_draft(): void
    {
        $this->withoutMiddleware(Currency::class);

        EmailTemplate::query()->create([
            'name' => 'Draft template',
            'code' => 'new_register',
            'text' => '<p>Should not send</p>',
            'status' => 0,
            'sort' => 1,
        ]);

        Mail::shouldReceive('send')->never();

        $this->postJson(route('customer.register.submit'), [
            'name' => 'No Mail User',
            'email' => 'nomail@example.com',
            'phone' => '0900000088',
            'password' => 'secret123',
            'password_confirm' => 'secret123',
        ])->assertOk()->assertJson(['error' => 0]);
    }

    public function test_registration_still_succeeds_when_mail_service_fails(): void
    {
        $this->withoutMiddleware(Currency::class);

        $this->mock(CustomerRegistrationEmailService::class, function ($mock) {
            $mock->shouldReceive('sendRegistrationEmails')
                ->once()
                ->andThrow(new \RuntimeException('SMTP down'));
        });

        $response = $this->postJson(route('customer.register.submit'), [
            'name' => 'Resilient User',
            'email' => 'resilient@example.com',
            'phone' => '0900000077',
            'password' => 'secret123',
            'password_confirm' => 'secret123',
        ]);

        $response->assertOk()->assertJson(['error' => 0]);
        $this->assertAuthenticated();
        $this->assertInstanceOf(User::class, User::where('email', 'resilient@example.com')->first());
    }

    public function test_service_replaces_placeholders_in_template_body(): void
    {
        EmailTemplate::query()->create([
            'name' => 'Welcome',
            'code' => 'new_register',
            'text' => '<p>Hello {{$fullname}} — {{$email}}</p>',
            'status' => 1,
            'sort' => 1,
        ]);

        $capturedHtml = null;

        Mail::shouldReceive('send')
            ->once()
            ->with([], [], \Mockery::on(function ($callback) use (&$capturedHtml) {
                $message = new Message(new Email);
                $callback($message);
                $capturedHtml = $message->getHtmlBody();

                return true;
            }))
            ->andReturnNull();

        $user = User::create([
            'fullname' => 'Nguyen Van A',
            'email' => 'a@example.com',
            'phone' => '0900000001',
            'password' => bcrypt('secret'),
            'status' => 1,
        ]);

        app(CustomerRegistrationEmailService::class)->sendRegistrationEmails($user);

        $this->assertStringContainsString('Nguyen Van A', (string) $capturedHtml);
        $this->assertStringContainsString('a@example.com', (string) $capturedHtml);
    }
}

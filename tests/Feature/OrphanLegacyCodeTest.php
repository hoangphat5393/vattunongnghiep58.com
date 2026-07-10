<?php

namespace Tests\Feature;

use App\Http\Middleware\Currency;
use App\Models\Frontend\Contact;
use App\Models\Frontend\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class OrphanLegacyCodeTest extends TestCase
{
    public function test_newsletter_subscription_persists_to_contacts_table(): void
    {
        $this->withoutMiddleware(Currency::class);

        $response = $this->postJson(route('subscription'), [
            'email' => 'newsletter@example.com',
        ]);

        $response->assertOk()
            ->assertJson([
                'status' => 'success',
            ]);

        $this->assertDatabaseHas('contacts', [
            'email' => 'newsletter@example.com',
            'type' => 'subscription',
        ]);
    }

    public function test_newsletter_subscription_rejects_duplicate_email(): void
    {
        $this->withoutMiddleware(Currency::class);

        Contact::create([
            'email' => 'dup@example.com',
            'type' => 'subscription',
            'name' => 'dup@example.com',
            'status' => 1,
        ]);

        $response = $this->postJson(route('subscription'), [
            'email' => 'dup@example.com',
        ]);

        $response->assertOk()
            ->assertJson([
                'status' => 'error',
            ]);
    }

    public function test_legacy_account_routes_redirect_to_dashboard(): void
    {
        $this->withoutMiddleware(Currency::class);

        $user = User::create([
            'fullname' => 'Legacy Route User',
            'email' => 'legacy-route@example.com',
            'password' => bcrypt('secret123'),
            'status' => 1,
        ]);

        $this->actingAs($user)
            ->get(route('customer.reviews'))
            ->assertRedirect(route('customer.dashboard'));

        $this->actingAs($user)
            ->get(route('customer.refused'))
            ->assertRedirect(route('customer.dashboard'));
    }

    public function test_customer_my_post_route_redirects_to_dashboard(): void
    {
        $this->withoutMiddleware(Currency::class);

        $user = User::create([
            'fullname' => 'Post User',
            'email' => 'post-user@example.com',
            'password' => bcrypt('secret123'),
            'status' => 1,
        ]);

        $this->actingAs($user)
            ->get(route('customer.post'))
            ->assertRedirect(route('customer.dashboard'));
    }

    public function test_permalink_helper_uses_product_route(): void
    {
        if (config('database.default') !== 'sqlite') {
            $this->markTestSkipped('Product permalink helper test uses sqlite test schema.');
        }

        $productId = DB::table('products')->insertGetId([
            'name' => 'Helper Product',
            'slug' => 'helper-product',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $url = permalink_by_id($productId);

        $this->assertStringContainsString('helper-product', $url);
        $this->assertStringContainsString((string) $productId, $url);
    }
}

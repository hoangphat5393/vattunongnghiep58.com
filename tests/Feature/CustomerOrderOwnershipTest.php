<?php

namespace Tests\Feature;

use App\Http\Middleware\Currency;
use App\Models\Addtocard;
use App\Models\Frontend\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CustomerOrderOwnershipTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! Schema::hasTable('shop_orders')) {
            Schema::create('shop_orders', function (Blueprint $table) {
                $table->id('cart_id');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('name')->nullable();
                $table->string('cart_email')->nullable();
                $table->string('cart_phone')->nullable();
                $table->string('cart_address')->nullable();
                $table->longText('cart_note')->nullable();
                $table->string('cart_code')->nullable();
                $table->integer('cart_status')->default(0);
                $table->integer('cart_payment')->default(0);
                $table->decimal('cart_total', 12, 2)->default(0);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('shop_payment_method')) {
            Schema::create('shop_payment_method', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('code')->nullable();
                $table->unsignedTinyInteger('status')->default(1);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('shop_order_items')) {
            Schema::create('shop_order_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('cart_id');
                $table->unsignedBigInteger('product_id')->nullable();
                $table->decimal('price', 12, 2)->default(0);
                $table->integer('quanlity')->default(1);
                $table->timestamps();
            });
        }
    }

    public function test_user_cannot_view_another_users_order(): void
    {
        $this->withoutMiddleware(Currency::class);

        $owner = User::create([
            'fullname' => 'Owner',
            'email' => 'owner@example.com',
            'password' => bcrypt('secret123'),
            'status' => 1,
        ]);

        $intruder = User::create([
            'fullname' => 'Intruder',
            'email' => 'intruder@example.com',
            'password' => bcrypt('secret123'),
            'status' => 1,
        ]);

        $order = Addtocard::create([
            'user_id' => $owner->id,
            'name' => 'Owner Order',
            'cart_email' => 'owner@example.com',
            'cart_code' => 'ORD-001',
            'cart_status' => 0,
            'cart_total' => 100000,
        ]);

        $this->actingAs($intruder)
            ->get(route('customer.orders.show', ['id_cart' => $order->cart_id]))
            ->assertForbidden();
    }

    public function test_user_can_view_own_order(): void
    {
        $this->withoutMiddleware(Currency::class);

        $owner = User::create([
            'fullname' => 'Owner',
            'email' => 'owner2@example.com',
            'password' => bcrypt('secret123'),
            'status' => 1,
        ]);

        $order = Addtocard::create([
            'user_id' => $owner->id,
            'name' => 'Owner Order',
            'cart_email' => 'owner2@example.com',
            'cart_code' => 'ORD-002',
            'cart_status' => 0,
            'cart_total' => 100000,
        ]);

        $this->actingAs($owner)
            ->get(route('customer.orders.show', ['id_cart' => $order->cart_id]))
            ->assertOk();
    }

    public function test_login_claims_guest_orders_by_email(): void
    {
        $this->withoutMiddleware(Currency::class);

        $order = Addtocard::create([
            'user_id' => null,
            'name' => 'Guest Order',
            'cart_email' => 'claim@example.com',
            'cart_code' => 'ORD-GUEST',
            'cart_status' => 0,
            'cart_total' => 50000,
        ]);

        User::create([
            'fullname' => 'Claim User',
            'email' => 'claim@example.com',
            'password' => bcrypt('secret123'),
            'status' => 1,
        ]);

        $this->postJson(route('customer.login.submit'), [
            'email' => 'claim@example.com',
            'password' => 'secret123',
        ])->assertOk();

        $this->assertSame((int) auth()->id(), (int) $order->fresh()->user_id);
    }

    public function test_checkout_assigns_user_id_when_logged_in(): void
    {
        $this->withoutMiddleware(Currency::class);

        $user = User::create([
            'fullname' => 'Checkout User',
            'email' => 'checkout@example.com',
            'password' => bcrypt('secret123'),
            'status' => 1,
        ]);

        $this->actingAs($user);

        $orderData = [
            'name' => 'Checkout User',
            'cart_email' => 'checkout@example.com',
            'cart_phone' => '0900000000',
            'cart_address' => 'Test address',
            'cart_note' => 'Note',
            'cart_total' => 120000,
            'user_id' => auth()->id(),
        ];

        $order = Addtocard::create($orderData);

        $this->assertDatabaseHas('shop_orders', [
            'cart_id' => $order->cart_id,
            'user_id' => $user->id,
            'cart_email' => 'checkout@example.com',
        ]);
    }
}

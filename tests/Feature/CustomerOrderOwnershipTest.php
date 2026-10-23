<?php

namespace Tests\Feature;

use App\Http\Middleware\Currency;
use App\Models\Frontend\Order;
use App\Models\Frontend\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CustomerOrderOwnershipTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id('id');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('name')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('address')->nullable();
                $table->longText('note')->nullable();
                $table->string('code')->nullable();
                $table->integer('status')->default(0);
                $table->integer('payment_status')->default(0);
                $table->decimal('total', 12, 2)->default(0);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('payment_methods')) {
            Schema::create('payment_methods', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('code')->nullable();
                $table->unsignedTinyInteger('status')->default(1);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('order_items')) {
            Schema::create('order_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('order_id');
                $table->unsignedBigInteger('product_id')->nullable();
                $table->decimal('price', 12, 2)->default(0);
                $table->integer('quantity')->default(1);
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

        $order = Order::create([
            'user_id' => $owner->id,
            'name' => 'Owner Order',
            'email' => 'owner@example.com',
            'code' => 'ORD-001',
            'status' => 0,
            'total' => 100000,
        ]);

        $this->actingAs($intruder)
            ->get(route('customer.orders.show', ['id_cart' => $order->id]))
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

        $order = Order::create([
            'user_id' => $owner->id,
            'name' => 'Owner Order',
            'email' => 'owner2@example.com',
            'code' => 'ORD-002',
            'status' => 0,
            'total' => 100000,
        ]);

        $this->actingAs($owner)
            ->get(route('customer.orders.show', ['id_cart' => $order->id]))
            ->assertOk();
    }

    public function test_login_claims_guest_orders_by_email(): void
    {
        $this->withoutMiddleware(Currency::class);

        $order = Order::create([
            'user_id' => null,
            'name' => 'Guest Order',
            'email' => 'claim@example.com',
            'code' => 'ORD-GUEST',
            'status' => 0,
            'total' => 50000,
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
            'email' => 'checkout@example.com',
            'phone' => '0900000000',
            'address' => 'Test address',
            'note' => 'Note',
            'total' => 120000,
            'user_id' => auth()->id(),
        ];

        $order = Order::create($orderData);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'user_id' => $user->id,
            'email' => 'checkout@example.com',
        ]);
    }
}

<?php

namespace Tests\Unit;

use App\Models\Addtocard;
use App\Models\Frontend\User;
use App\Services\CustomerAccountService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class CustomerAccountServiceTest extends TestCase
{
    private CustomerAccountService $service;

    protected function setUp(): void
    {
        parent::setUp();

        if (config('database.default') !== 'sqlite') {
            $this->markTestSkipped('CustomerAccountService unit tests run on sqlite only.');
        }

        $this->service = app(CustomerAccountService::class);

        if (! Schema::hasTable('shop_orders')) {
            Schema::create('shop_orders', function (Blueprint $table) {
                $table->id('cart_id');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('cart_email')->nullable();
                $table->string('cart_code')->nullable();
                $table->integer('cart_status')->default(0);
                $table->decimal('cart_total', 12, 2)->default(0);
                $table->timestamps();
            });
        }
    }

    public function test_link_guest_orders_to_user_assigns_user_id_by_email(): void
    {
        $order = Addtocard::create([
            'user_id' => null,
            'cart_email' => 'guest-link@example.com',
            'cart_code' => 'ORD-GUEST-LINK',
            'cart_status' => 0,
            'cart_total' => 100000,
        ]);

        $linked = $this->service->linkGuestOrdersToUser(42, 'guest-link@example.com');

        $this->assertSame(1, $linked);
        $this->assertSame(42, (int) $order->fresh()->user_id);
    }

    public function test_update_profile_persists_validated_fields(): void
    {
        $user = User::create([
            'fullname' => 'Before Name',
            'email' => 'profile-service@example.com',
            'password' => bcrypt('secret123'),
            'phone' => '0900000000',
            'address' => 'Old address',
            'status' => 1,
        ]);

        $updated = $this->service->updateProfile($user, [
            'fullname' => 'After Name',
            'phone' => '0911111111',
            'address' => 'New address',
        ]);

        $this->assertSame('After Name', $updated->fullname);
        $this->assertSame('0911111111', $updated->phone);
        $this->assertSame('New address', $updated->address);
    }

    public function test_find_order_for_user_or_abort_returns_order_for_owner(): void
    {
        $user = User::create([
            'fullname' => 'Owner',
            'email' => 'owner-service@example.com',
            'password' => bcrypt('secret123'),
            'status' => 1,
        ]);

        $order = Addtocard::create([
            'user_id' => $user->id,
            'cart_email' => 'owner-service@example.com',
            'cart_code' => 'ORD-OWN',
            'cart_status' => 0,
            'cart_total' => 50000,
        ]);

        $found = $this->service->findOrderForUserOrAbort($user->id, $order->cart_id);

        $this->assertSame((int) $order->cart_id, (int) $found->cart_id);
    }

    public function test_find_order_for_user_or_abort_blocks_other_users(): void
    {
        $owner = User::create([
            'fullname' => 'Owner',
            'email' => 'owner2@example.com',
            'password' => bcrypt('secret123'),
            'status' => 1,
        ]);

        $intruder = User::create([
            'fullname' => 'Intruder',
            'email' => 'intruder2@example.com',
            'password' => bcrypt('secret123'),
            'status' => 1,
        ]);

        $order = Addtocard::create([
            'user_id' => $owner->id,
            'cart_email' => 'owner2@example.com',
            'cart_code' => 'ORD-BLOCK',
            'cart_status' => 0,
            'cart_total' => 50000,
        ]);

        $this->expectException(HttpException::class);

        $this->service->findOrderForUserOrAbort($intruder->id, $order->cart_id);
    }
}

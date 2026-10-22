<?php

namespace Tests\Unit;

use App\Models\Frontend\Order;
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

        if (! Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id('id');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('email')->nullable();
                $table->string('code')->nullable();
                $table->integer('status')->default(0);
                $table->decimal('total', 12, 2)->default(0);
                $table->timestamps();
            });
        }
    }

    public function test_link_guest_orders_to_user_assigns_user_id_by_email(): void
    {
        $order = Order::create([
            'user_id' => null,
            'email' => 'guest-link@example.com',
            'code' => 'ORD-GUEST-LINK',
            'status' => 0,
            'total' => 100000,
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

        $order = Order::create([
            'user_id' => $user->id,
            'email' => 'owner-service@example.com',
            'code' => 'ORD-OWN',
            'status' => 0,
            'total' => 50000,
        ]);

        $found = $this->service->findOrderForUserOrAbort($user->id, $order->id);

        $this->assertSame((int) $order->id, (int) $found->id);
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

        $order = Order::create([
            'user_id' => $owner->id,
            'email' => 'owner2@example.com',
            'code' => 'ORD-BLOCK',
            'status' => 0,
            'total' => 50000,
        ]);

        $this->expectException(HttpException::class);

        $this->service->findOrderForUserOrAbort($intruder->id, $order->id);
    }
}

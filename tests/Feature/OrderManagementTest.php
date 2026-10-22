<?php

namespace Tests\Feature;

use App\Models\Backend\Role;
use App\Models\Backend\User;
use App\Models\Frontend\Order;
use App\Services\DatabaseMaintenanceService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class OrderManagementTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'database.connections.pgsql.database' => 'vattunongnghiep58',
            'database.connections.pgsql.username' => 'postgres',
            'database.connections.pgsql.password' => '',
            'database.connections.pgsql.host' => '127.0.0.1',
            'database.connections.pgsql.port' => '5432',
        ]);
        DB::purge('pgsql');
        DB::setDefaultConnection('pgsql');
    }

    protected function createAdministrator(): User
    {
        $role = Role::firstOrCreate(['slug' => 'administrator'], ['name' => 'Administrator']);

        $admin = User::create([
            'fullname' => 'Order Admin',
            'username' => 'order_admin_'.time(),
            'email' => 'order_admin_'.time().'@example.com',
            'password' => bcrypt('password'),
            'status' => 1,
            'admin_level' => 1,
        ]);

        $admin->roles()->sync([$role->id]);

        return $admin;
    }

    /**
     * Test orders and order_items tables exist with standardized schema.
     */
    public function test_standardized_schema_tables_and_columns_exist(): void
    {
        $this->assertTrue(Schema::hasTable('orders'), 'Table orders must exist.');
        $this->assertTrue(Schema::hasTable('order_items'), 'Table order_items must exist.');

        $this->assertTrue(Schema::hasColumns('orders', [
            'id', 'code', 'phone', 'email', 'address', 'total', 'status', 'payment_status',
        ]), 'Orders table must have standard column names.');

        $this->assertTrue(Schema::hasColumns('order_items', [
            'id', 'order_id', 'product_id', 'price', 'quantity',
        ]), 'Order items table must have standard column names.');
    }

    /**
     * Test creating order and order items via Eloquent relationship.
     */
    public function test_create_order_with_items_and_relationships(): void
    {
        $order = Order::create([
            'name' => 'Nguyễn Văn Test',
            'phone' => '0912345678',
            'email' => 'nguyen_test@example.com',
            'address' => '123 Đường Test, Quận 1',
            'total' => 250000,
            'status' => 0,
            'payment_status' => 0,
            'code' => 'TEST-ORD-'.time(),
        ]);

        $this->assertNotNull($order->id);
        $this->assertGreaterThan(0, $order->id);

        $item = $order->items()->create([
            'product_id' => null,
            'price' => 125000,
            'quantity' => 2,
            'price_label' => 'Gói 1kg',
        ]);

        $this->assertSame((int) $order->id, (int) $item->order_id);
        $this->assertCount(1, $order->fresh()->items);

        // Test backward compatibility accessors
        $this->assertSame((int) $order->id, (int) $order->cart_id);
        $this->assertSame($order->code, $order->cart_code);
        $this->assertSame($order->total, $order->cart_total);
        $this->assertSame((int) $item->order_id, (int) $item->cart_id);
        $this->assertSame(2, (int) $item->quanlity);

        // Cleanup
        $order->delete();
    }

    /**
     * Test foreign key cascading delete on order_items when order is deleted.
     */
    public function test_cascading_delete_on_order_items(): void
    {
        $order = Order::create([
            'name' => 'Cascade Test Order',
            'phone' => '0909090909',
            'email' => 'cascade@example.com',
            'total' => 50000,
            'code' => 'CASCADE-'.time(),
        ]);

        $item = $order->items()->create([
            'price' => 50000,
            'quantity' => 1,
        ]);

        $orderId = $order->id;
        $itemId = $item->id;

        $this->assertDatabaseHas('order_items', ['id' => $itemId, 'order_id' => $orderId]);

        // Direct DB delete on orders table
        DB::table('orders')->where('id', $orderId)->delete();

        // Foreign key cascade must have purged order_items automatically
        $this->assertDatabaseMissing('order_items', ['id' => $itemId]);
    }

    /**
     * Test admin updating order status.
     */
    public function test_admin_can_update_order_details(): void
    {
        $admin = $this->createAdministrator();

        $order = Order::create([
            'name' => 'Update Test',
            'phone' => '0988888888',
            'email' => 'update_test@example.com',
            'total' => 100000,
            'status' => 0,
            'payment_status' => 0,
            'code' => 'UPD-'.time(),
        ]);

        $response = $this->actingAs($admin, 'admin')->post(route('admin.order.update'), [
            'id' => $order->id,
            'status' => 2, // Hoàn thành
            'payment_status' => 1, // Đã thanh toán
            'shipping_cost' => 30000,
            'admin_note' => 'Đã giao thành công',
        ]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 2,
            'payment_status' => 1,
            'note' => 'Đã giao thành công',
        ]);

        $order->delete();
        $admin->delete();
    }

    /**
     * Test database maintenance service recognizes orders and order_items tables.
     */
    public function test_database_maintenance_dashboard_recognizes_orders_table(): void
    {
        $service = new DatabaseMaintenanceService;
        $stats = $service->getTableStats();
        $tables = array_column($stats, 'table');

        $this->assertContains('orders', $tables);
        $this->assertContains('order_items', $tables);

        // Test resetting sequence on orders table
        $result = $service->resetTableSequence('orders');
        $this->assertSame('orders', $result['table']);
        $this->assertSame('id', $result['pk']);
        $this->assertGreaterThan(0, $result['next_id']);
    }
}

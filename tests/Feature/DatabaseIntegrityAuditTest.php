<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\AjaxController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DatabaseIntegrityAuditTest extends TestCase
{
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

    /**
     * Test PostgreSQL connection and active schema.
     */
    public function test_postgresql_connection_is_active(): void
    {
        $db = DB::connection('pgsql');
        $driver = $db->getDriverName();
        $this->assertEquals('pgsql', $driver, 'Database driver must be pgsql');

        $dbName = $db->selectOne('SELECT current_database() as db')->db;
        $this->assertEquals('vattunongnghiep58', $dbName, 'Database name must be vattunongnghiep58');
    }

    /**
     * Test all foreign keys exist in PostgreSQL catalog.
     */
    public function test_all_foreign_keys_are_present(): void
    {
        $foreignKeys = DB::connection('pgsql')->select("
            SELECT
                tc.table_name,
                kcu.column_name,
                ccu.table_name AS foreign_table_name,
                ccu.column_name AS foreign_column_name
            FROM information_schema.table_constraints AS tc
            JOIN information_schema.key_column_usage AS kcu
              ON tc.constraint_name = kcu.constraint_name
              AND tc.table_schema = kcu.table_schema
            JOIN information_schema.constraint_column_usage AS ccu
              ON ccu.constraint_name = tc.constraint_name
              AND ccu.table_schema = tc.table_schema
            WHERE tc.constraint_type = 'FOREIGN KEY'
              AND tc.table_schema = 'public'
            ORDER BY tc.table_name, kcu.column_name;
        ");

        $this->assertNotEmpty($foreignKeys, 'Foreign keys should exist');

        $fkList = array_map(fn ($fk) => "{$fk->table_name}.{$fk->column_name} -> {$fk->foreign_table_name}.{$fk->foreign_column_name}", $foreignKeys);

        // Check core FKs
        $this->assertContains('product_categories.product_id -> products.id', $fkList);
        $this->assertContains('product_categories.category_id -> categories.id', $fkList);
        $this->assertContains('role_user.user_id -> users.id', $fkList);
        $this->assertContains('role_user.role_id -> roles.id', $fkList);
        $this->assertContains('shop_orders.user_id -> users.id', $fkList);
        $this->assertContains('shop_order_items.cart_id -> shop_orders.cart_id', $fkList);
        $this->assertContains('product_prices.product_id -> products.id', $fkList);
        $this->assertContains('menu_items.menu_id -> menus.id', $fkList);
        $this->assertContains('permission_role.role_id -> roles.id', $fkList);
    }

    /**
     * Test data integrity across critical business tables.
     */
    public function test_business_data_integrity_and_joins(): void
    {
        $db = DB::connection('pgsql');

        // 1. Products and Categories Join
        $productCount = $db->table('products')->count();
        $this->assertGreaterThan(0, $productCount, 'Products table must contain data');

        $productsWithCategories = $db->table('products')
            ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
            ->join('categories', 'product_categories.category_id', '=', 'categories.id')
            ->select('products.id', 'products.name as product_name', 'categories.name as category_name')
            ->get();

        $this->assertNotEmpty($productsWithCategories, 'Products should join cleanly with categories via product_categories');

        // Check Vietnamese UTF-8 names are preserved
        $sampleProduct = $db->table('products')->first();
        $this->assertNotNull($sampleProduct);
        $this->assertNotEmpty($sampleProduct->name);

        // 2. Orders and Order Items Join
        $orderCount = $db->table('shop_orders')->count();
        $this->assertGreaterThan(0, $orderCount, 'Orders table must contain data');

        $ordersWithItems = $db->table('shop_orders')
            ->join('shop_order_items', 'shop_orders.cart_id', '=', 'shop_order_items.cart_id')
            ->select('shop_orders.cart_id', 'shop_order_items.price_label', 'shop_order_items.price')
            ->get();

        $this->assertNotEmpty($ordersWithItems, 'Shop orders should join cleanly with order items via cart_id');

        // 3. User & Roles Join
        $userCount = $db->table('users')->count();
        $this->assertGreaterThan(0, $userCount, 'Users table must contain data');

        $usersWithRoles = $db->table('users')
            ->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->select('users.email', 'roles.name as role_name')
            ->get();

        $this->assertNotEmpty($usersWithRoles, 'Users should join cleanly with roles');
    }

    /**
     * Test sequence auto-increment works without primary key collision.
     */
    public function test_sequences_and_crud_insert_delete(): void
    {
        $db = DB::connection('pgsql');

        // Test inserting and deleting a temporary page
        $insertedId = $db->table('pages')->insertGetId([
            'name' => 'Trang kiểm tra khóa ngoại vattunongnghiep58',
            'slug' => 'trang-kiem-tra-khoa-ngoai-vt58-'.time(),
            'description' => 'Kiểm tra sequence PostgreSQL',
            'content' => 'Nội dung kiểm tra',
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertGreaterThan(0, $insertedId);

        // Verify it exists
        $record = $db->table('pages')->where('id', $insertedId)->first();
        $this->assertNotNull($record);

        // Clean up
        $db->table('pages')->where('id', $insertedId)->delete();
        $this->assertNull($db->table('pages')->where('id', $insertedId)->first());
    }

    /**
     * Test that zero orphan records exist after wiring foreign keys.
     */
    public function test_zero_orphan_records(): void
    {
        $db = DB::connection('pgsql');

        // No orphaned product_categories
        $orphanProductCategories = $db->table('product_categories')
            ->leftJoin('products', 'product_categories.product_id', '=', 'products.id')
            ->leftJoin('categories', 'product_categories.category_id', '=', 'categories.id')
            ->whereNull('products.id')
            ->orWhereNull('categories.id')
            ->count();
        $this->assertEquals(0, $orphanProductCategories, 'Zero orphan product_categories allowed');

        // No orphaned role_user
        $orphanRoleUser = $db->table('role_user')
            ->leftJoin('users', 'role_user.user_id', '=', 'users.id')
            ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
            ->whereNull('users.id')
            ->orWhereNull('roles.id')
            ->count();
        $this->assertEquals(0, $orphanRoleUser, 'Zero orphan role_user allowed');

        // No orphaned shop_order_items (cart_id)
        $orphanOrderItems = $db->table('shop_order_items')
            ->leftJoin('shop_orders', 'shop_order_items.cart_id', '=', 'shop_orders.cart_id')
            ->whereNull('shop_orders.cart_id')
            ->count();
        $this->assertEquals(0, $orphanOrderItems, 'Zero orphan shop_order_items allowed for cart_id');
    }

    /**
     * Test bulk delete functionality executes cleanly on PostgreSQL without SQL errors.
     */
    public function test_bulk_delete_functions_correctly_on_postgresql(): void
    {
        $db = DB::connection('pgsql');

        // 1. Create a dummy test post in pages
        $testId = $db->table('pages')->insertGetId([
            'name' => 'Test Bulk Delete VT58 Post',
            'slug' => 'test-bulk-delete-vt58-'.time(),
            'type' => 'post',
            'status' => 0,
            'sort' => 9999,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertNotNull($db->table('pages')->where('id', $testId)->first());

        // 2. Execute bulk delete via AjaxController
        $controller = new AjaxController;
        $request = new Request;
        $request->merge([
            'type' => 'post',
            'seq_list' => [$testId],
        ]);

        $result = $controller->ajax_delete($request);
        $this->assertEquals(1, $result, 'Bulk delete must return 1 on success');

        // 3. Verify record was removed
        $this->assertNull($db->table('pages')->where('id', $testId)->first(), 'Record must be deleted');

        // 4. Verify subsequent insert succeeds and sequence increments properly
        $newId = $db->table('pages')->insertGetId([
            'name' => 'Post After Bulk Delete VT58',
            'slug' => 'post-after-bulk-delete-vt58-'.time(),
            'type' => 'post',
            'status' => 0,
            'sort' => 9999,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertGreaterThan(0, $newId, 'New record should have valid incremented ID');
        $db->table('pages')->where('id', $newId)->delete();
    }
}

<?php

namespace Tests\Feature;

use App\Models\Backend\Role;
use App\Models\Backend\User;
use App\Services\DatabaseMaintenanceService;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DatabaseMaintenanceFeatureTest extends TestCase
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

    protected function createAdministrator(): User
    {
        $role = Role::firstOrCreate(['slug' => 'administrator'], ['name' => 'Administrator']);

        $admin = User::create([
            'fullname' => 'Maintenance Admin',
            'username' => 'maint_'.time(),
            'email' => 'maint_'.time().'@example.com',
            'password' => bcrypt('password'),
            'status' => 1,
            'admin_level' => 1,
        ]);

        $admin->roles()->sync([$role->id]);

        return $admin;
    }

    /**
     * Test admin can access database maintenance dashboard.
     */
    public function test_admin_can_access_database_maintenance_dashboard(): void
    {
        $admin = $this->createAdministrator();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.database-maintenance.index'));

        $response->assertOk();
        $response->assertSee('Bảo trì CSDL');
        $response->assertSee('products');
        $response->assertSee('categories');
        $response->assertSee('pages');

        $admin->delete();
    }

    /**
     * Test guest cannot access database maintenance.
     */
    public function test_guest_cannot_access_database_maintenance(): void
    {
        $response = $this->get(route('admin.database-maintenance.index'));
        $response->assertRedirect('/admin/login');
    }

    /**
     * Test database maintenance service returns valid table stats.
     */
    public function test_database_maintenance_service_returns_table_stats(): void
    {
        $service = new DatabaseMaintenanceService;
        $stats = $service->getTableStats();

        $this->assertNotEmpty($stats);
        $tables = array_column($stats, 'table');

        $this->assertContains('products', $tables);
        $this->assertContains('categories', $tables);
        $this->assertContains('pages', $tables);
        $this->assertContains('shop_orders', $tables);

        foreach ($stats as $item) {
            $this->assertArrayHasKey('table', $item);
            $this->assertArrayHasKey('pk', $item);
            $this->assertArrayHasKey('total_rows', $item);
            $this->assertArrayHasKey('max_id', $item);
            $this->assertArrayHasKey('next_id', $item);
            $this->assertGreaterThanOrEqual(0, $item['total_rows']);
            $this->assertGreaterThanOrEqual(0, $item['max_id']);
            $this->assertGreaterThanOrEqual(1, $item['next_id']);
        }
    }

    /**
     * Test resetting sequence on table with data ensures next ID is greater than max ID.
     */
    public function test_reset_sequence_on_populated_table_preserves_next_id(): void
    {
        $admin = $this->createAdministrator();
        $service = new DatabaseMaintenanceService;

        // Test on pages table
        $maxBefore = DB::table('pages')->max('id') ?? 0;

        $response = $this->actingAs($admin, 'admin')->post(route('admin.database-maintenance.reset'), [
            'table' => 'pages',
        ]);

        $response->assertRedirect(route('admin.database-maintenance.index'));
        $response->assertSessionHas('success');

        // Insert new dummy page to verify next sequence is maxBefore + 1
        $newId = DB::table('pages')->insertGetId([
            'name' => 'Sequence Verification Page',
            'slug' => 'seq-verify-'.time(),
            'type' => 'page',
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertGreaterThan($maxBefore, $newId, 'New record ID must be strictly greater than max existing ID');

        // Clean up
        DB::table('pages')->where('id', $newId)->delete();
        $admin->delete();
    }

    /**
     * Test invalid table rejection.
     */
    public function test_invalid_table_rejection(): void
    {
        $admin = $this->createAdministrator();

        $response = $this->actingAs($admin, 'admin')->post(route('admin.database-maintenance.reset'), [
            'table' => 'non_existent_hack_table',
        ]);

        $response->assertRedirect(route('admin.database-maintenance.index'));
        $response->assertSessionHas('error');

        $admin->delete();
    }
}

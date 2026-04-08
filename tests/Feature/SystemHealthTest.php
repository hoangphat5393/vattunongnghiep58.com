<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SystemHealthTest extends TestCase
{
    /**
     * Test database connection.
     */
    public function test_database_connection()
    {
        try {
            DB::connection()->getPdo();
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail('Database connection failed: '.$e->getMessage());
        }
    }

    /**
     * Test critical tables exist.
     */
    public function test_critical_tables_exist()
    {
        $tables = ['settings', 'pages', 'categories', 'products', 'users', 'roles', 'permissions', 'role_user', 'permission_role'];
        foreach ($tables as $table) {
            $this->assertTrue(Schema::hasTable($table), "Table '$table' does not exist.");
        }
    }

    /**
     * Test admin page load (sanity check).
     */
    public function test_admin_login_page_loads()
    {
        // Assuming /admin/login is the route
        $response = $this->get('/admin/login');
        // It might redirect or show login page
        if ($response->status() == 302) {
            $this->assertTrue(true); // Redirect is fine (likely to dashboard if logged in or back to login)
        } else {
            $response->assertStatus(200);
        }
    }
}

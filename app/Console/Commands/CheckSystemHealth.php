<?php

namespace App\Console\Commands;

use App\Models\Backend\Permission;
use App\Models\Backend\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CheckSystemHealth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check system health including database tables and basic functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting System Health Check...');

        // 1. Check Database Connection
        try {
            DB::connection()->getPdo();
            $this->info('✅ Database connection successful.');
        } catch (\Exception $e) {
            $this->error('❌ Database connection failed: '.$e->getMessage());

            return;
        }

        // 2. Check Critical Tables
        $tables = ['roles', 'users', 'permissions', 'permission_role', 'shop_orders'];
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $this->info("✅ Table '$table' exists.");
            } else {
                $this->error("❌ Table '$table' MISSING!");
            }
        }

        // 3. Check Models
        try {
            $permissionCount = Permission::count();
            $this->info("✅ Permission model works. Count: $permissionCount");
        } catch (\Exception $e) {
            $this->error('❌ Permission model error: '.$e->getMessage());
        }

        try {
            // Role model might use 'roles' table.
            $roleCount = Role::count();
            $this->info("✅ Role model works. Count: $roleCount");
        } catch (\Exception $e) {
            $this->error('❌ Role model error: '.$e->getMessage());
        }

        $this->info('System Health Check Completed.');
    }
}

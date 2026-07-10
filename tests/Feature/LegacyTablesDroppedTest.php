<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class LegacyTablesDroppedTest extends TestCase
{
    /**
     * @return array<int, string>
     */
    private function legacyTables(): array
    {
        return [
            'admins',
            'payments',
            'payment_request',
            'user_password_auto',
            'settings_cost',
            'password_resets',
            'shipping_order',
        ];
    }

    public function test_drop_legacy_tables_migration_file_exists(): void
    {
        $this->assertFileExists(
            database_path('migrations/2026_07_09_194029_drop_legacy_admin_and_payment_tables.php')
        );
    }

    public function test_legacy_payment_and_admin_tables_are_not_present_on_mysql(): void
    {
        if (config('database.default') !== 'mysql') {
            $this->markTestSkipped('Legacy table drop is verified against MySQL only.');
        }

        foreach ($this->legacyTables() as $table) {
            $this->assertFalse(
                Schema::hasTable($table),
                "Legacy table [{$table}] should have been dropped."
            );
        }
    }

    public function test_auth_password_broker_uses_password_reset_tokens(): void
    {
        $this->assertSame('password_reset_tokens', config('auth.passwords.users.table'));
        $this->assertSame('password_reset_tokens', config('auth.passwords.admins.table'));
    }

    public function test_vnpay_routes_are_disabled(): void
    {
        $this->assertNull(Route::getRoutes()->getByName('customer.vnpay'));
        $this->assertNull(Route::getRoutes()->getByName('customer.payment.point'));
    }
}

<?php

namespace Tests\Feature;

use App\Models\Backend\Currency;
use App\Models\Backend\ShopCurrency;
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
        $migrationFile = '2026_07_09_194029_drop_legacy_admin_and_payment_tables.php';
        $exists = file_exists(database_path('migrations/'.$migrationFile))
            || file_exists(database_path('migrations_backup_20261007/'.$migrationFile));

        $this->assertTrue($exists, 'Migration file for dropping legacy tables should exist.');
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

    public function test_currencies_table_and_model_standardized(): void
    {
        $this->assertTrue(Schema::hasTable('currencies'), 'Table [currencies] must exist.');
        $this->assertFalse(Schema::hasTable('shop_currencies'), 'Legacy table [shop_currencies] must not exist.');

        $currency = new Currency;
        $this->assertSame('currencies', $currency->getTable());

        $shopCurrency = new ShopCurrency;
        $this->assertInstanceOf(Currency::class, $shopCurrency);
        $this->assertSame('currencies', $shopCurrency->getTable());
    }
}

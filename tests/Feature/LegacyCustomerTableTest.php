<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class LegacyCustomerTableTest extends TestCase
{
    public function test_legacy_customer_table_is_not_present(): void
    {
        if (config('database.default') !== 'sqlite') {
            $this->markTestSkipped('Schema assertion runs on sqlite test database.');
        }

        $this->assertFalse(Schema::hasTable('customer'));
    }
}

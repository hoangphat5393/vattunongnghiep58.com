<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('shop_currencies') && ! Schema::hasTable('currencies')) {
            Schema::rename('shop_currencies', 'currencies');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('currencies') && ! Schema::hasTable('shop_currencies')) {
            Schema::rename('currencies', 'shop_currencies');
        }
    }
};

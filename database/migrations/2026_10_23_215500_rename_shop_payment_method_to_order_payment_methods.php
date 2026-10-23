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
        if (Schema::hasTable('shop_payment_method') && ! Schema::hasTable('order_payment_methods')) {
            Schema::rename('shop_payment_method', 'order_payment_methods');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('order_payment_methods') && ! Schema::hasTable('shop_payment_method')) {
            Schema::rename('order_payment_methods', 'shop_payment_method');
        }
    }
};

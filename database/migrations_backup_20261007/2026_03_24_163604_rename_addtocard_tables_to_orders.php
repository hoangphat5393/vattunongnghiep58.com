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
        Schema::rename('addtocard', 'shop_orders');
        Schema::rename('addtocard_detail', 'shop_order_items');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('shop_order_items', 'addtocard_detail');
        Schema::rename('shop_orders', 'addtocard');
    }
};

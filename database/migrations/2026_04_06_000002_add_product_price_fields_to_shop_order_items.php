<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('shop_order_items')) {
            return;
        }

        Schema::table('shop_order_items', function (Blueprint $table) {
            if (! Schema::hasColumn('shop_order_items', 'product_price_id')) {
                $table->unsignedBigInteger('product_price_id')->nullable()->after('product_id');
            }
            if (! Schema::hasColumn('shop_order_items', 'price_label')) {
                $table->string('price_label', 255)->nullable()->after('product_price_id');
            }
            if (! Schema::hasColumn('shop_order_items', 'price_unit')) {
                $table->string('price_unit', 100)->nullable()->after('price_label');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('shop_order_items')) {
            return;
        }

        Schema::table('shop_order_items', function (Blueprint $table) {
            if (Schema::hasColumn('shop_order_items', 'price_unit')) {
                $table->dropColumn('price_unit');
            }
            if (Schema::hasColumn('shop_order_items', 'price_label')) {
                $table->dropColumn('price_label');
            }
            if (Schema::hasColumn('shop_order_items', 'product_price_id')) {
                $table->dropColumn('product_price_id');
            }
        });
    }
};

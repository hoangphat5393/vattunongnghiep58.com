<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('shop_orders', function (Blueprint $table) {
            $table->renameColumn('id', 'cart_id');
            $table->renameColumn('status', 'cart_status');
            $table->renameColumn('fullname', 'name');
            $table->renameColumn('phone', 'cart_phone');
            $table->renameColumn('email', 'cart_email');
            $table->renameColumn('address', 'cart_address');
            $table->renameColumn('content', 'cart_note');
            $table->renameColumn('total_price', 'cart_total');
            $table->string('cart_code', 100)->nullable();
            $table->integer('cart_payment')->default(0);
            $table->longText('cart_content')->nullable();
        });

        Schema::table('shop_order_items', function (Blueprint $table) {
            $table->renameColumn('addtocard_id', 'cart_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shop_order_items', function (Blueprint $table) {
            $table->renameColumn('cart_id', 'addtocard_id');
        });

        Schema::table('shop_orders', function (Blueprint $table) {
            $table->dropColumn(['cart_code', 'cart_payment', 'cart_content']);
            $table->renameColumn('cart_total', 'total_price');
            $table->renameColumn('cart_note', 'content');
            $table->renameColumn('cart_address', 'address');
            $table->renameColumn('cart_email', 'email');
            $table->renameColumn('cart_phone', 'phone');
            $table->renameColumn('name', 'fullname');
            $table->renameColumn('cart_status', 'status');
            $table->renameColumn('cart_id', 'id');
        });
    }
};

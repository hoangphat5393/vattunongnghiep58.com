<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Dọn dẹp dữ liệu mồ côi (Orphan records)
        DB::table('product_categories')
            ->whereNotIn('product_id', DB::table('products')->select('id'))
            ->delete();

        DB::table('product_categories')
            ->whereNotIn('category_id', DB::table('categories')->select('id'))
            ->delete();

        DB::table('shop_order_items')
            ->whereNotNull('product_price_id')
            ->whereNotIn('product_price_id', DB::table('product_prices')->select('id'))
            ->update(['product_price_id' => null]);

        DB::table('shop_orders')
            ->whereNotNull('user_id')
            ->whereNotIn('user_id', DB::table('users')->select('id'))
            ->update(['user_id' => null]);

        DB::table('shop_order_items')
            ->whereNotNull('user_id')
            ->whereNotIn('user_id', DB::table('users')->select('id'))
            ->update(['user_id' => null]);

        DB::table('pages')
            ->whereNotNull('user_id')
            ->whereNotIn('user_id', DB::table('users')->select('id'))
            ->update(['user_id' => null]);

        // 2. Đồng bộ kiểu cột (Type cast sang bigint để khớp khóa chính id của users & products)
        DB::statement('ALTER TABLE product_categories ALTER COLUMN product_id TYPE bigint');
        DB::statement('ALTER TABLE role_user ALTER COLUMN user_id TYPE bigint');
        DB::statement('ALTER TABLE shop_orders ALTER COLUMN user_id TYPE bigint');
        DB::statement('ALTER TABLE shop_order_items ALTER COLUMN product_id TYPE bigint');
        DB::statement('ALTER TABLE shop_order_items ALTER COLUMN user_id TYPE bigint');

        // 3. Thêm các ràng buộc khóa ngoại (Foreign Keys)
        Schema::table('product_categories', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->foreign('category_id')->references('id')->on('categories')->cascadeOnDelete();
        });

        Schema::table('product_prices', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });

        Schema::table('role_user', function (Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('roles')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });

        Schema::table('permission_role', function (Blueprint $table) {
            $table->foreign('permission_id')->references('id')->on('permissions')->cascadeOnDelete();
            $table->foreign('role_id')->references('id')->on('roles')->cascadeOnDelete();
        });

        Schema::table('shop_orders', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::table('shop_order_items', function (Blueprint $table) {
            $table->foreign('cart_id')->references('cart_id')->on('shop_orders')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('products')->nullOnDelete();
            $table->foreign('product_price_id')->references('id')->on('product_prices')->nullOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::table('menu_items', function (Blueprint $table) {
            $table->foreign('menu_id')->references('id')->on('menus')->cascadeOnDelete();
        });

        Schema::table('album_items', function (Blueprint $table) {
            $table->foreign('album_id')->references('id')->on('albums')->cascadeOnDelete();
        });

        Schema::table('agent_conversation_messages', function (Blueprint $table) {
            $table->foreign('conversation_id')->references('id')->on('agent_conversations')->cascadeOnDelete();
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('agent_conversation_messages', function (Blueprint $table) {
            $table->dropForeign(['conversation_id']);
        });

        Schema::table('album_items', function (Blueprint $table) {
            $table->dropForeign(['album_id']);
        });

        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropForeign(['menu_id']);
        });

        Schema::table('shop_order_items', function (Blueprint $table) {
            $table->dropForeign(['cart_id']);
            $table->dropForeign(['product_id']);
            $table->dropForeign(['product_price_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::table('shop_orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('permission_role', function (Blueprint $table) {
            $table->dropForeign(['permission_id']);
            $table->dropForeign(['role_id']);
        });

        Schema::table('role_user', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::table('product_prices', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['category_id']);
        });
    }
};

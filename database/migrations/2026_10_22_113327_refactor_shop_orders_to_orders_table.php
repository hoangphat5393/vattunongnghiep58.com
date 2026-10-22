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
        $driver = DB::connection()->getDriverName();

        // 1. Gỡ Foreign Key cũ trên shop_order_items
        Schema::table('shop_order_items', function (Blueprint $table) {
            $table->dropForeign(['cart_id']);
        });

        // Gỡ index legacy nếu tồn tại
        Schema::table('shop_order_items', function (Blueprint $table) {
            try {
                $table->dropIndex('addtocard_detail_addtocard_id_foreign');
            } catch (Throwable $e) {
                // Bỏ qua nếu index không tồn tại
            }
        });

        // 2. Chuẩn hóa kiểu dữ liệu cho cart_status và các trường nullable
        if ($driver === 'pgsql') {
            $colType = DB::selectOne("SELECT data_type FROM information_schema.columns WHERE table_name = 'shop_orders' AND column_name = 'cart_status'")?->data_type;
            if ($colType === 'boolean') {
                DB::statement('ALTER TABLE shop_orders ALTER COLUMN cart_status TYPE smallint USING (CASE WHEN cart_status IS TRUE THEN 1 WHEN cart_status IS FALSE THEN 0 ELSE 0 END)');
            }
            DB::statement('ALTER TABLE shop_orders ALTER COLUMN cart_status SET DEFAULT 0');
            DB::statement('ALTER TABLE shop_order_items ALTER COLUMN product_id DROP NOT NULL');
            DB::statement('ALTER TABLE shop_order_items ALTER COLUMN user_id DROP DEFAULT');
            DB::statement('ALTER TABLE shop_order_items ALTER COLUMN user_id DROP NOT NULL');
            DB::statement('UPDATE shop_order_items SET user_id = NULL WHERE user_id = 0');
            DB::statement('UPDATE shop_orders SET user_id = NULL WHERE user_id = 0');
        } else {
            Schema::table('shop_orders', function (Blueprint $table) {
                $table->smallInteger('cart_status')->default(0)->change();
            });
            Schema::table('shop_order_items', function (Blueprint $table) {
                $table->unsignedBigInteger('product_id')->nullable()->change();
                $table->unsignedBigInteger('user_id')->nullable()->default(null)->change();
            });
            DB::table('shop_order_items')->where('user_id', 0)->update(['user_id' => null]);
            DB::table('shop_orders')->where('user_id', 0)->update(['user_id' => null]);
        }

        // 3. Đổi tên các cột trong shop_orders
        Schema::table('shop_orders', function (Blueprint $table) {
            $table->renameColumn('cart_id', 'id');
            $table->renameColumn('cart_code', 'code');
            $table->renameColumn('cart_phone', 'phone');
            $table->renameColumn('cart_email', 'email');
            $table->renameColumn('cart_address', 'address');
            $table->renameColumn('cart_note', 'note');
            $table->renameColumn('cart_total', 'total');
            $table->renameColumn('cart_status', 'status');
            $table->renameColumn('cart_payment', 'payment_status');
        });

        // 4. Đổi tên các cột trong shop_order_items
        Schema::table('shop_order_items', function (Blueprint $table) {
            $table->renameColumn('cart_id', 'order_id');
            if (Schema::hasColumn('shop_order_items', 'quanlity')) {
                $table->renameColumn('quanlity', 'quantity');
            }
        });

        // 5. Đổi tên 2 bảng thành orders và order_items
        Schema::rename('shop_orders', 'orders');
        Schema::rename('shop_order_items', 'order_items');

        // 6. Cập nhật tên Sequence trên PostgreSQL
        if ($driver === 'pgsql') {
            DB::statement('ALTER SEQUENCE IF EXISTS shop_orders_cart_id_seq RENAME TO orders_id_seq');
            DB::statement('ALTER SEQUENCE IF EXISTS shop_order_items_id_seq RENAME TO order_items_id_seq');
        }

        // 7. Tái tạo Index và Foreign Key chuẩn mới
        Schema::table('order_items', function (Blueprint $table) {
            $table->index('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::connection()->getDriverName();

        // 1. Gỡ FK và Index mới
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropIndex(['order_id']);
        });

        // 2. Đổi lại tên bảng
        Schema::rename('orders', 'shop_orders');
        Schema::rename('order_items', 'shop_order_items');

        // 3. Đổi lại tên cột
        Schema::table('shop_orders', function (Blueprint $table) {
            $table->renameColumn('id', 'cart_id');
            $table->renameColumn('code', 'cart_code');
            $table->renameColumn('phone', 'cart_phone');
            $table->renameColumn('email', 'cart_email');
            $table->renameColumn('address', 'cart_address');
            $table->renameColumn('note', 'cart_note');
            $table->renameColumn('total', 'cart_total');
            $table->renameColumn('status', 'cart_status');
            $table->renameColumn('payment_status', 'cart_payment');
        });

        Schema::table('shop_order_items', function (Blueprint $table) {
            $table->renameColumn('order_id', 'cart_id');
            if (Schema::hasColumn('shop_order_items', 'quantity')) {
                $table->renameColumn('quantity', 'quanlity');
            }
        });

        // 4. Đổi lại Sequence PostgreSQL
        if ($driver === 'pgsql') {
            DB::statement('ALTER SEQUENCE IF EXISTS orders_id_seq RENAME TO shop_orders_cart_id_seq');
            DB::statement('ALTER SEQUENCE IF EXISTS order_items_id_seq RENAME TO shop_order_items_id_seq');
        }

        // 5. Tái tạo lại FK cũ
        Schema::table('shop_order_items', function (Blueprint $table) {
            $table->index('cart_id', 'addtocard_detail_addtocard_id_foreign');
            $table->foreign('cart_id')->references('cart_id')->on('shop_orders')->cascadeOnDelete();
        });
    }
};

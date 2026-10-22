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
        Schema::dropIfExists('shop_order_payment_status');
        Schema::dropIfExists('shop_order_status');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('shop_order_status')) {
            Schema::create('shop_order_status', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 100)->nullable();
                $table->string('name_en', 100)->nullable();
            });

            DB::table('shop_order_status')->insert([
                ['id' => 1, 'name' => 'Chờ xác nhận', 'name_en' => 'Wait for confirmation'],
                ['id' => 2, 'name' => 'Đã tiếp nhận', 'name_en' => 'Processing'],
                ['id' => 3, 'name' => 'Đã hủy', 'name_en' => 'Canceled'],
                ['id' => 4, 'name' => 'Đang giao hàng', 'name_en' => 'Delivery in progress'],
                ['id' => 5, 'name' => 'Hoàn thành', 'name_en' => 'Done'],
            ]);
        }

        if (! Schema::hasTable('shop_order_payment_status')) {
            Schema::create('shop_order_payment_status', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 100)->nullable();
                $table->string('name_en', 100)->nullable();
            });

            DB::table('shop_order_payment_status')->insert([
                ['id' => 1, 'name' => 'Đã thanh toán', 'name_en' => 'Wait for confirmation'],
                ['id' => 2, 'name' => 'Chưa thanh toán', 'name_en' => 'Processing'],
                ['id' => 3, 'name' => 'Đang chờ', 'name_en' => 'Pending'],
                ['id' => 4, 'name' => 'Hoàn tiền', 'name_en' => 'Refunded'],
                ['id' => 5, 'name' => 'Hết hạn', 'name_en' => 'Expired'],
                ['id' => 6, 'name' => 'Thanh toán lỗi', 'name_en' => 'Failed'],
            ]);
        }
    }
};

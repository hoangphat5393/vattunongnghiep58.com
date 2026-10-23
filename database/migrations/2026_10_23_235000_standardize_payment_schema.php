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
        // 1. Chuyển bảng danh mục cổng về tên chuẩn payment_methods
        if (Schema::hasTable('order_payment_methods') && ! Schema::hasTable('payment_methods')) {
            Schema::rename('order_payment_methods', 'payment_methods');
        } elseif (Schema::hasTable('shop_payment_method') && ! Schema::hasTable('payment_methods')) {
            Schema::rename('shop_payment_method', 'payment_methods');
        }

        // 2. Bổ sung trường tài chính paid_amount vào bảng orders
        if (Schema::hasTable('orders') && ! Schema::hasColumn('orders', 'paid_amount')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->decimal('paid_amount', 15, 2)->default(0)->after('total');
            });
        }

        // 3. Tạo bảng nhật ký giao dịch thanh toán đa cổng order_payments
        if (! Schema::hasTable('order_payments')) {
            Schema::create('order_payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
                $table->string('gateway', 50)->index();
                $table->decimal('amount', 15, 2);
                $table->string('currency', 10)->default('VND');
                $table->string('transaction_code', 100)->nullable()->index();
                $table->string('status', 30)->default('pending')->index();
                $table->jsonb('payload')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_payments');

        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'paid_amount')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('paid_amount');
            });
        }

        if (Schema::hasTable('payment_methods') && ! Schema::hasTable('order_payment_methods')) {
            Schema::rename('payment_methods', 'order_payment_methods');
        }
    }
};

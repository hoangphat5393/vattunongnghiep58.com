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
        Schema::create('shop_order_items', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('cart_id')->index('addtocard_detail_addtocard_id_foreign');
            $table->integer('product_id');
            $table->unsignedBigInteger('product_price_id')->nullable();
            $table->string('price_label')->nullable();
            $table->string('price_unit', 100)->nullable();
            $table->decimal('price', 20, 6)->default(0);
            $table->integer('quanlity')->nullable();
            $table->integer('user_id')->nullable()->default(0);
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_order_items');
    }
};

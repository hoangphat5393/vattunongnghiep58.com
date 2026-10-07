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
        Schema::create('shop_orders', function (Blueprint $table) {
            $table->integer('cart_id', true);
            $table->string('firstname', 100)->nullable()->default('0');
            $table->string('lastname', 100)->nullable()->default('0');
            $table->string('name', 200)->nullable();
            $table->string('cart_phone', 50)->nullable();
            $table->string('cart_email', 300)->nullable();
            $table->longText('cart_address')->nullable();
            $table->longText('cart_note')->nullable();
            $table->double('cart_total')->nullable();
            $table->integer('discount')->nullable();
            $table->boolean('cart_status')->nullable();
            $table->string('shipping_type', 191)->nullable();
            $table->string('shipping_fee', 1000)->nullable();
            $table->float('shipping_cost')->nullable()->default(0);
            $table->string('city', 191)->nullable();
            $table->string('province', 191)->nullable();
            $table->string('ward', 191)->nullable();
            $table->string('country_code', 191)->nullable();
            $table->string('postal_code', 191)->nullable();
            $table->integer('user_id')->nullable();
            $table->string('payment_id', 50)->nullable();
            $table->string('payment_method', 1000)->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->string('cart_code', 100)->nullable();
            $table->integer('cart_payment')->default(0);
            $table->longText('cart_content')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_orders');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Drop legacy tables superseded by users ACL, offline checkout, and OTP password flow.
     * PayPal/VNPay not used in this project phase.
     */
    public function up(): void
    {
        Schema::dropIfExists('payment_request');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('shipping_order');
        Schema::dropIfExists('user_password_auto');
        Schema::dropIfExists('settings_cost');
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('admins');
    }

    public function down(): void
    {
        if (! Schema::hasTable('admins')) {
            Schema::create('admins', function (Blueprint $table) {
                $table->id();
                $table->string('fullname')->nullable();
                $table->string('name')->nullable();
                $table->string('username')->nullable()->unique();
                $table->string('email')->unique();
                $table->string('phone')->nullable()->unique();
                $table->string('password');
                $table->integer('admin_level')->default(1);
                $table->tinyInteger('status')->default(1);
                $table->rememberToken();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('password_resets')) {
            Schema::create('password_resets', function (Blueprint $table) {
                $table->string('email');
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }

        if (! Schema::hasTable('settings_cost')) {
            Schema::create('settings_cost', function (Blueprint $table) {
                $table->integer('id')->primary();
                $table->string('name', 2000)->nullable();
                $table->string('title', 2000)->nullable();
                $table->longText('content')->nullable();
                $table->string('type', 50)->nullable();
                $table->integer('status')->default(0);
                $table->integer('sort')->default(0);
                $table->dateTime('created_at')->nullable();
                $table->dateTime('updated_at')->nullable();
            });
        }

        if (! Schema::hasTable('user_password_auto')) {
            Schema::create('user_password_auto', function (Blueprint $table) {
                $table->integer('id')->primary();
                $table->string('email', 191);
                $table->string('password', 100)->nullable();
                $table->integer('status');
                $table->dateTime('created_at');
                $table->dateTime('updated_at');
            });
        }

        if (! Schema::hasTable('shipping_order')) {
            Schema::create('shipping_order', function (Blueprint $table) {
                $table->integer('id')->primary();
                $table->string('id_shipping', 191);
                $table->integer('cart_id');
                $table->string('type_shipping', 191);
                $table->string('shipping_status', 191)->nullable();
                $table->dateTime('created_at');
                $table->dateTime('updated_at');
            });
        }

        if (! Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->unsignedBigInteger('id')->primary();
                $table->string('payment_id');
                $table->integer('post_id');
                $table->string('payer_id');
                $table->string('payer_email');
                $table->double('amount', 10, 2);
                $table->string('currency');
                $table->string('payment_status');
                $table->string('type', 100)->nullable();
                $table->timestamp('date_end')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('payment_request')) {
            Schema::create('payment_request', function (Blueprint $table) {
                $table->integer('id')->primary();
                $table->integer('user_id')->nullable();
                $table->integer('cart_id')->default(0);
                $table->float('amount')->nullable();
                $table->string('status', 100)->nullable();
                $table->string('payment_gate', 100)->nullable();
                $table->string('payment_method', 100)->nullable();
                $table->string('session_id', 200)->nullable();
                $table->string('transaction_status', 100)->nullable();
                $table->string('transaction_code', 100)->nullable();
                $table->string('bank_code', 100)->nullable();
                $table->string('transaction_url', 500)->nullable();
                $table->mediumText('content')->nullable();
                $table->dateTime('created_at')->nullable();
                $table->dateTime('updated_at')->nullable();
            });
        }
    }
};

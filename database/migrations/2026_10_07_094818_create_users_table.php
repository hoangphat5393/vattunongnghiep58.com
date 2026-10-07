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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username')->nullable();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('fullname', 200)->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('wallet')->nullable()->default(0);
            $table->mediumText('about_me')->nullable();
            $table->string('provider_id')->nullable();
            $table->string('provider')->nullable()->comment('Đăng ký lần đầu bằng tài khoản nào?');
            $table->date('birthday')->nullable();
            $table->string('phone', 15)->nullable();
            $table->string('full_phone', 15)->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->string('address')->nullable();
            $table->string('country', 50)->nullable();
            $table->string('province')->nullable();
            $table->string('district')->nullable();
            $table->string('city')->nullable();
            $table->integer('postal_code')->nullable();
            $table->string('ward')->nullable();
            $table->string('avatar')->nullable();
            $table->integer('status')->nullable()->default(0);
            $table->boolean('verified')->nullable()->default(false);
            $table->string('stripe_id')->nullable();
            $table->string('card_brand')->nullable();
            $table->string('card_last_four', 4)->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->integer('admin_level')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

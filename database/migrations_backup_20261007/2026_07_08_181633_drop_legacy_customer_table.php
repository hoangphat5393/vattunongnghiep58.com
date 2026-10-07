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
        Schema::dropIfExists('customer');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('customer')) {
            return;
        }

        Schema::create('customer', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 191);
            $table->string('full_name', 255)->nullable();
            $table->string('email', 191)->unique();
            $table->string('provider_id', 255)->nullable();
            $table->string('provider', 255)->nullable();
            $table->text('about_me')->nullable();
            $table->date('birthday');
            $table->string('phone', 11);
            $table->mediumText('address');
            $table->string('province', 255)->nullable();
            $table->string('district', 255)->nullable();
            $table->string('ward', 255)->nullable();
            $table->mediumText('avatar')->nullable();
            $table->string('password', 255);
            $table->boolean('status')->default(false);
            $table->string('remember_token', 191);
            $table->timestamps();
        });
    }
};

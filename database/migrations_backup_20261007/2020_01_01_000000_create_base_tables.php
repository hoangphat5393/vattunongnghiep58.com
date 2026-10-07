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
        if (! Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('username')->unique()->nullable();
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->string('fullname')->nullable();
                $table->string('phone')->nullable();
                $table->string('address')->nullable();
                $table->string('image')->nullable();
                $table->tinyInteger('status')->default(1);
                $table->rememberToken();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }

        if (! Schema::hasTable('pages')) {
            Schema::create('pages', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('slug')->nullable();
                $table->text('description')->nullable();
                $table->longText('content')->nullable();
                $table->string('image')->nullable();
                $table->tinyInteger('status')->default(1);
                $table->unsignedBigInteger('user_id')->nullable();
                // 'cocojt' will be added by later migration
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('posts')) {
            Schema::create('posts', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('slug')->nullable();
                $table->text('description')->nullable();
                $table->longText('content')->nullable();
                $table->string('image')->nullable();
                $table->string('seo_title')->nullable();
                $table->string('seo_keyword')->nullable();
                $table->text('seo_description')->nullable();
                $table->integer('sort')->default(0);
                $table->tinyInteger('status')->default(1);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('post_categories')) {
            Schema::create('post_categories', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('post_id');
                $table->unsignedBigInteger('category_id');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('admin_menus')) {
            Schema::create('admin_menus', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('parent_id')->default(0);
                $table->string('name')->nullable();
                $table->string('uri')->nullable();
                $table->string('icon')->nullable();
                $table->integer('sort')->default(0);
                $table->tinyInteger('hidden')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_menus');
        Schema::dropIfExists('post_categories');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};

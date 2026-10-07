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
        Schema::create('categories', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('slug')->nullable()->index();
            $table->string('name', 1000)->nullable();
            $table->longText('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->string('cover')->nullable();
            $table->integer('parent')->nullable()->default(0)->index();
            $table->boolean('hot')->nullable()->default(false);
            $table->integer('sort')->nullable()->default(0);
            $table->boolean('status')->nullable()->default(false);
            $table->integer('user_id')->nullable();
            $table->text('seo_title')->nullable();
            $table->text('seo_keyword')->nullable();
            $table->text('seo_description')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();

            $table->index(['status', 'id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};

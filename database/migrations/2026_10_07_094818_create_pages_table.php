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
        Schema::create('pages', function (Blueprint $table) {
            $table->integer('id', true);
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('slug')->nullable()->index();
            $table->string('type')->default('page');
            $table->string('name')->nullable();
            $table->string('name_en')->nullable();
            $table->longText('description')->nullable();
            $table->longText('description_en')->nullable();
            $table->longText('content')->nullable();
            $table->longText('content_en')->nullable();
            $table->longText('content2')->nullable();
            $table->longText('content2_en')->nullable();
            $table->longText('content3')->nullable();
            $table->longText('content3_en')->nullable();
            $table->longText('content4')->nullable();
            $table->longText('content4_en')->nullable();
            $table->string('image')->nullable();
            $table->string('cover')->nullable();
            $table->string('icon')->nullable();
            $table->integer('sort')->nullable()->default(0);
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->integer('status')->nullable()->default(0);
            $table->integer('show_footer')->nullable()->default(0);
            $table->string('template', 50)->nullable();
            $table->string('parent', 100)->nullable()->default('root');
            $table->string('seo_title', 200)->nullable();
            $table->mediumText('seo_keyword')->nullable();
            $table->mediumText('seo_description')->nullable();

            $table->index(['status', 'id']);
            $table->index(['type', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};

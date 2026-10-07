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
        Schema::create('album_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('album_id')->index('album_items_album_id_foreign');
            $table->string('sub_name', 500)->nullable();
            $table->string('sub_name_en', 500)->nullable();
            $table->string('name', 500)->nullable();
            $table->string('name_en', 500)->nullable();
            $table->text('description')->nullable();
            $table->text('description_en')->nullable();
            $table->text('image')->nullable();
            $table->text('image_en')->nullable();
            $table->text('video')->nullable();
            $table->text('link')->nullable();
            $table->text('link_en')->nullable();
            $table->text('link_name')->nullable();
            $table->text('link_name_en')->nullable();
            $table->string('target', 50)->default('_blank');
            $table->integer('sort')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('album_items');
    }
};

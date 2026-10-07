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
        // Drop page_categories table
        Schema::dropIfExists('page_categories');

        // Drop post_categories table if it exists
        Schema::dropIfExists('post_categories');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate page_categories table
        if (! Schema::hasTable('page_categories')) {
            Schema::create('page_categories', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('page_id');
                $table->unsignedBigInteger('category_id');
                // No foreign keys were added in the previous migration, so we don't add them here to be safe
            });
        }

        // Recreate post_categories table (simplified version as backup)
        if (! Schema::hasTable('post_categories')) {
            Schema::create('post_categories', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('post_id');
                $table->unsignedBigInteger('category_id');
            });
        }
    }
};

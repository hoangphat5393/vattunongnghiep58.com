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
        Schema::create('contacts', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('type', 50)->nullable()->default('contact');
            $table->string('name', 200)->nullable();
            $table->tinyText('address');
            $table->string('email', 200)->nullable();
            $table->string('phone', 100)->nullable();
            $table->string('file', 200)->nullable();
            $table->text('content')->nullable();
            $table->integer('sort')->nullable()->default(0);
            $table->boolean('status')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};

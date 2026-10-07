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
        Schema::create('countries', function (Blueprint $table) {
            $table->integer('id', true);
            $table->char('code', 2);
            $table->char('code_3', 3)->nullable();
            $table->string('name', 100);
            $table->integer('phone');
            $table->string('symbol', 10)->nullable();
            $table->string('capital', 80)->nullable();
            $table->string('currency', 3)->nullable();
            $table->string('continent', 30)->nullable();
            $table->string('continent_code', 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};

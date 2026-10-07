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
        Schema::table('products', function (Blueprint $table) {
            $columnsToDrop = [];
            foreach (['promotion', 'date_start', 'date_end'] as $col) {
                if (Schema::hasColumn('products', $col)) {
                    $columnsToDrop[] = $col;
                }
            }

            if (! empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'promotion')) {
                $table->string('promotion')->nullable()->after('price');
            }
            if (! Schema::hasColumn('products', 'date_start')) {
                $table->string('date_start')->nullable()->after('unit');
            }
            if (! Schema::hasColumn('products', 'date_end')) {
                $table->string('date_end')->nullable()->after('date_start');
            }
        });
    }
};

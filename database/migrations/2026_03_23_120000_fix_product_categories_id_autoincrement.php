<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng pivot product_categories: cột id phải AUTO_INCREMENT để sync() không cần gửi id.
     */
    public function up(): void
    {
        if (! Schema::hasTable('product_categories')) {
            return;
        }

        if (! Schema::hasColumn('product_categories', 'id')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();
        if ($driver !== 'mysql' && $driver !== 'mariadb') {
            return;
        }

        try {
            DB::statement('ALTER TABLE `product_categories` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        } catch (Throwable $e) {
            // Có thể đã AUTO_INCREMENT hoặc khóa chính khác — bỏ qua
        }
    }

    public function down(): void
    {
        //
    }
};

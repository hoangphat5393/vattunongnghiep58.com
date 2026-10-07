<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Khôi phục AUTO_INCREMENT cho products.id.
     *
     * Trước đó form gửi id=0 có thể tạo bản ghi id=0; khi ALTER sang AUTO_INCREMENT
     * MySQL có thể báo trùng khóa — cần gỡ id=0 / pivot lỗi trước.
     */
    public function up(): void
    {
        if (! Schema::hasTable('products')) {
            return;
        }

        if (! Schema::hasColumn('products', 'id')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();
        if ($driver !== 'mysql' && $driver !== 'mariadb') {
            return;
        }

        if (Schema::hasTable('product_categories')) {
            DB::table('product_categories')->where('product_id', 0)->delete();
        }

        $hasZero = DB::table('products')->where('id', 0)->exists();
        if ($hasZero) {
            $maxId = (int) DB::table('products')->where('id', '>', 0)->max('id');
            $newId = max(1, $maxId + 1);
            DB::table('products')->where('id', 0)->update(['id' => $newId]);
        }

        try {
            DB::statement('ALTER TABLE `products` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        } catch (Throwable $e) {
            // Một số phiên bản / engine: thử INT thay vì BIGINT
            try {
                DB::statement('ALTER TABLE `products` MODIFY `id` INT UNSIGNED NOT NULL AUTO_INCREMENT');
            } catch (Throwable $e2) {
                throw $e;
            }
        }

        $next = (int) DB::table('products')->max('id');
        if ($next > 0) {
            DB::statement('ALTER TABLE `products` AUTO_INCREMENT = '.($next + 1));
        }
    }

    public function down(): void
    {
        //
    }
};

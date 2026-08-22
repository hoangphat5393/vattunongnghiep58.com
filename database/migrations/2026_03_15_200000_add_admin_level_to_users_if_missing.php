<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thêm cột admin_level vào bảng users nếu chưa có.
     * Admin đăng nhập và phân quyền dùng bảng users (Backend\User), không còn bảng admins.
     * Cột admin_level giữ tương thích với code cũ (vd: OrderController), giá trị 99999 = super admin.
     */
    public function up(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        if (! Schema::hasColumn('users', 'admin_level')) {
            Schema::table('users', function (Blueprint $table) {
                $table->integer('admin_level')->default(1);
            });
        }

        // Gán admin_level = 99999 cho user có role administrator (nếu có bảng role_user, roles)
        if (Schema::hasTable('roles') && Schema::hasTable('role_user')) {
            $adminRole = DB::table('roles')->where('slug', 'administrator')->first();
            if ($adminRole) {
                $userIds = DB::table('role_user')
                    ->where('role_id', $adminRole->id)
                    ->pluck('user_id');
                if ($userIds->isNotEmpty()) {
                    DB::table('users')
                        ->whereIn('id', $userIds)
                        ->update(['admin_level' => 99999]);
                }
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'admin_level')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('admin_level');
            });
        }
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Thêm cột slug vào bảng roles nếu chưa có (để ACL và đăng nhập admin hoạt động đúng).
     */
    public function up(): void
    {
        if (! Schema::hasTable('roles')) {
            return;
        }

        if (! Schema::hasColumn('roles', 'slug')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->string('slug')->nullable()->unique()->after('name');
            });
            // Gán slug cho các role theo name
            DB::table('roles')->where('name', 'Administrator')->update(['slug' => 'administrator']);
            foreach (DB::table('roles')->whereNull('slug')->get() as $role) {
                $slug = Str::slug($role->name ?: 'role-'.$role->id);
                if ($slug) {
                    DB::table('roles')->where('id', $role->id)->update(['slug' => $slug]);
                }
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('roles') && Schema::hasColumn('roles', 'slug')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }
    }
};

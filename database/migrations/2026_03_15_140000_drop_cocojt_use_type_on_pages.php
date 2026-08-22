<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bỏ cột cocojt, dùng type: bài viết (từ post) có type='post', trang thường có type='page'.
     */
    public function up(): void
    {
        if (! Schema::hasTable('pages')) {
            return;
        }

        if (! Schema::hasColumn('pages', 'type')) {
            Schema::table('pages', function (Blueprint $table) {
                $table->string('type')->default('page')->after('id');
            });
        }

        if (Schema::hasColumn('pages', 'cocojt')) {
            DB::table('pages')->where('cocojt', 'post')->update(['type' => 'post']);
            DB::table('pages')->whereNull('type')->orWhere('type', '')->update(['type' => 'page']);
            Schema::table('pages', function (Blueprint $table) {
                $table->dropColumn('cocojt');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('pages')) {
            return;
        }
        if (! Schema::hasColumn('pages', 'cocojt')) {
            Schema::table('pages', function (Blueprint $table) {
                $table->string('cocojt', 50)->nullable()->after('id')->index();
            });
            DB::table('pages')->where('type', 'post')->update(['cocojt' => 'post']);
        }
    }
};

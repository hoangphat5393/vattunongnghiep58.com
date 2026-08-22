<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bỏ cột type trong categories; đổi title, title_en trong pages thành name, name_en.
     */
    public function up(): void
    {
        if (Schema::hasTable('categories') && Schema::hasColumn('categories', 'type')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('type');
            });
        }

        if (! Schema::hasTable('pages')) {
            return;
        }

        $driver = DB::getDriverName();
        if (Schema::hasColumn('pages', 'title')) {
            if (Schema::hasColumn('pages', 'name')) {
                DB::table('pages')->whereNotNull('title')->where(function ($q) {
                    $q->whereNull('name')->orWhere('name', '');
                })->update(['name' => DB::raw('title')]);
                Schema::table('pages', function (Blueprint $table) {
                    $table->dropColumn('title');
                });
            } elseif ($driver === 'mysql') {
                DB::statement('ALTER TABLE pages CHANGE title name VARCHAR(255) NULL');
            } else {
                Schema::table('pages', function (Blueprint $table) {
                    $table->string('name')->nullable()->after('id');
                });
                DB::table('pages')->update(['name' => DB::raw('title')]);
                Schema::table('pages', function (Blueprint $table) {
                    $table->dropColumn('title');
                });
            }
        }
        if (Schema::hasColumn('pages', 'title_en')) {
            if (Schema::hasColumn('pages', 'name_en')) {
                DB::table('pages')->whereNotNull('title_en')->update(['name_en' => DB::raw('title_en')]);
                Schema::table('pages', function (Blueprint $table) {
                    $table->dropColumn('title_en');
                });
            } elseif ($driver === 'mysql') {
                DB::statement('ALTER TABLE pages CHANGE title_en name_en VARCHAR(255) NULL');
            } else {
                Schema::table('pages', function (Blueprint $table) {
                    $table->string('name_en')->nullable();
                });
                DB::table('pages')->update(['name_en' => DB::raw('title_en')]);
                Schema::table('pages', function (Blueprint $table) {
                    $table->dropColumn('title_en');
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('categories') && ! Schema::hasColumn('categories', 'type')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->string('type')->nullable()->after('id');
            });
        }

        if (! Schema::hasTable('pages')) {
            return;
        }
        $driver = DB::getDriverName();
        if (Schema::hasColumn('pages', 'name') && $driver === 'mysql') {
            DB::statement('ALTER TABLE pages CHANGE name title VARCHAR(255) NULL');
        }
        if (Schema::hasColumn('pages', 'name_en') && $driver === 'mysql') {
            DB::statement('ALTER TABLE pages CHANGE name_en title_en VARCHAR(255) NULL');
        }
    }
};

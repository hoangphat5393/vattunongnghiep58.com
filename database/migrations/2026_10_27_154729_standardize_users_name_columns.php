<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Thêm cột 'name' nếu chưa có
        if (! Schema::hasColumn('users', 'name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('name', 255)->nullable()->after('id');
            });
        }

        // 2. Chuyển dữ liệu từ fullname sang name
        if (Schema::hasColumn('users', 'fullname')) {
            DB::statement('UPDATE users SET name = fullname WHERE name IS NULL AND fullname IS NOT NULL');
        }

        // Nếu cả name và fullname đều rỗng mà có firstname/lastname thì ghép lại
        if (Schema::hasColumn('users', 'firstname') && Schema::hasColumn('users', 'lastname')) {
            DB::statement("UPDATE users SET name = TRIM(CONCAT(COALESCE(lastname, ''), ' ', COALESCE(firstname, ''))) WHERE (name IS NULL OR name = '') AND (firstname IS NOT NULL OR lastname IS NOT NULL)");
        }

        // 3. Drop các cột cũ không còn dùng
        Schema::table('users', function (Blueprint $table) {
            $dropColumns = [];
            if (Schema::hasColumn('users', 'fullname')) {
                $dropColumns[] = 'fullname';
            }
            if (Schema::hasColumn('users', 'firstname')) {
                $dropColumns[] = 'firstname';
            }
            if (Schema::hasColumn('users', 'lastname')) {
                $dropColumns[] = 'lastname';
            }
            if (! empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('fullname', 255)->nullable();
            $table->string('firstname', 255)->nullable();
            $table->string('lastname', 255)->nullable();
        });

        if (Schema::hasColumn('users', 'name')) {
            DB::statement('UPDATE users SET fullname = name WHERE fullname IS NULL AND name IS NOT NULL');
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('name');
            });
        }
    }
};

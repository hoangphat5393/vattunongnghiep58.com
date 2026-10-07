<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('email_templates')) {
            return;
        }

        if (! Schema::hasColumn('email_templates', 'code')) {
            Schema::table('email_templates', function (Blueprint $table) {
                $table->string('code', 100)->nullable()->after('name');
            });
        }

        if (Schema::hasColumn('email_templates', 'group')) {
            DB::table('email_templates')
                ->whereNull('code')
                ->update([
                    'code' => DB::raw('`group`'),
                ]);
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('email_templates')) {
            return;
        }

        if (Schema::hasColumn('email_templates', 'code')) {
            Schema::table('email_templates', function (Blueprint $table) {
                $table->dropColumn('code');
            });
        }
    }
};

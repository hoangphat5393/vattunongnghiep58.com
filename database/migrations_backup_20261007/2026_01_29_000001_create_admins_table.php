<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('fullname')->nullable();
            $table->string('name')->nullable();
            $table->string('username')->nullable()->unique();
            $table->string('email')->unique();
            $table->string('phone')->nullable()->unique();
            $table->string('password');
            $table->integer('admin_level')->default(1);
            $table->tinyInteger('status')->default(1);
            $table->rememberToken();
            $table->timestamps();
        });

        /*
        try {
            if (!DB::table('admins')->where('email', 'admin@local')->exists()) {
                DB::table('admins')->insert([
                    'fullname' => 'Administrator',
                    'name' => 'Administrator',
                    'username' => 'admin',
                    'email' => 'admin@local',
                    'phone' => null,
                    'password' => Hash::make('admin123'),
                    'admin_level' => 99999,
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Throwable $e) {
        }
        */
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};

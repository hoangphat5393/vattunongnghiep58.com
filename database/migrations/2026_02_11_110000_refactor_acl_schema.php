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
        // 1. Users Table
        if (! Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('username')->unique();
                $table->string('email')->unique();
                $table->string('password');
                $table->string('fullname')->nullable();
                $table->string('phone')->nullable();
                $table->string('address')->nullable();
                $table->integer('status')->default(1);
                $table->rememberToken();
                $table->timestamps();
            });
        } else {
            Schema::table('users', function (Blueprint $table) {
                if (! Schema::hasColumn('users', 'username')) {
                    $table->string('username')->nullable()->unique();
                }
                if (! Schema::hasColumn('users', 'fullname')) {
                    $table->string('fullname')->nullable();
                }
                if (! Schema::hasColumn('users', 'phone')) {
                    $table->string('phone')->nullable();
                }
                if (! Schema::hasColumn('users', 'address')) {
                    $table->string('address')->nullable();
                }
                if (! Schema::hasColumn('users', 'status')) {
                    $table->integer('status')->default(1);
                }
            });
        }

        // 2. Roles Table
        if (! Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('roles', function (Blueprint $table) {
                if (! Schema::hasColumn('roles', 'slug')) {
                    $table->string('slug')->unique();
                }
                if (! Schema::hasColumn('roles', 'description')) {
                    $table->text('description')->nullable();
                }
            });
        }

        // 3. Permissions Table
        if (! Schema::hasTable('permissions')) {
            Schema::create('permissions', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('resource')->nullable();
                $table->string('action')->nullable();
                $table->string('http_uri')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('permissions', function (Blueprint $table) {
                if (! Schema::hasColumn('permissions', 'slug')) {
                    $table->string('slug')->unique();
                }
                if (! Schema::hasColumn('permissions', 'resource')) {
                    $table->string('resource')->nullable();
                }
                if (! Schema::hasColumn('permissions', 'action')) {
                    $table->string('action')->nullable();
                }
                if (! Schema::hasColumn('permissions', 'http_uri')) {
                    $table->string('http_uri')->nullable();
                }
            });
        }

        // 4. Role User Pivot
        if (! Schema::hasTable('role_user')) {
            Schema::create('role_user', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('role_id');
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            });
        } else {
            // If table exists, ensure FKs exist?
            // Difficult to check constraint existence portably.
            // Assuming if table exists, it's correct or we leave it.
            // But we need to ensure columns exist.
            Schema::table('role_user', function (Blueprint $table) {
                if (! Schema::hasColumn('role_user', 'user_id')) {
                    $table->unsignedBigInteger('user_id');
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                }
                if (! Schema::hasColumn('role_user', 'role_id')) {
                    $table->unsignedBigInteger('role_id');
                    $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
                }
            });
        }

        // 5. Permission Role Pivot
        if (! Schema::hasTable('permission_role')) {
            Schema::create('permission_role', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('permission_id');
                $table->unsignedBigInteger('role_id');
                $table->timestamps();

                $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            });
        } else {
            Schema::table('permission_role', function (Blueprint $table) {
                if (! Schema::hasColumn('permission_role', 'permission_id')) {
                    $table->unsignedBigInteger('permission_id');
                    $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
                }
                if (! Schema::hasColumn('permission_role', 'role_id')) {
                    $table->unsignedBigInteger('role_id');
                    $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
                }
            });
        }

        // 6. Data Migration (Admins -> Users)
        if (Schema::hasTable('admins')) {
            $admins = DB::table('admins')->get();
            foreach ($admins as $admin) {
                $exists = DB::table('users')->where('email', $admin->email)->exists();
                if (! $exists) {
                    DB::table('users')->insert([
                        'username' => $admin->username ?? explode('@', $admin->email)[0],
                        'email' => $admin->email,
                        'password' => $admin->password,
                        'fullname' => $admin->fullname ?? $admin->name,
                        'phone' => $admin->phone,
                        'status' => $admin->status,
                        'created_at' => $admin->created_at,
                        'updated_at' => $admin->updated_at,
                    ]);
                }
            }
        }

        // 7. Data Migration (Admin Permission -> Permissions)
        if (Schema::hasTable('admin_permission')) {
            $oldPermissions = DB::table('admin_permission')->get();
            foreach ($oldPermissions as $p) {
                $exists = DB::table('permissions')->where('slug', $p->slug)->exists();
                if (! $exists) {
                    DB::table('permissions')->insert([
                        'name' => $p->name,
                        'slug' => $p->slug,
                        'http_uri' => $p->http_uri,
                        'created_at' => $p->created_at,
                        'updated_at' => $p->updated_at,
                    ]);
                }
            }
        }

        // 8. Data Migration (Pivot Tables)
        // Migrate role_users -> role_user
        if (Schema::hasTable('role_users')) {
            $roleUsers = DB::table('role_users')->get();
            foreach ($roleUsers as $ru) {
                if (Schema::hasTable('admins')) {
                    $admin = DB::table('admins')->where('id', $ru->user_id)->first();
                    if ($admin) {
                        $user = DB::table('users')->where('email', $admin->email)->first();
                        if ($user) {
                            if (! DB::table('role_user')->where('user_id', $user->id)->where('role_id', $ru->role_id)->exists()) {
                                DB::table('role_user')->insert([
                                    'user_id' => $user->id,
                                    'role_id' => $ru->role_id,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]);
                            }
                        }
                    }
                }
            }
        }

        // Migrate admin_role_permission -> permission_role
        if (Schema::hasTable('admin_role_permission')) {
            $arp = DB::table('admin_role_permission')->get();
            foreach ($arp as $item) {
                $oldPerm = DB::table('admin_permission')->where('id', $item->permission_id)->first();
                if ($oldPerm) {
                    $newPerm = DB::table('permissions')->where('slug', $oldPerm->slug)->first();
                    if ($newPerm) {
                        if (! DB::table('permission_role')->where('permission_id', $newPerm->id)->where('role_id', $item->role_id)->exists()) {
                            DB::table('permission_role')->insert([
                                'permission_id' => $newPerm->id,
                                'role_id' => $item->role_id,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Safety: do not drop tables.
    }
};

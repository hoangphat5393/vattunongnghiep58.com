<?php

use App\Models\Backend\Role;
use App\Models\Backend\User;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $adminRole = Role::where('slug', 'administrator')->first();

        if ($adminRole) {
            // Find users with admin_level 99999
            $superAdmins = User::where('admin_level', 99999)->get();

            foreach ($superAdmins as $user) {
                if (! $user->roles()->where('roles.id', $adminRole->id)->exists()) {
                    $user->roles()->attach($adminRole->id);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

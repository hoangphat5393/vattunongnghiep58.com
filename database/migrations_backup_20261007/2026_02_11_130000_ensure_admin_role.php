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
        // 1. Ensure Administrator Role exists
        $adminRole = Role::where('slug', 'administrator')->first();
        if (! $adminRole) {
            $adminRole = Role::create([
                'name' => 'Administrator',
                'slug' => 'administrator',
                'description' => 'System Administrator with full access',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2. Assign to default admin (ID 1 or username 'admin')
        $adminUser = User::find(1);
        if (! $adminUser) {
            $adminUser = User::where('username', 'admin')->first();
        }

        if ($adminUser) {
            // Check if already has role
            if (! $adminUser->roles()->where('slug', 'administrator')->exists()) {
                $adminUser->roles()->attach($adminRole->id);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse needed for data fix
    }
};

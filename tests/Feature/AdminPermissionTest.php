<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Backend\Role;
use App\Models\Backend\Permission;

class AdminPermissionTest extends TestCase
{
    // We don't use RefreshDatabase here to avoid wiping the existing DB if not configured for testing
    // Instead we will manually clean up or use a transaction if possible.
    // For safety in this environment, I'll just check if I can create and read.

    public function test_can_create_permission_and_assign_to_role()
    {
        // 1. Create a Test Permission
        $permission = Permission::create([
            'name' => 'Test Permission',
            'slug' => 'test-permission-' . time(),
            'http_uri' => 'GET::test/uri'
        ]);

        $this->assertDatabaseHas('admin_permission', [
            'id' => $permission->id,
            'slug' => $permission->slug
        ]);

        // 2. Create a Test Role
        $role = Role::create([
            'name' => 'Test Role',
            'slug' => 'test-role-' . time(),
            // 'status' => 1 // Removed as column does not exist
        ]);

        // If Role doesn't have status, we might need to adjust. 
        // Let's check Role model again if it fails.
        // Assuming minimal fillable from previous read: protected $guarded = [];

        $this->assertDatabaseHas('roles', [ // Wait, Role model table is 'roles' by default
            'id' => $role->id,
            'slug' => $role->slug
        ]);

        // 3. Attach
        $role->permissions()->attach($permission->id);

        $this->assertDatabaseHas('admin_role_permission', [
            'role_id' => $role->id,
            'permission_id' => $permission->id
        ]);

        // 4. Verify Relationship
        $this->assertTrue($role->permissions->contains($permission));
        $this->assertTrue($permission->roles->contains($role));

        // 5. Cleanup
        $role->permissions()->detach();
        $role->delete();
        $permission->delete();
    }
}

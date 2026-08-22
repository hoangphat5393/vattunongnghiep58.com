<?php

namespace Tests\Feature;

use App\Models\Backend\Permission;
use App\Models\Backend\Role;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminPermissionTest extends TestCase
{
    public function test_can_create_permission_and_assign_to_role()
    {
        if (! Schema::hasTable('permissions') || ! Schema::hasTable('permission_role')) {
            $this->markTestSkipped('Cần schema MySQL đầy đủ (phpunit dùng sqlite :memory: không migrate được toàn bộ).');
        }

        // 1. Create a Test Permission
        $permission = Permission::create([
            'name' => 'Test Permission',
            'slug' => 'test-permission-'.time(),
            'http_uri' => 'GET::test/uri',
        ]);

        $this->assertDatabaseHas('permissions', [
            'id' => $permission->id,
            'slug' => $permission->slug,
        ]);

        // 2. Create a Test Role
        $role = Role::create([
            'name' => 'Test Role',
            'slug' => 'test-role-'.time(),
            // 'status' => 1 // Removed as column does not exist
        ]);

        // If Role doesn't have status, we might need to adjust.
        // Let's check Role model again if it fails.
        // Assuming minimal fillable from previous read: protected $guarded = [];

        $this->assertDatabaseHas('roles', [ // Wait, Role model table is 'roles' by default
            'id' => $role->id,
            'slug' => $role->slug,
        ]);

        // 3. Attach
        $role->permissions()->attach($permission->id);

        $this->assertDatabaseHas('permission_role', [
            'role_id' => $role->id,
            'permission_id' => $permission->id,
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

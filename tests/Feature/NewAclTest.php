<?php

namespace Tests\Feature;

use App\Models\Backend\Permission;
use App\Models\Backend\Role;
use App\Models\Backend\User;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class NewAclTest extends TestCase
{
    // Use DatabaseTransactions to rollback changes after test
    // use \Illuminate\Foundation\Testing\DatabaseTransactions;
    // Commented out because we want to verify schema persistence first

    public function test_acl_tables_exist()
    {
        $this->assertTrue(Schema::hasTable('users'));
        $this->assertTrue(Schema::hasTable('roles'));
        $this->assertTrue(Schema::hasTable('permissions'));
        $this->assertTrue(Schema::hasTable('role_user'));
        $this->assertTrue(Schema::hasTable('permission_role'));
    }

    public function test_can_create_permission_and_assign_to_role()
    {
        // 1. Create a Test Permission
        $permission = Permission::create([
            'name' => 'New ACL Test Permission',
            'slug' => 'new-acl-test-permission-'.time(),
            'http_uri' => 'GET::test/uri',
            'resource' => 'test_resource',
            'action' => 'test_action',
        ]);

        $this->assertDatabaseHas('permissions', [
            'id' => $permission->id,
            'slug' => $permission->slug,
        ]);

        // 2. Create a Test Role
        $role = Role::create([
            'name' => 'New ACL Test Role',
            'slug' => 'new-acl-test-role-'.time(),
            'description' => 'Test Description',
        ]);

        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
            'slug' => $role->slug,
        ]);

        // 3. Attach Permission to Role
        $role->permissions()->attach($permission->id);

        $this->assertDatabaseHas('permission_role', [
            'role_id' => $role->id,
            'permission_id' => $permission->id,
        ]);

        // 4. Verify Relationship
        $this->assertTrue($role->permissions->contains($permission));
        $this->assertTrue($permission->roles->contains($role));

        // 5. Create a User and Assign Role
        $user = User::create([
            'username' => 'acluser'.time(),
            'email' => 'acluser'.time().'@example.com',
            'password' => bcrypt('password'),
            'fullname' => 'ACL Test User',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => $user->email,
        ]);

        $user->roles()->attach($role->id);

        $this->assertDatabaseHas('role_user', [
            'user_id' => $user->id,
            'role_id' => $role->id,
        ]);

        $this->assertTrue($user->roles->contains($role));

        // 6. Cleanup
        $user->roles()->detach();
        $user->delete();
        $role->permissions()->detach();
        $role->delete();
        $permission->delete();
    }
}

<?php

namespace Tests\Feature;

use App\Models\Backend\Permission;
use App\Models\Backend\Role;
use App\Models\Backend\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAclFeatureTest extends TestCase
{
    // We don't use RefreshDatabase here to avoid wiping the actual DB if configured incorrectly,
    // instead we'll rely on manual cleanup or transaction if possible.
    // But for safety in this environment, let's create unique data and clean it up.

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Cleanup potential stale data
        User::where('email', 'admin@example.com')->delete();
        User::where('email', 'nopermuser@example.com')->delete();
        User::where('email', 'testuserassign@example.com')->delete();

        // Create a user with admin access
        $this->adminUser = User::create([
            'name' => 'Admin User',
            'username' => 'admin_test',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'status' => 1,
        ]);

        // Assign administrator role
        $role = Role::firstOrCreate(['slug' => 'administrator'], ['name' => 'Administrator']);
        $this->adminUser->roles()->sync([$role->id]);
    }

    protected function tearDown(): void
    {
        if ($this->adminUser) {
            $this->adminUser->delete();
        }
        User::where('email', 'nopermuser@example.com')->delete();
        User::where('email', 'testuserassign@example.com')->delete();
        parent::tearDown();
    }

    public function test_admin_can_view_role_list()
    {
        $response = $this->actingAs($this->adminUser, 'admin')
            ->get(route('admin.role.index'));

        $response->assertStatus(200);
        $response->assertViewIs('backend.role.index');
    }

    public function test_admin_can_create_role()
    {
        $roleName = 'Test Role '.time();
        $roleSlug = 'test-role-'.time();

        $response = $this->actingAs($this->adminUser, 'admin')
            ->post(route('admin.role.store'), [
                'name' => $roleName,
                'slug' => $roleSlug,
                'permission' => [],
                'administrators' => [],
                'submit' => 'save',
            ]);

        $response->assertRedirect(route('admin.role.index'));

        $this->assertDatabaseHas('roles', [
            'name' => $roleName,
            'slug' => $roleSlug,
        ]);

        // Cleanup
        Role::where('slug', $roleSlug)->delete();
    }

    public function test_admin_can_update_role()
    {
        // Create a role first
        $role = Role::create([
            'name' => 'Role To Update',
            'slug' => 'role-to-update-'.time(),
        ]);

        $newName = 'Role Updated '.time();

        $response = $this->actingAs($this->adminUser, 'admin')
            ->put(route('admin.role.update', ['id' => $role->id]), [
                'name' => $newName,
                'slug' => $role->slug,
                'permission' => [],
                'submit' => 'save', // redirect to index
            ]);

        $response->assertRedirect(route('admin.role.index'));

        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
            'name' => $newName,
        ]);

        // Cleanup
        $role->delete();
    }

    public function test_admin_can_view_permission_list()
    {
        $response = $this->actingAs($this->adminUser, 'admin')
            ->get(route('admin.permission.index'));

        $response->assertStatus(200);
        $response->assertViewIs('backend.permission.index');
    }

    public function test_admin_can_create_permission()
    {
        $permName = 'Test Perm '.time();
        $permSlug = 'test-perm-'.time();

        $response = $this->actingAs($this->adminUser, 'admin')
            ->post(route('admin.permission.store'), [
                'name' => $permName,
                'slug' => $permSlug,
                'http_uri' => ['GET::test'],
                'submit' => 'save',
            ]);

        $response->assertRedirect(route('admin.permission.index'));

        $this->assertDatabaseHas('permissions', [
            'name' => $permName,
            'slug' => $permSlug,
        ]);

        // Cleanup
        Permission::where('slug', $permSlug)->delete();
    }

    public function test_user_without_permission_cannot_access_roles()
    {
        // Create a user with no roles/permissions
        $user = User::create([
            'name' => 'No Perm User',
            'username' => 'nopermuser',
            'email' => 'nopermuser@example.com',
            'password' => bcrypt('password'),
            'status' => 1,
        ]);

        $response = $this->actingAs($user, 'admin')
            ->get(route('admin.role.index'));

        // Should be forbidden (403) or redirect to dashboard/login depending on middleware
        // CheckAdminPermission usually aborts 403 or redirects
        // If the user has NO roles, CheckAdminPermission might deny.

        $response->assertStatus(403);

        $user->delete();
    }

    public function test_admin_can_assign_role_to_user()
    {
        // Create a test role
        $role = Role::create([
            'name' => 'Test Role Assign',
            'slug' => 'test-role-assign-'.time(),
        ]);

        // Create a test user
        $user = User::create([
            'name' => 'Test User Assign',
            'username' => 'testuserassign',
            'email' => 'testuserassign@example.com',
            'password' => bcrypt('password'),
            'status' => 1,
        ]);

        // Assign role via UserAdminController post
        // Use PUT to admin.user.update
        $response = $this->actingAs($this->adminUser, 'admin')
            ->put(route('admin.user.update', ['id' => $user->id]), [
                'id' => $user->id,
                'roles' => [$role->id],
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'submit' => 'save', // redirect to index
            ]);

        // Assert redirect to list
        $response->assertRedirect(route('admin.userList'));

        // Assert role attached
        $this->assertDatabaseHas('role_user', [
            'user_id' => $user->id,
            'role_id' => $role->id,
        ]);

        // Cleanup
        $user->roles()->detach();
        $role->delete();
        $user->delete();
    }
}

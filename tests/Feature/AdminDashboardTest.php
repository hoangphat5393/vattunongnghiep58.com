<?php

namespace Tests\Feature;

use App\Models\Backend\User;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    public function test_admin_can_access_dashboard(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'username' => 'admin_test',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'status' => 1,
        ]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));

        $response->assertStatus(200);
    }
}

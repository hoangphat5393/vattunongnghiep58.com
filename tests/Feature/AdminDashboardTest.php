<?php

namespace Tests\Feature;

use App\Models\Backend\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Debug connection
        echo "DB Connection: " . \DB::connection()->getName() . "\n";
        echo "DB Database: " . \DB::connection()->getDatabaseName() . "\n";

        // Explicitly run migrations
        try {
            \Illuminate\Support\Facades\Artisan::call('migrate');
        } catch (\Exception $e) {
            echo "Migration failed: " . $e->getMessage() . "\n";
        }

        // Clear cache to ensure setting_option helper doesn't use stale cache
        \Illuminate\Support\Facades\Cache::flush();

        // Debug: Check if settings table exists
        if (!\Illuminate\Support\Facades\Schema::hasTable('settings')) {
            echo "Settings table missing, creating manually...\n";
            \Illuminate\Support\Facades\Schema::create('settings', function ($table) {
                $table->id();
                $table->string('name')->unique();
                $table->longText('content')->nullable();
                $table->string('type')->default('text');
                $table->timestamps();
            });
        } else {
            echo "Settings table exists.\n";
        }

        // Seed some settings if needed
        if (\App\Models\Backend\Setting::count() == 0) {
            \App\Models\Backend\Setting::create(['name' => 'webtitle', 'content' => 'AdminLTE 4 Test']);
            \App\Models\Backend\Setting::create(['name' => 'logo', 'content' => 'logo.png']);
        }
    }

    /** @test */
    public function admin_can_access_dashboard()
    {
        // Create an admin user
        $admin = User::create([
            'name' => 'Admin User',
            'username' => 'admin_test',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'status' => 1
        ]);

        $response = $this->actingAs($admin, 'admin')->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('backend.home');

        // Check for AdminLTE 4 specific classes to verify layout update
        $response->assertSee('app-wrapper'); // New wrapper class
        $response->assertSee('app-sidebar'); // New sidebar class

        // Check for Bootstrap 5 specific classes (from my updates)
        // $response->assertSee('text-bg-primary'); // Updated badge class in dashboard widgets
        // $response->assertSee('float-end'); // Updated utility class
    }
}

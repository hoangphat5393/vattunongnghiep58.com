<?php

namespace Tests\Feature\Admin;

use App\Models\Backend\Role;
use App\Models\Backend\User;
use App\Services\MysqlExportService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Mockery;
use Tests\TestCase;

class DatabaseMysqlExportTest extends TestCase
{
    /**
     * @var array<int, User>
     */
    protected array $createdAdmins = [];

    protected function tearDown(): void
    {
        foreach ($this->createdAdmins as $admin) {
            $admin->roles()->detach();
            $admin->delete();
        }
        $this->createdAdmins = [];

        parent::tearDown();
    }

    protected function createSuperAdmin(): User
    {
        $role = Role::firstOrCreate(
            ['slug' => 'administrator'],
            ['name' => 'Administrator']
        );

        $unique = uniqid('export_admin_', true);
        $admin = User::create([
            'name' => 'Admin Export Tester',
            'username' => $unique,
            'email' => $unique.'@example.com',
            'password' => bcrypt('password'),
            'status' => 1,
        ]);
        $admin->roles()->sync([$role->id]);
        $this->createdAdmins[] = $admin;

        return $admin;
    }

    protected function createUnprivilegedAdmin(): User
    {
        $unique = uniqid('no_perm_admin_', true);
        $admin = User::create([
            'name' => 'No Perm Admin',
            'username' => $unique,
            'email' => $unique.'@example.com',
            'password' => bcrypt('password'),
            'status' => 1,
        ]);
        $this->createdAdmins[] = $admin;

        return $admin;
    }

    public function test_guest_cannot_access_mysql_export_or_download_routes(): void
    {
        $this->post(route('admin.database-maintenance.export-mysql'))
            ->assertRedirect();

        $this->get(route('admin.database-maintenance.download-mysql'))
            ->assertRedirect();
    }

    public function test_unprivileged_admin_receives_403_on_export_and_download(): void
    {
        $admin = $this->createUnprivilegedAdmin();

        $this->actingAs($admin, 'admin')
            ->postJson(route('admin.database-maintenance.export-mysql'))
            ->assertStatus(403);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.database-maintenance.download-mysql'))
            ->assertStatus(403);
    }

    public function test_admin_sees_mysql_export_card_on_database_maintenance_page(): void
    {
        $admin = $this->createSuperAdmin();

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.database-maintenance.index'));

        $response->assertStatus(200);
        $response->assertSee('Xuất Database đa định dạng', false);
        $response->assertSee('btnExportDatabase', false);
        $response->assertSee(route('admin.database-maintenance.export'), false);
    }

    public function test_admin_can_trigger_mysql_export_via_ajax(): void
    {
        $admin = $this->createSuperAdmin();

        $mockService = Mockery::mock(MysqlExportService::class);
        $mockService->shouldReceive('export')->once()->andReturn([
            'path' => database_path('vattunongnghiep58_mysql_latest.sql'),
            'filename' => 'vattunongnghiep58_mysql_latest.sql',
            'size_bytes' => 2457600,
            'size_human' => '2.34 MB',
            'updated_at' => '20/11/2026 18:05:00',
            'duration_ms' => 1420,
        ]);
        $this->app->instance(MysqlExportService::class, $mockService);

        $response = $this->actingAs($admin, 'admin')
            ->postJson(route('admin.database-maintenance.export-mysql'));

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'filename' => 'vattunongnghiep58_mysql_latest.sql',
                    'size_human' => '2.34 MB',
                    'download_url' => route('admin.database-maintenance.download-mysql'),
                ],
            ]);
    }

    public function test_concurrent_export_returns_423_when_locked(): void
    {
        $admin = $this->createSuperAdmin();
        $lock = Cache::lock('admin_mysql_export_lock', 60);
        $this->assertTrue($lock->get());

        try {
            $response = $this->actingAs($admin, 'admin')
                ->postJson(route('admin.database-maintenance.export-mysql'));

            $response->assertStatus(423)
                ->assertJson([
                    'status' => 'error',
                ]);
        } finally {
            $lock->release();
        }
    }

    public function test_admin_can_download_existing_mysql_dump_file(): void
    {
        $admin = $this->createSuperAdmin();
        $tempFile = storage_path('app/test_mysql_export_dummy.sql');
        File::put($tempFile, "-- Test MySQL Dump\nSELECT 1;\n");

        try {
            $mockService = Mockery::mock(MysqlExportService::class);
            $mockService->shouldReceive('getDumpPath')->once()->andReturn($tempFile);
            $this->app->instance(MysqlExportService::class, $mockService);

            $response = $this->actingAs($admin, 'admin')
                ->get(route('admin.database-maintenance.download-mysql'));

            $response->assertStatus(200);
            $disposition = (string) $response->headers->get('content-disposition');
            $this->assertStringContainsString('attachment;', $disposition);
            $this->assertStringContainsString('.sql', $disposition);
        } finally {
            File::delete($tempFile);
        }
    }

    public function test_download_redirects_with_error_when_dump_file_missing(): void
    {
        $admin = $this->createSuperAdmin();

        $mockService = Mockery::mock(MysqlExportService::class);
        $mockService->shouldReceive('getDumpPath')->once()->andReturn(storage_path('app/non_existent_mysql_dump.sql'));
        $this->app->instance(MysqlExportService::class, $mockService);

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.database-maintenance.download-mysql'));

        $response->assertRedirect(route('admin.database-maintenance.index'));
        $response->assertSessionHas('error');
    }
}

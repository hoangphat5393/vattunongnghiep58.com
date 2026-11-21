<?php

namespace Tests\Feature\Admin;

use App\Models\Backend\Role;
use App\Models\Backend\User;
use App\Services\DatabaseExportService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Mockery;
use Tests\TestCase;

class DatabaseMultiExportTest extends TestCase
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

        $unique = uniqid('multi_export_admin_', true);
        $admin = User::create([
            'name' => 'Multi Export Tester',
            'username' => $unique,
            'email' => $unique.'@example.com',
            'password' => bcrypt('password'),
            'status' => 1,
        ]);
        $admin->roles()->sync([$role->id]);
        $this->createdAdmins[] = $admin;

        return $admin;
    }

    public function test_guest_cannot_access_multi_export_or_download_routes(): void
    {
        $this->post(route('admin.database-maintenance.export'), ['format' => 'pgsql'])
            ->assertRedirect();

        $this->get(route('admin.database-maintenance.download', ['format' => 'mongodb']))
            ->assertRedirect();
    }

    public function test_invalid_export_format_is_rejected(): void
    {
        $admin = $this->createSuperAdmin();

        $this->actingAs($admin, 'admin')
            ->postJson(route('admin.database-maintenance.export'), ['format' => 'oracle'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['format']);

        $this->actingAs($admin, 'admin')
            ->get('/admin/database-maintenance/download/oracle')
            ->assertStatus(404);
    }

    public function test_admin_sees_all_three_export_formats_on_maintenance_page(): void
    {
        $admin = $this->createSuperAdmin();

        $response = $this->actingAs($admin, 'admin')
            ->get(route('admin.database-maintenance.index'));

        $response->assertStatus(200);
        $response->assertSee('MySQL / MariaDB (.sql)', false);
        $response->assertSee('PostgreSQL (.sql)', false);
        $response->assertSee('MongoDB (.zip — JSON)', false);
        $response->assertSee('btnExportDatabase', false);
    }

    public function test_admin_can_trigger_export_for_pgsql_and_mongodb_via_ajax(): void
    {
        $admin = $this->createSuperAdmin();

        $mock = Mockery::mock(DatabaseExportService::class);
        foreach (['pgsql' => 'sql', 'mongodb' => 'zip'] as $format => $ext) {
            $mock->shouldReceive('export')->once()->with($format)->andReturn([
                'format' => $format,
                'path' => storage_path("app/private/db-exports/test_{$format}.{$ext}"),
                'filename' => "test_{$format}.{$ext}",
                'size_bytes' => 102400,
                'size_human' => '100.0 KB',
                'updated_at' => '21/11/2026 00:20:00',
                'duration_ms' => 320,
            ]);
        }
        $this->app->instance(DatabaseExportService::class, $mock);

        foreach (['pgsql' => 'sql', 'mongodb' => 'zip'] as $format => $ext) {
            $response = $this->actingAs($admin, 'admin')
                ->postJson(route('admin.database-maintenance.export'), ['format' => $format]);

            $response->assertStatus(200)
                ->assertJson([
                    'status' => 'success',
                    'data' => [
                        'format' => $format,
                        'filename' => "test_{$format}.{$ext}",
                        'download_url' => route('admin.database-maintenance.download', ['format' => $format]),
                    ],
                ]);
        }
    }

    public function test_concurrent_export_lock_returns_423_per_format(): void
    {
        $admin = $this->createSuperAdmin();
        $lock = Cache::lock('admin_db_export_lock_pgsql', 60);
        $this->assertTrue($lock->get());

        try {
            $this->actingAs($admin, 'admin')
                ->postJson(route('admin.database-maintenance.export'), ['format' => 'pgsql'])
                ->assertStatus(423)
                ->assertJson(['status' => 'error']);
        } finally {
            $lock->release();
        }
    }

    public function test_admin_can_download_pgsql_and_mongodb_exports(): void
    {
        $admin = $this->createSuperAdmin();

        $tempZip = storage_path('app/test_mongodb_export.zip');
        File::put($tempZip, 'PK-DUMMY-ZIP-CONTENT');

        try {
            $mock = Mockery::mock(DatabaseExportService::class);
            $mock->shouldReceive('getDumpPath')->once()->with('mongodb')->andReturn($tempZip);
            $mock->shouldReceive('getDownloadDescriptor')->once()->with('mongodb')->andReturn([
                'filename' => 'vattunongnghiep58_mongodb_20261121.zip',
                'content_type' => 'application/zip',
            ]);
            $this->app->instance(DatabaseExportService::class, $mock);

            $response = $this->actingAs($admin, 'admin')
                ->get(route('admin.database-maintenance.download', ['format' => 'mongodb']));

            $response->assertStatus(200);
            $disposition = (string) $response->headers->get('content-disposition');
            $this->assertStringContainsString('attachment;', $disposition);
            $this->assertStringContainsString('.zip', $disposition);
        } finally {
            File::delete($tempZip);
        }
    }

    public function test_service_resolves_paths_and_download_descriptors_for_all_formats(): void
    {
        /** @var DatabaseExportService $service */
        $service = app(DatabaseExportService::class);

        $mysqlDesc = $service->getDownloadDescriptor(DatabaseExportService::FORMAT_MYSQL);
        $pgsqlDesc = $service->getDownloadDescriptor(DatabaseExportService::FORMAT_PGSQL);
        $mongoDesc = $service->getDownloadDescriptor(DatabaseExportService::FORMAT_MONGODB);

        $this->assertStringEndsWith('.sql', $mysqlDesc['filename']);
        $this->assertStringEndsWith('.sql', $pgsqlDesc['filename']);
        $this->assertStringEndsWith('.zip', $mongoDesc['filename']);
        $this->assertSame('application/zip', $mongoDesc['content_type']);

        $statuses = $service->getAllStatuses();
        $this->assertArrayHasKey('mysql', $statuses);
        $this->assertArrayHasKey('pgsql', $statuses);
        $this->assertArrayHasKey('mongodb', $statuses);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Backend\Page;
use App\Models\Backend\Role;
use App\Models\Backend\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class BackendP2ContinuationTest extends TestCase
{
    private function createAdministrator(): User
    {
        $admin = User::create([
            'name' => 'P2 Cont Admin',
            'username' => 'p2cont_'.time(),
            'email' => 'p2cont_'.time().'@example.com',
            'password' => bcrypt('password'),
            'status' => 1,
        ]);

        $role = Role::firstOrCreate(['slug' => 'administrator'], ['name' => 'Administrator']);
        $admin->roles()->sync([$role->id]);

        return $admin;
    }

    public function test_admin_routes_registered_under_admin_prefix(): void
    {
        $this->assertTrue(Route::has('admin.login'));

        $loginRoute = Route::getRoutes()->getByName('admin.login');
        $this->assertNotNull($loginRoute);
        $this->assertStringStartsWith('admin/', $loginRoute->uri());
        $this->assertStringNotContainsString('currency', $loginRoute->uri());
    }

    public function test_admin_logout_via_post_redirects_to_login(): void
    {
        $admin = $this->createAdministrator();

        $this->actingAs($admin, 'admin')
            ->post(route('admin.logout'))
            ->assertRedirect(route('admin.login'));

        $this->assertGuest('admin');

        $admin->delete();
    }

    public function test_admin_logout_get_route_is_not_available(): void
    {
        $admin = $this->createAdministrator();

        $this->actingAs($admin, 'admin')
            ->get('/admin/logout')
            ->assertMethodNotAllowed();

        $admin->delete();
    }

    public function test_post_store_validates_required_name(): void
    {
        $admin = $this->createAdministrator();

        $this->actingAs($admin, 'admin')
            ->from(route('admin.post.create'))
            ->post(route('admin.post.store'), [])
            ->assertRedirect(route('admin.post.create'))
            ->assertSessionHasErrors(['name']);

        $admin->delete();
    }

    public function test_post_store_creates_post_with_valid_data(): void
    {
        $admin = $this->createAdministrator();
        $slug = 'p2-post-store-'.time();

        $this->actingAs($admin, 'admin')
            ->post(route('admin.post.store'), [
                'name' => 'P2 Post Store',
                'slug' => $slug,
                'status' => 1,
                'submit' => 'save',
            ])
            ->assertRedirect(route('admin.post.index'));

        $this->assertDatabaseHas('pages', [
            'slug' => $slug,
            'type' => 'post',
            'name' => 'P2 Post Store',
        ]);

        Page::where('slug', $slug)->delete();
        $admin->delete();
    }

    public function test_user_store_validates_required_fields(): void
    {
        $admin = $this->createAdministrator();

        $this->actingAs($admin, 'admin')
            ->from(route('admin.user.create'))
            ->post(route('admin.user.store'), [])
            ->assertRedirect(route('admin.user.create'))
            ->assertSessionHasErrors(['username', 'email', 'password']);

        $admin->delete();
    }

    public function test_menu_store_validates_menuname(): void
    {
        $admin = $this->createAdministrator();

        $this->actingAs($admin, 'admin')
            ->postJson(route('admin.menu.store'), [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['menuname']);

        $admin->delete();
    }

    public function test_product_store_validates_required_name(): void
    {
        $admin = $this->createAdministrator();

        $this->actingAs($admin, 'admin')
            ->from(route('admin.product.create'))
            ->post(route('admin.product.store'), [])
            ->assertRedirect(route('admin.product.create'))
            ->assertSessionHasErrors(['name']);

        $admin->delete();
    }

    public function test_ajax_delete_post_requires_valid_payload(): void
    {
        $admin = $this->createAdministrator();

        $this->actingAs($admin, 'admin')
            ->postJson(route('admin.bulk.delete'), [
                'type' => 'post',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['seq_list']);

        $admin->delete();
    }

    public function test_order_update_validates_cart_id(): void
    {
        if (! Schema::hasTable('shop_orders')) {
            Schema::create('shop_orders', function (Blueprint $table) {
                $table->id('cart_id');
                $table->string('name')->nullable();
                $table->integer('cart_status')->default(0);
                $table->integer('cart_payment')->default(0);
                $table->decimal('shipping_cost', 12, 2)->default(0);
                $table->longText('cart_note')->nullable();
                $table->timestamps();
            });
        }

        $admin = $this->createAdministrator();

        $this->actingAs($admin, 'admin')
            ->from('/admin/order/1')
            ->post(route('admin.order.update'), [
                'cart_status' => 1,
            ])
            ->assertSessionHasErrors(['cart_id']);

        $admin->delete();
    }

    public function test_slug_status_index_migration_runs(): void
    {
        Artisan::call('migrate', [
            '--path' => 'database/migrations/2026_07_06_120000_add_slug_status_indexes.php',
            '--force' => true,
        ]);

        if (Schema::getConnection()->getDriverName() === 'sqlite' && Schema::hasTable('pages')) {
            $indexes = collect(DB::select("PRAGMA index_list('pages')"))->pluck('name');
            $this->assertTrue(
                $indexes->contains('pages_status_id_index') || $indexes->contains('pages_type_slug_index'),
                'Expected pages performance indexes to exist after migration.'
            );
        }

        $this->assertTrue(true);
    }

    public function test_non_administrator_cannot_access_theme_css_editor(): void
    {
        $user = User::create([
            'name' => 'No Theme CSS',
            'username' => 'notheme_'.time(),
            'email' => 'notheme_'.time().'@example.com',
            'password' => bcrypt('password'),
            'status' => 1,
        ]);

        $this->actingAs($user, 'admin')
            ->get(route('admin.css.get'))
            ->assertForbidden();

        $this->actingAs($user, 'admin')
            ->put(route('admin.css.update'), ['css_content' => 'body { color: red; }'])
            ->assertForbidden();

        $user->delete();
    }

    public function test_administrator_can_update_theme_css(): void
    {
        $admin = $this->createAdministrator();
        $cssPath = public_path('assets/css/user_custom.css');
        $original = file_exists($cssPath) ? file_get_contents($cssPath) : null;
        $css = 'body { color: #p2-test; }';

        $this->actingAs($admin, 'admin')
            ->put(route('admin.css.update'), ['css_content' => $css])
            ->assertRedirect(route('admin.css.get'))
            ->assertSessionHas('success');

        $this->assertFileExists($cssPath);
        $this->assertStringContainsString('#p2-test', file_get_contents($cssPath));

        if ($original !== null) {
            file_put_contents($cssPath, $original);
        } else {
            file_put_contents($cssPath, '');
        }

        $admin->delete();
    }

    public function test_theme_css_update_rejects_dangerous_content(): void
    {
        $admin = $this->createAdministrator();

        $this->actingAs($admin, 'admin')
            ->from(route('admin.css.get'))
            ->put(route('admin.css.update'), ['css_content' => 'body { background: url(javascript:alert(1)); }'])
            ->assertRedirect(route('admin.css.get'))
            ->assertSessionHasErrors(['css_content']);

        $admin->delete();
    }
}

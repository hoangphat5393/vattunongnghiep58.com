<?php

namespace Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        if (config('database.default') !== 'sqlite') {
            return;
        }

        Cache::flush();

        if (! Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->longText('content')->nullable();
                $table->string('type')->default('text');
                $table->timestamps();
            });
        }

        $defaultSettings = [
            ['name' => 'currency', 'content' => 'VND', 'type' => 'text'],
            ['name' => 'webtitle', 'content' => 'Craveva ERP', 'type' => 'text'],
            ['name' => 'company_name', 'content' => 'Craveva ERP', 'type' => 'text'],
            ['name' => 'site-name', 'content' => 'Craveva ERP', 'type' => 'text'],
            ['name' => 'email', 'content' => 'no-reply@example.com', 'type' => 'text'],
            ['name' => 'emailadmin', 'content' => 'no-reply@example.com', 'type' => 'text'],
        ];

        foreach ($defaultSettings as $setting) {
            $exists = DB::table('settings')->where('name', $setting['name'])->exists();
            if (! $exists) {
                DB::table('settings')->insert(array_merge($setting, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }

        if (! Schema::hasTable('shop_currencies')) {
            Schema::create('shop_currencies', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->string('name')->nullable();
                $table->string('symbol')->nullable();
                $table->decimal('exchange_rate', 16, 6)->default(1);
                $table->unsignedTinyInteger('precision')->default(0);
                $table->unsignedTinyInteger('symbol_first')->default(0);
                $table->string('thousands')->default(',');
                $table->unsignedTinyInteger('status')->default(1);
            });
        }

        $currencyExists = DB::table('shop_currencies')->where('code', 'VND')->exists();
        if (! $currencyExists) {
            DB::table('shop_currencies')->insert([
                'code' => 'VND',
                'name' => 'VND',
                'symbol' => '₫',
                'exchange_rate' => 1,
                'precision' => 0,
                'symbol_first' => 0,
                'thousands' => '.',
                'status' => 1,
            ]);
        }

        if (! Schema::hasTable('shop_order_status')) {
            Schema::create('shop_order_status', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
            });
        }

        if (! Schema::hasTable('shop_order_payment_status')) {
            Schema::create('shop_order_payment_status', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
            });
        }

        if (! Schema::hasTable('pages')) {
            Schema::create('pages', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('name_en')->nullable();
                $table->string('slug')->unique();
                $table->longText('description')->nullable();
                $table->longText('description_en')->nullable();
                $table->longText('content')->nullable();
                $table->longText('content_en')->nullable();
                $table->string('image')->nullable();
                $table->string('type')->nullable();
                $table->unsignedTinyInteger('status')->default(1);
                $table->integer('sort')->default(0);
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('seo_title')->nullable();
                $table->longText('seo_description')->nullable();
                $table->longText('seo_keyword')->nullable();
                $table->timestamps();
            });
        }

        $homePageExists = DB::table('pages')->where('slug', 'home')->exists();
        if (! $homePageExists) {
            DB::table('pages')->insert([
                'name' => 'Home',
                'slug' => 'home',
                'type' => 'page',
                'status' => 1,
                'sort' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $contactPageExists = DB::table('pages')->where('slug', 'contact')->exists();
        if (! $contactPageExists) {
            DB::table('pages')->insert([
                'name' => 'Contact',
                'slug' => 'contact',
                'type' => 'page',
                'status' => 1,
                'sort' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (! Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('slug')->nullable();
                $table->unsignedTinyInteger('status')->default(1);
                $table->unsignedBigInteger('parent')->default(0);
                $table->integer('sort')->default(0);
                $table->unsignedTinyInteger('hot')->default(0);
                $table->string('image')->nullable();
                $table->string('seo_title')->nullable();
                $table->longText('seo_description')->nullable();
                $table->longText('seo_keyword')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('slug')->nullable();
                $table->unsignedTinyInteger('status')->default(1);
                $table->integer('sort')->default(0);
                $table->string('image')->nullable();
                $table->integer('price')->default(0);
                $table->string('price_type')->nullable();
                $table->string('unit')->nullable();
                $table->integer('stock')->default(0);
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('seo_title')->nullable();
                $table->longText('seo_description')->nullable();
                $table->longText('seo_keyword')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('fullname')->nullable();
                $table->string('name')->nullable();
                $table->string('username')->nullable();
                $table->string('email')->unique();
                $table->string('phone')->nullable();
                $table->string('address')->nullable();
                $table->string('avatar')->nullable();
                $table->string('password')->nullable();
                $table->unsignedTinyInteger('status')->default(1);
                $table->rememberToken();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('shop_orders')) {
            Schema::create('shop_orders', function (Blueprint $table) {
                $table->id('cart_id');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('name')->nullable();
                $table->string('cart_email')->nullable();
                $table->string('cart_phone')->nullable();
                $table->string('cart_address')->nullable();
                $table->longText('cart_note')->nullable();
                $table->string('cart_code')->nullable();
                $table->integer('cart_status')->default(0);
                $table->integer('cart_payment')->default(0);
                $table->decimal('cart_total', 12, 2)->default(0);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('name_en')->nullable();
                $table->string('slug')->unique();
                $table->string('description')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('permissions')) {
            Schema::create('permissions', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('slug')->unique();
                $table->text('http_uri')->nullable();
                $table->string('resource')->nullable();
                $table->string('action')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('role_user')) {
            Schema::create('role_user', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('role_id');
            });
        }

        if (! Schema::hasTable('permission_role')) {
            Schema::create('permission_role', function (Blueprint $table) {
                $table->unsignedBigInteger('permission_id');
                $table->unsignedBigInteger('role_id');
            });
        }

        if (! Schema::hasTable('admin_menus')) {
            Schema::create('admin_menus', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('parent_id')->default(0);
                $table->string('title')->nullable();
                $table->string('uri')->nullable();
                $table->string('icon')->nullable();
                $table->unsignedTinyInteger('type')->default(0);
                $table->integer('sort')->default(0);
                $table->unsignedTinyInteger('hidden')->default(0);
                $table->timestamps();
            });
        }

        $dashboardMenuExists = DB::table('admin_menus')->where('uri', 'admin.dashboard')->exists();
        if (! $dashboardMenuExists) {
            DB::table('admin_menus')->insert([
                'parent_id' => 0,
                'title' => 'admin.dashboard',
                'uri' => 'admin.dashboard',
                'icon' => 'bi bi-speedometer',
                'type' => 0,
                'sort' => 0,
                'hidden' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (! Schema::hasTable('menus')) {
            Schema::create('menus', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('title')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('menu_items')) {
            Schema::create('menu_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('menu_id')->nullable();
                $table->text('slug')->nullable();
                $table->string('label')->nullable();
                $table->string('link')->nullable();
                $table->text('image')->nullable();
                $table->unsignedBigInteger('parent')->default(0);
                $table->integer('sort')->default(0);
                $table->string('class')->nullable();
                $table->integer('depth')->default(0);
                $table->string('rel', 10)->nullable();
                $table->string('target', 10)->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('email_templates')) {
            Schema::create('email_templates', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 50);
                $table->string('code', 100);
                $table->string('group', 50)->nullable();
                $table->text('text')->nullable();
                $table->unsignedTinyInteger('status')->default(1);
                $table->integer('sort')->default(0);
                $table->dateTime('created_at')->nullable();
                $table->dateTime('updated_at')->nullable();
            });
        }

        if (! Schema::hasTable('contacts')) {
            Schema::create('contacts', function (Blueprint $table) {
                $table->increments('id');
                $table->string('type', 50)->nullable();
                $table->string('name', 200)->nullable();
                $table->text('address')->nullable();
                $table->string('email', 200)->nullable();
                $table->string('phone', 100)->nullable();
                $table->string('file', 200)->nullable();
                $table->text('content')->nullable();
                $table->integer('sort')->default(0);
                $table->unsignedTinyInteger('status')->default(1);
                $table->dateTime('created_at')->nullable();
                $table->dateTime('updated_at')->nullable();
            });
        }
    }
}

<?php

namespace Tests\Feature;

use App\Http\Kernel;
use App\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery as BasePreventRequestForgery;
use Tests\TestCase;

class CsrfProtectionTest extends TestCase
{
    public function test_prevent_request_forgery_middleware_is_enabled_in_web_group(): void
    {
        $kernel = $this->app->make(Kernel::class);
        $reflection = new \ReflectionClass($kernel);
        $property = $reflection->getProperty('middlewareGroups');
        $property->setAccessible(true);
        $groups = $property->getValue($kernel);

        $this->assertContains(PreventRequestForgery::class, $groups['web']);
    }

    public function test_prevent_request_forgery_extends_laravel_middleware(): void
    {
        $this->assertTrue(is_subclass_of(PreventRequestForgery::class, BasePreventRequestForgery::class));
    }

    public function test_ckfinder_paths_are_excluded_from_csrf(): void
    {
        $middleware = $this->app->make(PreventRequestForgery::class);
        $reflection = new \ReflectionClass($middleware);
        $property = $reflection->getProperty('except');
        $property->setAccessible(true);
        $except = $property->getValue($middleware);

        $this->assertContains('ckfinder/*', $except);
    }
}

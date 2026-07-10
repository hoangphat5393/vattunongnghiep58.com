<?php

namespace App\Providers;

use App\Http\Middleware\Currency;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        // $this->app->bind(
        //     'App\Verify\Service',
        //     'App\Services\Twilio\Verification'
        // );
        $this->registerRouteMiddleware();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        View::prependNamespace('ckfinder', resource_path('views/backend/ckfinder'));
        View::prependNamespace('theme', resource_path('views/frontend'));

        $buildDirectory = env('VITE_BUILD_DIRECTORY', 'build'); // 'build' là giá trị mặc định
        Vite::useBuildDirectory($buildDirectory);

        // Blade::withoutDoubleEncoding();

        // A custom conditional that checks the current application environment
        Blade::if('env', function ($environment) {
            return app()->environment($environment);
        });

        // @env('local')
        //     // The application is in the local environment...
        // @elseenv('testing')
        //     // The application is in the testing environment...
        // @else
        //     // The application is not in the local or testing environment...
        // @endenv

        // @unlessenv('production')
        //     // The application is not in the production environment...
        // @endenv

        // A custom conditional that checks the current application cloud provider
        Blade::if('cloud', function ($provider) {
            return config('filesystems.default') === $provider;
        });

        // @cloud('digitalocean')
        //     // The application is using the digitalocean cloud provider...
        // @elsecloud('aws')
        //     // The application is using the aws provider...
        // @else
        //     // The application is not using the digitalocean or aws environment...
        // @endcloud

        // @unlesscloud('aws')
        //     // The application is not using the aws environment...
        // @endcloud

        // A custom conditional that checks the configured default "disk" for the application
        Blade::if('disk', function (string $value) {
            return config('filesystems.default') === $value;
        });

        // @disk('local')
        //     // The application is using the local disk...
        // @elsedisk('s3')
        //     // The application is using the s3 disk...
        // @else
        //     // The application is using some other disk...
        // @enddisk

        // @unlessdisk('local')
        //     // The application is not using the local disk...
        // @enddisk

        view()->share('templatePath', env('APP_THEME', 'frontend'));
        view()->share('templateFile', env('APP_THEME', 'frontend'));

        Paginator::useBootstrap();
    }

    /**
     * Register the route middleware.
     *
     * @return void
     */
    protected function registerRouteMiddleware()
    {
        // register route middleware.
        foreach ($this->routeMiddleware as $key => $middleware) {
            app('router')->aliasMiddleware($key, $middleware);
        }
    }

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'currency' => Currency::class,
    ];
}

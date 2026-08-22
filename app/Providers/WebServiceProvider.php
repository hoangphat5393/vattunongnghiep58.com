<?php

namespace App\Providers;

use App\WebService\WebService;
use Illuminate\Support\ServiceProvider;

class WebServiceProvider extends ServiceProvider
{
    public function register()
    {

        $this->app->bind('WebService', function () {

            return new WebService;

        });

    }
}

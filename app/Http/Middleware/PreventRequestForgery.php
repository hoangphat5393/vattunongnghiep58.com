<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\PreventRequestForgery as Middleware;

class PreventRequestForgery extends Middleware
{
    /**
     * URIs excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'ckfinder/*',
    ];
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomCKFinderAuth
{
    /**
     * CKFinder connector auth: only authenticated admin users may upload/browse.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'admin')
    {
        config(['ckfinder.authentication' => function () use ($guard) {
            return Auth::guard($guard)->check() || Auth::guard('web')->check() || Auth::check();
        }]);

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotAdmin
{
    /**
     * Handle an incoming request.

     *

     * @param  Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'admin')
    {

        // dd('RedirectIfNotAdmin');

        if (Auth::guard($guard)->guest()) {

            return redirect('admin/login');

        }

        return $next($request);

    }
}

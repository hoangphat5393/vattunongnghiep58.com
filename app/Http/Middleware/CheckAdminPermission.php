<?php

namespace App\Http\Middleware;

use App\Models\Backend\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdminPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard('admin')->check()) {
            $user = Auth::guard('admin')->user();
            if ($user->isAdministrator()) {
                return $next($request);
            }

            $allPermissions = User::allPermissions();
            foreach ($allPermissions as $permission) {
                if ($permission->passRequest($request)) {
                    return $next($request);
                }
            }
        }
        abort(403, 'Unauthorized action.');
    }
}

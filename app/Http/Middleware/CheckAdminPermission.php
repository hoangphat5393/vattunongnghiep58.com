<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckAdminPermission
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard('admin')->check()) {
            $user = Auth::guard('admin')->user();
            if ($user->isAdministrator()) {
                return $next($request);
            }

            $allPermissions = \App\Models\Backend\User::allPermissions();
            foreach ($allPermissions as $permission) {
                if ($permission->passRequest($request)) {
                    return $next($request);
                }
            }
        }
        abort(403, 'Unauthorized action.');
    }
}

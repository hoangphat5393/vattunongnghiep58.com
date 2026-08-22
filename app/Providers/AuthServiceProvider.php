<?php

namespace App\Providers;

use App\Models\Backend\Permission;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Grant all permissions to administrators
        Gate::before(function ($user, $ability) {
            if (method_exists($user, 'isAdministrator') && $user->isAdministrator()) {
                return true;
            }
        });

        // Dynamically register permissions
        try {
            if (Schema::hasTable('permissions')) {
                foreach (Permission::all() as $permission) {
                    Gate::define($permission->slug, function ($user) use ($permission) {
                        if (method_exists($user, 'hasPermissionTo')) {
                            return $user->hasPermissionTo($permission->slug);
                        }

                        return false;
                    });
                }
            }
        } catch (\Exception $e) {
            // Permissions table might not exist yet during migration
        }
    }
}

<?php

namespace App\Models\Backend;

use App\Traits\Filterable;
use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// Trait
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Filterable, HasFactory, Notifiable;

    protected $table = 'users';

    protected static $allPermissions = null;

    protected static $allViewPermissions = null;

    protected $fillable = [
        'fullname',
        'name',
        'username',
        'birthday',
        'email',
        'password',
        'phone',
        'address',
        'admin_level',
        'email_info',
        'status',
        'image',
        'province',
        'district',
        'ward',
    ];

    protected $hidden = ['password', 'remember_token'];

    /** Tương thích bảng users có fullname (sau refactor ACL), hiển thị qua ->name */
    public function getNameAttribute()
    {
        return $this->attributes['fullname'] ?? $this->attributes['name'] ?? null;
    }

    public static function user()
    {
        return Auth::guard('web')->user();
    }

    /**
     * A user has and belongs to many roles.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    /**
     * Check url menu can display
     *
     * @param   [type]  $url  [$url description]
     * @return [type]        [return description]
     */
    public function checkUrlAllowAccess($url)
    {
        if ($this->isAdministrator() || $this->isViewAll()) {
            return true;
        }
        $listUrlAllowAccess = self::allViewPermissions();
        $arrScheme = ['https://', 'http://'];
        $pathCheck = strtolower(str_replace($arrScheme, '', $url));
        if ($listUrlAllowAccess) {
            foreach ($listUrlAllowAccess as $pathAllow) {
                if (
                    $pathCheck === $pathAllow
                    || $pathCheck === $pathAllow.'/'
                    || (Str::endsWith($pathAllow, '*') && ($pathCheck === str_replace('/*', '', $pathAllow) || strpos($pathCheck, str_replace('*', '', $pathAllow)) === 0))
                    || (Str::endsWith($pathAllow, '{id}') && ($pathCheck === str_replace('/{id}', '', $pathAllow) || strpos($pathCheck, str_replace('{id}', '', $pathAllow)) === 0))
                ) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check if user has permission.
     *
     * @param  string  $permission
     * @return bool
     */
    public function hasPermissionTo($permission)
    {
        if ($this->isAdministrator()) {
            return true;
        }

        return $this->roles()->whereHas('permissions', function ($q) use ($permission) {
            $q->where('slug', $permission);
        })->exists();
    }

    /**
     * Check if user is administrator.
     *
     * @return mixed
     */
    public function isAdministrator(): bool
    {
        return $this->isRole('administrator');
    }

    /**
     * Check if user is $role.
     *
     *
     * @return mixed
     */
    public function isRole(string $role): bool
    {
        return $this->roles->pluck('slug')->contains($role);
    }

    /**
     * Check if user is view_all.
     *
     * @return mixed
     */
    public function isViewAll(): bool
    {
        return $this->isRole('view.all');
    }

    /**
     * Get all view permissions of user.
     *
     * @return mixed
     */
    protected static function allViewPermissions()
    {
        if (self::$allViewPermissions === null) {
            $arrView = [];
            $allPermissionTmp = self::allPermissions();
            $allPermissionTmp = $allPermissionTmp->pluck('http_uri')->toArray();
            if ($allPermissionTmp) {
                foreach ($allPermissionTmp as $actionList) {
                    foreach (explode(',', $actionList) as $action) {
                        if (strpos($action, 'ANY::') === 0 || strpos($action, 'GET::') === 0) {
                            $arrPrefix = ['ANY::', 'GET::'];
                            $arrScheme = ['https://', 'http://'];
                            $arrView[] = str_replace($arrScheme, '', url(str_replace($arrPrefix, '', $action)));
                        }
                    }
                }
            }
            self::$allViewPermissions = $arrView;
        }

        return self::$allViewPermissions;
    }

    /**
     * Get all permissions of user.
     *
     * @return mixed
     */
    public static function allPermissions()
    {
        if (self::$allPermissions === null) {
            $user = Auth::guard('admin')->user();
            self::$allPermissions = $user->roles()->with('permissions')
                ->get()->pluck('permissions')->flatten();
        }

        return self::$allPermissions;
    }

    // Filter Search
    public function filterName(Builder $query, string $value)
    {
        return $query->where('name', 'LIKE', '%'.$value.'%');
    }
}

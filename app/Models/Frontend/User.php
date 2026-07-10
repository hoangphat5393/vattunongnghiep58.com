<?php

namespace App\Models\Frontend;

use App\Models\Backend\Role;
use App\Traits\Filterable;
use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// Trait
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Filterable, HasFactory, Notifiable;

    // protected $guard = 'users';

    protected static $allPermissions = null;

    protected static $allViewPermissions = null;

    protected $fillable = [
        'fullname',
        'birthday',
        'email',
        'password',
        'phone',
        'address',
        'avatar',
        'firstname',
        'lastname',
        'province',
        'district',
        'city',
        'postal_code',
        'country',
        'full_phone',
        'admin_level',
        'email_info',
        'status',
    ];

    protected $hidden = ['password', 'remember_token'];

    /**
     * Form đăng ký gửi field `name`; DB lưu `fullname`.
     */
    protected function name(): Attribute
    {
        return Attribute::get(function (): string {
            return (string) ($this->attributes['fullname'] ?? $this->attributes['name'] ?? '');
        });
    }

    public static function user()
    {
        return Auth::guard('web')->user();
    }

    public function theme(): HasMany
    {
        return $this->hasMany('App\Models\Theme', 'user_id', 'id');
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
        // dd($url);
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
                ->get()->pluck('permissions')->flatten()
                ->merge($user->permissions);
        }

        return self::$allPermissions;
    }

    // Filter Search
    public function filterName(Builder $query, string $value)
    {
        return $query->where('fullname', 'LIKE', '%'.$value.'%');
    }
}

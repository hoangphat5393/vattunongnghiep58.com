<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    use HasFactory;

    // protected $table = 'menus';
    protected $guarded = [];

    // public function __construct(array $attributes = [])
    // {
    //     //parent::construct( $attributes );
    //     $this->table = config('menu.table_prefix') . config('menu.table_name_menus');
    // }

    public static function byName($name)
    {
        return self::where('name', '=', $name)->first();
    }

    // public function items()
    // {
    //     return $this->hasMany('Harimayco\Menu\Models\MenuItems', 'menu')->with('child')->where('parent', 0)->orderBy('sort', 'ASC');
    // }

    public function items(): HasMany
    {
        return $this->hasMany(MenuItems::class, 'menu_id')->with('child')->where('parent', 0)->orderBy('sort', 'ASC');
    }
}

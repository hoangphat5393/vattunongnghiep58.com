<?php

namespace App\Models\Backend;

use App\Models\ProductPrice;
// use Illuminate\Database\Eloquent\Casts\Attribute;

use App\Traits\Filterable;
use App\Traits\LocalizeController;
// Traits

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Filterable, LocalizeController;

    public $timestamps = true;

    // protected $table = 'product';

    protected $guarded = [];

    /**
     * The attributes that should be cast.

     *

     * @var array
     */
    protected $casts = [

        // 'options' => 'array', // change option to column want to cast to array

        'meta' => 'array',

        'meta_en' => 'array',

    ];

    public function getUser()
    {

        return $this->user ? $this->user->name : null;

    }

    /* user detail */

    public function getUserPost()
    {

        return $this->belongsTo(User::class, 'user_id', 'id');

    }

    public function user()
    {

        return $this->belongsTo(User::class, 'user_id', 'id');

    }

    public function categories()
    {

        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id');

    }

    public function prices()
    {
        return $this->hasMany(ProductPrice::class, 'product_id', 'id')->orderBy('sort')->orderBy('id');
    }

    public function filterName($query, $value)
    {

        if ($value) {

            $query->where('name', 'like', '%'.$value.'%');

        }

    }

    public function filterCategoryId($query, $value)
    {

        if ($value) {

            $query->whereHas('categories', function ($q) use ($value) {

                $q->where('id', $value);

            });

        }

    }

    public function listClass()
    {

        return [

            'out-product' => 'Ngoại thất',

            'in-product' => 'Nội thất',

            'engine-product' => 'Động cơ, An toàn',

            'operation-product' => 'Vận hành',

        ];

    }

    // public function promotions()

    // {

    //     return $this->hasMany(ShopProductPromotion::class);

    // }

    // public function products()

    // {

    //     return $this->belongsToMany('App\Product', 'shop_product_category', 'category_id', 'product_id')->orderByDesc('shop_products.updated_at');

    // }

}

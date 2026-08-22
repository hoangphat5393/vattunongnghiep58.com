<?php

namespace App\Models\Frontend;

use App\Models\ProductPrice;
use App\Traits\Filterable;
// use App\Models\Variable;

// use App\Models\ProductPromotion;

// Trait

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Filterable;

    // protected $table = 'product';

    // protected $primaryKey = 'id';

    protected $sc_category = []; // array category id

    protected $game_id = []; // array game id

    public static function search(string $keyword)
    {

        // Eloquent binds parameters automatically, so addslashes is not needed and can double-escape

        $keyword = '%'.$keyword.'%';

        $result = self::select('*')

            ->where('name', 'like', $keyword)

            ->paginate(20);

        return $result;

    }

    public function getNameAttribute($value)
    {

        $lc = app()->getLocale();

        if ($lc == 'vi') {

            return $value;

        } else {

            return $this->{'name_'.$lc} ?: $value;

        }

    }

    public function getDescriptionAttribute($value)
    {

        $lc = app()->getLocale();

        if ($lc == 'vi') {

            return $value;

        } else {

            return $this->{'description_'.$lc} ?: $value;

        }

    }

    public function getContentAttribute($value)
    {

        $lc = app()->getLocale();

        if ($lc == 'vi') {

            return $value;

        } else {

            return $this->{'content_'.$lc} ?: $value;

        }

    }

    /**
     * Get product to array Catgory

     *
     * @param   [array|int]  $arrCategory
     */
    public function getProductToCategory($arrCategory)
    {

        $this->setCategory($arrCategory);

        return $this;

    }

    /**
     * Set array category

     *

     * @param   [array|int]  $category
     */
    private function setCategory($category)
    {

        if (is_array($category)) {

            $this->sc_category = $category;

        } else {

            $this->sc_category = [(int) $category];

        }

        return $this;

    }

    public function getGame($arrGame)
    {

        $this->setGame($arrGame);

        return $this;

    }

    private function setGame($game_id)
    {

        if (is_array($game_id)) {

            $this->game_id = $game_id;

        } else {

            $this->game_id = [(int) $game_id];

        }

        return $this;

    }

    public function getProductsByCategoryId()
    {

        $list = (new Product);

        $tablePTC = (new ShopProductCategory)->getTable();

        $list = $list->leftJoin($tablePTC, $tablePTC.'.product_id', $this->getTable().'.id');

        $list = $list->whereIn($tablePTC.'.category_id', $this->sc_category);

        $list = $list->where('status', 1)->get();

        return $list;

    }

    public function getFinalPrice()
    {

        if ($this->promotion > 0) {

            return $this->promotion;
        }

        return $this->price;

    }

    public function showPrice()
    {

        $priceFinal = $this->promotion ?? 0;

        $price = $this->price ?? 0;

        $variables_item = $this->getVariables->first();

        if ($variables_item) {

            $price = $variables_item->price;

            $priceFinal = $variables_item->promotion;

        }

        return view(env('APP_THEME', 'demo').'.product.includes.showPrice', [

            'priceFinal' => $priceFinal,

            'price' => $price,

        ]);

    }

    // public function showPriceDetail($options = null)

    // {

    //     $priceFinal = $this->promotion ?? 0;

    //     $price = $this->price ?? 0;

    //     if ($options != null) {

    //         $variable = Variable::where('status', 0)->first();

    //         $option = $options[$variable->id] ?? '';

    //         $option_name = explode('__', $option)[0] ?? '';

    //         if ($option_name) {

    //             $variable_detail = Variable::where('name', $option_name)->first();

    //             $product_variable_detail = ThemeVariable::with('getVariableParent')->whereHas('getVariableParent', function ($query) use ($variable_detail) {

    //                 return $query->where('variable_id', $variable_detail->id);

    //             })->where('theme_id', $this->id)->first();

    //             // dd($variable_detail);

    //             if ($product_variable_detail) {

    //                 $price = $product_variable_detail->price;

    //                 $priceFinal = $product_variable_detail->promotion;

    //             } else {

    //                 $product_variable_detail = ThemeVariable::where('theme_id', $this->id)->where('variable_id', $variable_detail->id)->first();

    //                 if ($product_variable_detail) {

    //                     $price = $product_variable_detail->price;

    //                     $priceFinal = $product_variable_detail->promotion;

    //                 }

    //             }

    //         }

    //     } else {

    //         $variables_item = $this->getVariables->first();

    //         if ($variables_item) {

    //             $price = $variables_item->price;

    //             $priceFinal = $variables_item->promotion;

    //         }

    //     }

    //     return view(env('APP_THEME', 'theme') . '.product.includes.showPriceDetail', [

    //         'priceFinal' => $priceFinal,

    //         'price' => $price,

    //         'unit' => $this->unit,

    //     ]);

    // }

    /*

    *Format price

    */

    public function getPrice()
    {

        $n = $this->price;

        $price_type = $this->price_type;

        $m = '';

        if ($price_type == 1) {

            $m = '/m²';
        }

        if ($n > 0 || $n != '') {

            $n = (0 + str_replace(',', '', $n));

            // is this a number?

            if (! is_numeric($n)) {
                return false;
            }

            // now filter it;

            if ($n > 1000000000000) {
                return round(($n / 1000000000000), 1).' nghìn tỷ'.$m;
            } elseif ($n > 1000000000) {
                return round(($n / 1000000000), 2).' tỷ'.$m;
            } elseif ($n > 1000000) {
                return round(($n / 1000000), 1).' triệu'.$m;
            } elseif ($n > 1000) {
                return round(($n / 1000), 1).' VNĐ'.$m;
            }

            return $n.$m;

        } else {
            return __('Giá thỏa thuận');
        }

    }

    public function getPriceSub()
    {

        $n = $this->price;

        $price_type = $this->price_type;

        $m = '';

        if ($price_type == 1) {

            $n = $n * $this->acreage;

        } else {

            $n = $n / $this->acreage;

            $m = '/m²';

        }

        if ($n > 0 || $n != '') {

            $n = (0 + str_replace(',', '', $n));

            // is this a number?

            if (! is_numeric($n)) {
                return false;
            }

            // now filter it;

            if ($n > 1000000000000) {
                return round(($n / 1000000000000), 1).' nghìn tỷ'.$m;
            } elseif ($n > 1000000000) {
                return round(($n / 1000000000), 2).' tỷ'.$m;
            } elseif ($n > 1000000) {
                return round(($n / 1000000), 1).' triệu'.$m;
            } elseif ($n > 1000) {
                return round(($n / 1000), 1).' VNĐ'.$m;
            }

            return $n.$m;

        } else {
            return __('Giá thỏa thuận');
        }

    }

    public function price_render($price) {}

    /*

    *gallery

    */

    public function getGallery()
    {

        if ($this->gallery != '') {

            return unserialize($this->gallery);
        }

        return [];

    }

    // public function countGallery()

    // {

    //     if ($this->gallery != '')

    //         return count(unserialize($this->gallery));

    //     return 0;

    // }

    // public function wishlist()

    // {

    //     if (auth()->check()) {

    //         $db = \App\Models\Wishlist::where('product_id', $this->id)->where('user_id', auth()->user()->id)->first();

    //         if ($db != '')

    //             return true;

    //     } else {

    //         $wishlist = json_decode(\Cookie::get('wishlist'));

    //         // dd($wishlist);

    //         $key = false;

    //         if ($wishlist != '' && count($wishlist) > 0)

    //             $key = array_search($this->id, $wishlist);

    //         if ($key !== false)

    //             return true;

    //     }

    //     return false;

    // }

    public function getWishList()
    {

        return $this->hasMany('App\Models\Wishlist', 'product_id', 'id');

    }

    /* user detail */

    public function getUser()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /* theme info */

    // public function getInfo()

    // {

    //     return $this->hasOne(\App\Models\ThemeInfo::class, 'theme_id', 'id');

    // }

    public function getThongke()
    {

        return $this->hasOne('App\Models\Thongke', 'theme_id', 'id');

    }

    public function getPackage()
    {

        return $this->hasOne('App\Models\Package', 'id', 'package_id');

    }

    public function categories()
    {

        return $this->belongsToMany(Category::class, 'product_category', 'product_id', 'category_id');

    }

    public function prices()
    {
        return $this->hasMany(ProductPrice::class, 'product_id', 'id')->orderBy('sort')->orderBy('id');
    }

    public function brands()
    {

        return $this->belongsToMany('App\Brand', 'shop_product_brand', 'product_id', 'brand_id');

    }

    public function element()
    {

        return $this->belongsToMany('App\Models\ShopElement', 'shop_product_element', 'product_id', 'element_id');

    }

    public function getAllVariable()
    {

        return $this->belongsToMany('App\Variable', 'theme_variable', 'theme_id', 'variable_id');

    }

    public function getVariable($variable_parent)
    {

        return $this->hasMany('App\Models\ThemeVariable', 'theme_id', 'id')->where('variable_parent', $variable_parent)->groupBy('variable_id')->orderBy('price')->get();

    }

    public function getVariables()
    {

        return $this->hasMany('App\Models\ThemeVariable', 'theme_id', 'id')->where('parent', 0)->orderBy('price');

    }

    public function FlashSale()
    {

        // $now = date('Y-m-d H:i');

        // $list = (new Product)->where('status', 1)->where('promotion', '>', 0)->where('date_start', '<', $now)->where('date_end', '>', $now)->get();

        // return $list;

    }
}

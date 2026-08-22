<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;

class ShopOrderStatus extends Model
{
    public $timestamps = false;

    public $table = 'shop_order_status';

    protected $guarded = [];

    protected static $listStatus = null;

    public static function getIdAll()
    {

        if (! self::$listStatus) {

            self::$listStatus = self::pluck('name', 'id')->all();

        }

        return self::$listStatus;

    }
}

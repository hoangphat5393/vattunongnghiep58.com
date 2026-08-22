<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;

class ShopOrderPaymentStatus extends Model
{
    public $timestamps = false;

    public $table = 'shop_order_payment_status';

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

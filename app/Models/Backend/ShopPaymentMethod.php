<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class ShopPaymentMethod extends Model
{
    public $timestamps = false;

    protected $table = 'shop_payment_method';

    protected $guarded = [];
}

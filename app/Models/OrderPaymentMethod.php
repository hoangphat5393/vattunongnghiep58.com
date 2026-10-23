<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPaymentMethod extends Model
{
    public $timestamps = false;

    protected $table = 'order_payment_methods';

    protected $guarded = [];
}

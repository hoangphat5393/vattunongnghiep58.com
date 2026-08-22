<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model
{
    public $timestamps = true;

    protected $table = 'payment_request';

    protected $guarded = [];
}

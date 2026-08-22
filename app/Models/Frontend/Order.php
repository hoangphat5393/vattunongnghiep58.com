<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $table = 'shop_orders';

    protected $primaryKey = 'cart_id';

    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'cart_id', 'cart_id');
    }
}

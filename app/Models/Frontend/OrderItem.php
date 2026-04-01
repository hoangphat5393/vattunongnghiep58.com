<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    public $timestamps = true;
    protected $table = 'shop_order_items';
    protected $guarded = [];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'cart_id', 'cart_id');
    }

    /**
     * Get the product associated with the OrderItem
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}

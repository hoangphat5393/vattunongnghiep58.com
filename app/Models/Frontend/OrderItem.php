<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    public $timestamps = true;

    protected $table = 'order_items';

    protected $guarded = [];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    // --- Backward Compatibility Accessors & Mutators ---

    public function getCartIdAttribute()
    {
        return $this->attributes['order_id'] ?? null;
    }

    public function setCartIdAttribute($value): void
    {
        $this->attributes['order_id'] = $value;
    }

    public function getQuanlityAttribute()
    {
        return $this->attributes['quantity'] ?? null;
    }

    public function setQuanlityAttribute($value): void
    {
        $this->attributes['quantity'] = $value;
    }
}

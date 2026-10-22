<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $table = 'orders';

    protected $guarded = [];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    // --- Backward Compatibility Accessors & Mutators ---

    public function getCartIdAttribute()
    {
        return $this->attributes['id'] ?? null;
    }

    public function setCartIdAttribute($value): void
    {
        $this->attributes['id'] = $value;
    }

    public function getCartCodeAttribute()
    {
        return $this->attributes['code'] ?? null;
    }

    public function setCartCodeAttribute($value): void
    {
        $this->attributes['code'] = $value;
    }

    public function getCartTotalAttribute()
    {
        return $this->attributes['total'] ?? null;
    }

    public function setCartTotalAttribute($value): void
    {
        $this->attributes['total'] = $value;
    }

    public function getCartPhoneAttribute()
    {
        return $this->attributes['phone'] ?? null;
    }

    public function setCartPhoneAttribute($value): void
    {
        $this->attributes['phone'] = $value;
    }

    public function getCartEmailAttribute()
    {
        return $this->attributes['email'] ?? null;
    }

    public function setCartEmailAttribute($value): void
    {
        $this->attributes['email'] = $value;
    }

    public function getCartAddressAttribute()
    {
        return $this->attributes['address'] ?? null;
    }

    public function setCartAddressAttribute($value): void
    {
        $this->attributes['address'] = $value;
    }

    public function getCartNoteAttribute()
    {
        return $this->attributes['note'] ?? null;
    }

    public function setCartNoteAttribute($value): void
    {
        $this->attributes['note'] = $value;
    }

    public function getCartStatusAttribute()
    {
        return $this->attributes['status'] ?? null;
    }

    public function setCartStatusAttribute($value): void
    {
        $this->attributes['status'] = $value;
    }

    public function getCartPaymentAttribute()
    {
        return $this->attributes['payment_status'] ?? null;
    }

    public function setCartPaymentAttribute($value): void
    {
        $this->attributes['payment_status'] = $value;
    }
}

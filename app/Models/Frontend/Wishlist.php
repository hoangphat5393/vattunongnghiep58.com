<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $table = 'wishlist';

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product', 'id');
    }
}

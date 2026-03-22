<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AddToCardDetail extends Model
{
    public $timestamps = true;
    protected $table = 'addtocard_detail';
    protected $guarded = [];
    // protected $fillable  = [];


    public function cart()
    {
        return $this->belongsTo(AddtoCard::class);
    }

    /**
     * Get the user associated with the AddToCardDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}

<?php

namespace App\Models;

use App\Models\Backend\Product as BackendProduct;
use App\Models\Frontend\Product as FrontendProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductPrice extends Model
{
    protected $table = 'product_prices';

    protected $guarded = [];

    protected $casts = [
        'is_default' => 'boolean',
        'price' => 'integer',
        'status' => 'integer',
        'sort' => 'integer',
    ];

    public function backendProduct(): BelongsTo
    {
        return $this->belongsTo(BackendProduct::class, 'product_id', 'id');
    }

    public function frontendProduct(): BelongsTo
    {
        return $this->belongsTo(FrontendProduct::class, 'product_id', 'id');
    }
}

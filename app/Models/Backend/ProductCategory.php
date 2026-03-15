<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LocalizeController;


class ProductCategory extends Model
{
    use LocalizeController;

    public $timestamps = true;
    protected $table = 'product_category';
    protected $guarded = [];
}

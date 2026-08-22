<?php

namespace App\Models\Backend;

use App\Traits\LocalizeController;
use Illuminate\Database\Eloquent\Model;

/**
 * Pivot product ↔ category (bảng product_categories).
 * Tên class lịch sử là ProductCategory; không nhầm với model Category (danh mục).
 */
class ProductCategory extends Model
{
    use LocalizeController;

    public $timestamps = true;

    protected $table = 'product_categories';

    protected $guarded = [];
}

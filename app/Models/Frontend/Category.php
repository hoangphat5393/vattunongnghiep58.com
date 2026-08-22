<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    // protected $table = 'category';

    /**
     * Get the products that belong to the category.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_categories', 'category_id', 'product_id');
    }

    /**
     * Bảng category_page đã xóa; tin tức không còn gắn category. Trả về collection rỗng.
     */
    public function getPostsAttribute()
    {
        return collect([]);
    }

    // public function pages()
    // {
    //     return $this->belongsToMany(Page::class, 'page_categories', 'category_id', 'page_id');
    // }

    public function getCategoryNameAttribute($value)
    {
        $lc = app()->getLocale();
        if ($lc == 'en') {
            return $value;
        } else {
            return $this->{'categoryName_'.$lc};
        }
    }

    public function getCategoryDescriptionAttribute($value)
    {
        $lc = app()->getLocale();
        if ($lc == 'en') {
            return $value;
        } else {
            return $this->{'categoryDescription_'.$lc};
        }
    }

    public function getCategoryContentAttribute($value)
    {
        $lc = app()->getLocale();
        if ($lc == 'en') {
            return $value;
        } else {
            return $this->{'categoryContent_'.$lc};
        }
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent', 'id')->orderBy('sort', 'DESC');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent', 'id');
    }

    public function getDetail($id, $type = '')
    {
        $detail = new Category;
        if ($type == 'slug') {
            $detail = $detail->where('slug', $id);
        } else {
            $detail = $detail->where('id', $id);
        }

        $detail = $detail->first();

        return $detail;
    }
}

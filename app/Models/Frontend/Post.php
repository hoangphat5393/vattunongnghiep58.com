<?php

namespace App\Models\Frontend;

// use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Backend\User;

// Traits
use App\Traits\Filterable;

class Post extends Model
{
    use HasFactory, Filterable;

    public $timestamps = true;
    protected $guarded = [];

    /**
     * @deprecated Bảng post_categories đã bỏ; chỉ sản phẩm dùng categories. Tin tức dùng Page (cocojt=post).
     * Trả về collection rỗng để code cũ không lỗi.
     */
    public function getCategoriesAttribute()
    {
        return collect([]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Filter Search (post không còn dùng categories, bỏ lọc theo category)
    public function filterCategoryId($query, $value)
    {
        return $query;
    }

    public function filterName($query, $value)
    {
        return $query->where('name', 'LIKE', '%' . $value . '%');
    }
}

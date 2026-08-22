<?php

namespace App\Models\Frontend;

use App\Models\Backend\User;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Page extends Model
{
    use Filterable, HasFactory;
    // protected $table = 'page';

    protected $attributes = [
        'type' => 'page',
    ];

    /**
     * Bảng category_page đã xóa; chỉ sản phẩm dùng categories. Trả về collection rỗng.
     */
    public function getCategoriesAttribute()
    {
        return collect([]);
    }

    public function scopePosts(Builder $query)
    {
        return $query->where('type', 'post');
    }

    public function scopePages(Builder $query)
    {
        // Bảng pages dùng cột type (cocojt đã bỏ). Chỉ query theo type để tránh lỗi "Unknown column 'cocojt'".
        $table = $this->getTable();
        if (Schema::hasColumn($table, 'type')) {
            return $query->where(function ($q) {
                $q->where('type', 'page')->orWhereNull('type');
            });
        }

        return $query;
    }

    // public function filterCategoryId($query, $value)
    // {
    //     if ($value)
    //         return $query->whereHas('categories', function ($query) use ($value) {
    //             $query->where('id', $value);
    //         });
    // }

    public function filterName($query, $value)
    {
        return $query->where('name', 'LIKE', '%'.$value.'%');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function search(string $keyword)
    {
        $keyword = '%'.$keyword.'%';
        $result = self::select('id', 'name', 'slug', 'description')
            ->where('name', 'like', $keyword)
            ->orWhere('parent', 'like', $keyword)
            ->get();

        return $result;
    }

    /** Tên (đa ngôn ngữ: name, name_en). */
    public function getNameAttribute($value)
    {
        $lc = app()->getLocale();
        if ($lc == 'vi') {
            return $value;
        }

        return $this->{'name_en'} ?? $value;
    }

    /** Alias cho name (tương thích code cũ dùng ->title). */
    public function getTitleAttribute()
    {
        return $this->attributes['name'] ?? null;
    }

    /** Alias cho name_en (tương thích code cũ dùng ->title_en). */
    public function getTitleEnAttribute()
    {
        return $this->attributes['name_en'] ?? null;
    }

    public function getDescriptionAttribute($value)
    {
        $lc = app()->getLocale();
        if ($lc == 'vi') {
            return $value;
        } else {
            return $this->{'description_'.$lc};
        }
    }

    public function getContentAttribute($value)
    {
        $lc = app()->getLocale();
        if ($lc == 'vi') {
            return $value;
        } else {
            return $this->{'content_'.$lc};
        }
    }
}

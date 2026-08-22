<?php

namespace App\Models\Backend;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Schema;

class Page extends Model
{
    use Filterable, HasFactory;

    public $timestamps = true;

    // protected $table = 'page';
    protected $guarded = [];

    protected $attributes = [
        'type' => 'page',
    ];

    public function scopePosts(Builder $query)
    {
        return $query->where('type', 'post');
    }

    public function scopePages(Builder $query)
    {
        if (Schema::hasColumn($this->getTable(), 'type')) {
            return $query->where(function ($q) {
                $q->where('type', 'page')->orWhereNull('type');
            });
        }

        return $query;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Filter Search
    public function filterName(Builder $query, string $value)
    {
        return $query->where('name', 'LIKE', '%'.$value.'%');
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
}

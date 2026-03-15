<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
// use Illuminate\Database\Eloquent\SoftDeletes;

// Traits
use App\Traits\Filterable;

class Album extends Model
{
    use HasFactory, Filterable;
    // public $timestamps = true;
    // protected $table = 'albums';
    protected $guarded = [];

    public function items(): HasMany
    {
        return $this->hasMany(AlbumItem::class);
    }

    // Filter Search
    public function filterName(Builder $query, string $value)
    {
        return $query->where('name', 'LIKE', '%' . $value . '%');
    }
}

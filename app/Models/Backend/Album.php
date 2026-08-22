<?php

namespace App\Models\Backend;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

// Traits

use Illuminate\Database\Eloquent\Relations\HasMany;

class Album extends Model
{
    use Filterable, HasFactory;

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

        return $query->where('name', 'LIKE', '%'.$value.'%');

    }
}

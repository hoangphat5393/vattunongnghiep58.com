<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

// Trails
use App\Traits\Filterable;

class Contact extends Model
{
    use HasFactory, Filterable;

    // public $timestamps = true;
    // protected $table = 'contact';
    protected $guarded = [];

    // Filter Search
    public function filterName(Builder $query, string $value)
    {
        return $query->where('name', 'LIKE', '%' . $value . '%');
    }

    public function filterEmail(Builder $query, string $value)
    {
        return $query->where('email', 'LIKE', '%' . $value . '%');
    }
}

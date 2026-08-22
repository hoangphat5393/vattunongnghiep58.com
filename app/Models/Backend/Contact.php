<?php

namespace App\Models\Backend;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// Trails

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use Filterable, HasFactory;

    // public $timestamps = true;

    // protected $table = 'contact';

    protected $guarded = [];

    // Filter Search

    public function filterName(Builder $query, string $value)
    {

        return $query->where('name', 'LIKE', '%'.$value.'%');

    }

    public function filterEmail(Builder $query, string $value)
    {

        return $query->where('email', 'LIKE', '%'.$value.'%');

    }
}

<?php

namespace App\Models\Backend;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// Traits

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use Filterable, HasFactory;

    protected $guarded = [];

    // Filter Search

    public function filterEmail($query, $value)
    {

        return $query->where('email', 'LIKE', '%'.$value.'%');

    }
}

<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Traits
use App\Traits\Filterable;

class Subscription extends Model
{
    use HasFactory, Filterable;

    protected $guarded = [];

    // Filter Search
    public function filterEmail($query, $value)
    {
        return $query->where('email', 'LIKE', '%' . $value . '%');
    }
}

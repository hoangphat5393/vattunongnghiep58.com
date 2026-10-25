<?php

namespace App\Models\Backend;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use Filterable, HasFactory;

    // public $table = 'email_template';

    // protected $table = 'email_template';

    protected $guarded = [];

    public function filterKeyword(Builder $query, string $value)
    {
        return $query->where(function ($q) use ($value) {
            $q->where('name', 'LIKE', '%'.$value.'%')
                ->orWhere('code', 'LIKE', '%'.$value.'%')
                ->orWhere('group', 'LIKE', '%'.$value.'%');
        });
    }

    public function filterName(Builder $query, string $value)
    {
        return $query->where(function ($q) use ($value) {
            $q->where('name', 'LIKE', '%'.$value.'%')
                ->orWhere('code', 'LIKE', '%'.$value.'%');
        });
    }

    public function filterCode(Builder $query, string $value)
    {
        return $query->where('code', 'LIKE', '%'.$value.'%');
    }
}

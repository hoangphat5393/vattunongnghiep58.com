<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category_Theme extends Model
{
    protected $table = 'category_theme';

    protected $primaryKey = 'categoryID';

    protected $guarded = [];

    public $timestamps = false;
}

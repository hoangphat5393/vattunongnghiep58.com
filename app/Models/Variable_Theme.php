<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variable_Theme extends Model
{
    protected $table = 'variable_theme';

    protected $primaryKey = 'variable_themeID';

    protected $guarded = [];

    public $timestamps = false;
}

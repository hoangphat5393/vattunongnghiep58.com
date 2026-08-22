<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public $timestamps = false;

    protected $table = 'country';

    protected $guarded = [];
}

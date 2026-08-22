<?php

namespace App\Models\Frontend;

use App\Traits\LocalizeController;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use LocalizeController;

    public $timestamps = true;

    protected $guarded = [];
}

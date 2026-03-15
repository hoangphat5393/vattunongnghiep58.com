<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LocalizeController;

class Contact extends Model
{
    use LocalizeController;
    public $timestamps = true;
    protected $guarded = [];
}

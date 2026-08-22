<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class Jt_address extends Model
{
    protected $table = 'jt_address';

    protected $fillable = [

        'id',

        'prov',

        'city',

        'area',

    ];
}

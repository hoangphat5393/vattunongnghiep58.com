<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class ImportLog extends Model
{
    protected $table = 'import_log';

    public $timestamps = true;

    protected $guarded = [];
}

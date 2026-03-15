<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddToCard extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table = 'addtocard';
    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(AddToCardDetail::class, 'addtocard_id');
    }
}

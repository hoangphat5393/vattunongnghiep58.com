<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlbumItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }
}

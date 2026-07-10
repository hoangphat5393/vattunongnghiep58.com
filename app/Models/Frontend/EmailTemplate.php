<?php

namespace App\Models\Frontend;

use App\Support\EmailTemplateCodes;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use Filterable, HasFactory;

    protected $guarded = [];

    public function scopePublishedByCode(Builder $query, string $code): Builder
    {
        $code = EmailTemplateCodes::normalize($code);

        return $query
            ->where('status', 1)
            ->where('code', $code)
            ->orderByDesc('sort');
    }

    public static function findPublishedByCode(string $code): ?self
    {
        return static::query()->publishedByCode($code)->first();
    }
}

<?php

namespace App\Models\Backend;

// use App\Enums\ServerStatus;
// use App\Enums\UserRole;
// use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Casts\Attribute;
// use Illuminate\Database\Eloquent\Casts\AsStringable;
// use Illuminate\Database\Eloquent\Casts\AsArrayObject;
// use Illuminate\Database\Eloquent\Casts\AsCollection;
// use App\Collections\OptionCollection;
use App\Traits\Filterable;
use App\Traits\LocalizeController;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
// use DateTimeInterface;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    // use LocalizeController;
    // use HasFactory;
    use Filterable, HasFactory;

    /**
     * Prepare a date for array / JSON serialization.
     */
    // protected function serializeDate(DateTimeInterface $date): string
    // {
    //     return $date->format('Y-m-d');
    // }

    public $timestamps = true;

    // protected $table = 'category';
    protected $guarded = [];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    // protected $attributes = [
    //     'options' => '[]',
    //     'delayed' => false,
    // ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    // protected $cast = [
    //     'options' => 'array',                // change option to column want to cast to array
    //     'options' => AsArrayObject::class,   // casts your JSON attribute to an ArrayObject class
    //     'options' => AsCollection::class,    // casts your JSON attribute to a Laravel Collection instance
    //     'is_admin' => 'boolean',             // the is_admin attribute will always be cast to a boolean when you access it
    //     'directory' => AsStringable::class,  // cast a model attribute to a fluent Illuminate\Support\Stringable object:
    //     'created_at' => 'datetime:Y-m-d',    // When defining a date or datetime cast, you may also specify the date's format
    //     'status' => ServerStatus::class,     // cast your attribute values to PHP Enums
    //     'statuses' => AsEnumCollection::class.':'.ServerStatus::class     // Casting Arrays of Enums
    // ];

    // public function translations()
    // {
    //     return $this->hasMany(CategoryContent::class);
    // }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent', 'id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent', 'id')->orderBy('sort');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_categories', 'category_id', 'product_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Filter Search
    public function filterName(Builder $query, string $value)
    {
        return $query->where('name', 'LIKE', '%'.$value.'%');
    }
}

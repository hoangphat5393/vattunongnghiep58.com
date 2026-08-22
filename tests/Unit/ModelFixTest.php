<?php

namespace Tests\Unit;

use App\Models\Backend\Category;
use App\Models\Backend\Product;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelFixTest extends TestCase
{
    // Use RefreshDatabase to ensure we don't pollute the DB
    // However, if DB connection fails, this will fail immediately.
    // use RefreshDatabase;

    public function test_product_meta_casting()
    {
        // We can test casting without saving to DB if we instantiate with attributes
        $product = new Product;
        $product->meta = ['key' => 'value'];

        // Before the fix, accessing $product->meta might return string or json encoded string if set via mutator,
        // but here we are setting it.
        // Actually, casting applies when setting attributes too if they are in $casts.

        // Let's verify that the cast definition exists in the model instance
        $this->assertTrue($product->hasCast('meta', 'array'), 'Product meta attribute should be cast to array');
    }

    public function test_category_parent_relationship()
    {
        $category = new Category;
        $category->parent = 1; // Set dummy value
        // Check if the relationship returns BelongsTo
        $this->assertInstanceOf(BelongsTo::class, $category->parent());
    }

    public function test_product_user_relationship()
    {
        $product = new Product;
        // Check if relationships return correct types
        $this->assertInstanceOf(BelongsTo::class, $product->user());

        // Check the refactored getUserPost
        $this->assertInstanceOf(BelongsTo::class, $product->getUserPost());
    }
}

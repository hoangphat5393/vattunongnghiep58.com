<?php

namespace App\Traits;

use App\Models\Frontend\Category;
use App\Models\Frontend\Product;
use Carbon\Carbon;
use Illuminate\Support\Collection;

trait FrontendDataTransform
{
    protected function transformHomeCategories(Collection $categories): array
    {
        return $categories->map(function (Category $category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'image' => $category->image ?? null,
                'products' => $category->products
                    ->map(function (Product $product) {
                        return $this->transformProductCard($product);
                    })
                    ->all(),
            ];
        })->all();
    }

    protected function transformProductCard(Product $product): array
    {
        $hasPrice = $product->price_type === 'price' && $product->price !== null;

        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'image' => $product->image,
            'has_price' => $hasPrice,
            'price' => $hasPrice ? $product->price : null,
        ];
    }

    protected function transformHomeNews(Collection $posts): array
    {
        return $posts->map(function ($post) {
            $created = new Carbon($post->created_at ?? now());
            $title = $post->title ?? $post->name ?? '';

            return [
                'id' => $post->id,
                'slug' => $post->slug,
                'title' => $title,
                'image' => $post->image ?? null,
                'description' => $post->description ?? '',
                'date_primary' => $created->format('d/m/Y'),
                'date_secondary' => $created->format('d-m-Y'),
            ];
        })->all();
    }
}

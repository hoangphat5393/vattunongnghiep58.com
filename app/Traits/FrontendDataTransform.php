<?php

namespace App\Traits;

use App\Models\Frontend\Category;
use App\Models\Frontend\Page;
use App\Models\Frontend\Product;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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

    /**
     * @return array{
     *     id: int,
     *     name: string,
     *     slug: string,
     *     children: array<int, array{name: string, slug: string}>
     * }
     */
    protected function transformCategoryPage(Category $category): array
    {
        $children = $category->relationLoaded('children')
            ? $category->children
            : $category->children()->where('status', 1)->orderByDesc('sort')->get();

        return [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'children' => $children
                ->map(fn (Category $child) => [
                    'name' => $child->name,
                    'slug' => $child->slug,
                ])
                ->all(),
        ];
    }

    /**
     * @return array<int, array{
     *     row_id: string,
     *     qty: int,
     *     unit_price: float,
     *     line_total: float,
     *     price_label: string|null,
     *     price_unit: string|null,
     *     unit: string|null,
     *     description_excerpt: string,
     *     id: int,
     *     name: string,
     *     slug: string,
     *     image: string|null,
     *     has_price: bool,
     *     price: int|float|null
     * }>
     */
    protected function transformCartLines(iterable $cartContent): array
    {
        $lines = collect($cartContent);
        $productIds = $lines->pluck('id')->unique()->filter()->all();

        $products = Product::query()
            ->whereIn('id', $productIds)
            ->select('id', 'name', 'slug', 'image', 'price', 'price_type', 'unit', 'description')
            ->get()
            ->keyBy('id');

        return $lines
            ->map(function ($cart) use ($products) {
                $product = $products->get($cart->id);
                if (! $product) {
                    return null;
                }

                $card = $this->transformProductCard($product);
                $descriptionPlain = strip_tags(html_entity_decode($product->description ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8'));

                return array_merge($card, [
                    'row_id' => $cart->rowId,
                    'qty' => (int) $cart->qty,
                    'unit_price' => (float) $cart->price,
                    'line_total' => (float) $cart->price * (int) $cart->qty,
                    'price_label' => data_get($cart->options, 'price_label'),
                    'price_unit' => data_get($cart->options, 'price_unit'),
                    'unit' => $product->unit ?? null,
                    'description_excerpt' => Str::limit(trim($descriptionPlain), 60),
                ]);
            })
            ->filter()
            ->values()
            ->all();
    }

    /**
     * @param  array<int, array{line_total: float}>  $cartItems
     * @return array{subtotal: float, total: float}
     */
    protected function transformCartSummary(array $cartItems): array
    {
        $subtotal = (float) collect($cartItems)->sum('line_total');

        return [
            'subtotal' => $subtotal,
            'total' => $subtotal,
        ];
    }

    protected function transformHomeNews(Collection $posts): array
    {
        return $posts
            ->map(fn ($post) => $this->transformPostListItem($post))
            ->all();
    }

    /**
     * @return array{
     *     id: int,
     *     slug: string,
     *     title: string,
     *     image: string|null,
     *     description: string,
     *     description_html: string,
     *     date_primary: string,
     *     date_secondary: string,
     *     date_long: string
     * }
     */
    protected function transformPostListItem(Page $post): array
    {
        $created = new Carbon($post->created_at ?? now());
        $title = $post->title ?? $post->name ?? '';

        return [
            'id' => $post->id,
            'slug' => $post->slug,
            'title' => $title,
            'image' => $post->image ?? null,
            'description' => $post->description ?? '',
            'description_html' => htmlspecialchars_decode($post->description ?? ''),
            'date_primary' => $created->format('d/m/Y'),
            'date_secondary' => $created->format('d-m-Y'),
            'date_long' => $created->copy()->locale('vi')->translatedFormat('d \T\h\á\n\g m, Y'),
        ];
    }

    /**
     * @return array{
     *     id: int,
     *     slug: string,
     *     title: string,
     *     image: string|null,
     *     description: string,
     *     content_html: string,
     *     seo_keyword: string,
     *     date_primary: string,
     *     date_secondary: string,
     *     date_long: string,
     *     user: array{name: string, image: string|null}|null,
     *     categories: array<int, array{name: string, slug: string}>
     * }
     */
    protected function transformPostDetail(Page $post): array
    {
        $listItem = $this->transformPostListItem($post);
        $user = $post->user;

        return array_merge($listItem, [
            'content_html' => htmlspecialchars_decode($post->content ?? ''),
            'seo_keyword' => $post->seo_keyword ?? '',
            'user' => $user ? [
                'name' => $user->name,
                'image' => $user->image ?? null,
            ] : null,
            'categories' => $post->categories
                ->map(fn ($category) => [
                    'name' => $category->name,
                    'slug' => $category->slug,
                ])
                ->values()
                ->all(),
        ]);
    }
}

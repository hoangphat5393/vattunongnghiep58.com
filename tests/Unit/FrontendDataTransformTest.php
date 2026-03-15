<?php

namespace Tests\Unit;

use App\Models\Frontend\Category;
use App\Models\Frontend\Post;
use App\Models\Frontend\Product;
use App\Traits\FrontendDataTransform;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Tests\TestCase;

class FrontendDataTransformTest extends TestCase
{
    use FrontendDataTransform;

    public function test_transform_home_categories_maps_products_and_price()
    {
        $category = new Category();
        $category->id = 1;
        $category->name = 'Rau ăn lá';
        $category->slug = 'rau-an-la';

        $product = new Product();
        $product->id = 10;
        $product->name = 'Cải xanh';
        $product->slug = 'cai-xanh';
        $product->image = 'images/products/cai-xanh.jpg';
        $product->price = 25000;
        $product->price_type = 'price';

        $category->setRelation('products', Collection::make([$product]));

        $result = $this->transformHomeCategories(Collection::make([$category]));

        $this->assertCount(1, $result);
        $this->assertSame('Rau ăn lá', $result[0]['name']);
        $this->assertCount(1, $result[0]['products']);
        $this->assertSame('Cải xanh', $result[0]['products'][0]['name']);
        $this->assertSame('cai-xanh', $result[0]['products'][0]['slug']);
        $this->assertTrue($result[0]['products'][0]['has_price']);
        $this->assertSame(25000, $result[0]['products'][0]['price']);
    }

    public function test_transform_home_news_formats_dates_and_title()
    {
        $post = new Post();
        $post->id = 5;
        $post->slug = 'bi-quyet-trong-rau';
        $post->name = 'Bí quyết trồng rau';
        $post->image = 'images/news/bi-quyet-trong-rau.jpg';
        $post->description = 'Mô tả ngắn';
        $post->created_at = Carbon::create(2024, 2, 15, 0, 0, 0);

        $result = $this->transformHomeNews(Collection::make([$post]));

        $this->assertCount(1, $result);
        $this->assertSame('bi-quyet-trong-rau', $result[0]['slug']);
        $this->assertSame('Bí quyết trồng rau', $result[0]['title']);
        $this->assertSame('15/02/2024', $result[0]['date_primary']);
        $this->assertSame('15-02-2024', $result[0]['date_secondary']);
    }
}

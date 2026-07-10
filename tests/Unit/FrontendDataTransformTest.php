<?php

namespace Tests\Unit;

use App\Models\Backend\User;
use App\Models\Frontend\Category;
use App\Models\Frontend\Page;
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
        $category = new Category;
        $category->id = 1;
        $category->name = 'Rau ăn lá';
        $category->slug = 'rau-an-la';

        $product = new Product;
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
        app()->setLocale('vi');

        $post = new Page;
        $post->forceFill([
            'id' => 5,
            'slug' => 'bi-quyet-trong-rau',
            'name' => 'Bí quyết trồng rau',
            'image' => 'images/news/bi-quyet-trong-rau.jpg',
            'description' => 'Mô tả ngắn',
            'created_at' => Carbon::create(2024, 2, 15, 0, 0, 0),
        ]);

        $result = $this->transformHomeNews(Collection::make([$post]));

        $this->assertCount(1, $result);
        $this->assertSame('bi-quyet-trong-rau', $result[0]['slug']);
        $this->assertSame('Bí quyết trồng rau', $result[0]['title']);
        $this->assertSame('15/02/2024', $result[0]['date_primary']);
        $this->assertSame('15-02-2024', $result[0]['date_secondary']);
        $this->assertSame('Mô tả ngắn', $result[0]['description_html']);
    }

    public function test_transform_post_detail_includes_content_and_user()
    {
        app()->setLocale('vi');

        $post = new Page;
        $post->forceFill([
            'id' => 7,
            'slug' => 'huong-dan-bon-phan',
            'name' => 'Hướng dẫn bón phân',
            'content' => '<p>Nội dung bài viết</p>',
            'seo_keyword' => 'phân bón, nông nghiệp',
            'created_at' => Carbon::create(2024, 3, 1, 0, 0, 0),
        ]);

        $user = new User;
        $user->name = 'Kỹ sư A';
        $user->image = 'images/users/ky-su-a.jpg';
        $post->setRelation('user', $user);

        $result = $this->transformPostDetail($post);

        $this->assertSame('Hướng dẫn bón phân', $result['title']);
        $this->assertSame('<p>Nội dung bài viết</p>', $result['content_html']);
        $this->assertSame('Kỹ sư A', $result['user']['name']);
        $this->assertSame('phân bón, nông nghiệp', $result['seo_keyword']);
    }

    public function test_transform_category_page_maps_children()
    {
        $parent = new Category;
        $parent->id = 1;
        $parent->name = 'Hạt giống';
        $parent->slug = 'hat-giong';

        $child = new Category;
        $child->name = 'Rau ăn lá';
        $child->slug = 'rau-an-la';

        $parent->setRelation('children', Collection::make([$child]));

        $result = $this->transformCategoryPage($parent);

        $this->assertSame('Hạt giống', $result['name']);
        $this->assertCount(1, $result['children']);
        $this->assertSame('Rau ăn lá', $result['children'][0]['name']);
    }

    public function test_transform_cart_summary_sums_line_totals()
    {
        $summary = $this->transformCartSummary([
            ['line_total' => 100000.0],
            ['line_total' => 50000.0],
        ]);

        $this->assertSame(150000.0, $summary['subtotal']);
        $this->assertSame(150000.0, $summary['total']);
    }
}

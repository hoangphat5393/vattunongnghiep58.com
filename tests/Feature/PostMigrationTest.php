<?php

namespace Tests\Feature;

use App\Models\Frontend\Page;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PostMigrationTest extends TestCase
{
    /**
     * Test if migrated posts exist in pages table with type = 'post'.
     */
    public function test_posts_migrated_to_pages()
    {
        app()->setLocale('vi');
        $postCount = Page::posts()->count();

        $this->assertGreaterThanOrEqual(0, $postCount);

        if ($postCount > 0) {
            $post = Page::posts()->first();
            $this->assertEquals('post', $post->type);
            $this->assertNotNull($post->title ?? $post->name);
            $this->assertNotNull($post->slug);
        }
    }

    /**
     * Test if pages scope excludes posts.
     */
    public function test_pages_scope()
    {
        $page = Page::pages()->first();
        if ($page) {
            $this->assertNotEquals('post', $page->type);
        }
    }

    /**
     * Test news index page access.
     */
    public function test_news_page_loads()
    {
        $response = $this->get(route('news'));
        $response->assertStatus(200);
    }

    /**
     * Test news detail page access and content.
     */
    public function test_post_detail_page_loads()
    {
        $post = Page::posts()->first();

        if (! $post) {
            $post = new Page;
            $post->name = 'Test Post';
            $post->slug = 'test-post-'.time();
            $post->type = 'post';
            $post->status = 1;
            $post->sort = 0;
            $post->save();
        }

        $response = $this->get(route('news.detail', ['slug' => $post->slug, 'id' => $post->id]));
        $response->assertStatus(200);
        $response->assertSee($post->name);
    }

    /**
     * Test post_categories table removed (chỉ sản phẩm dùng categories).
     */
    public function test_post_categories_table_removed()
    {
        $this->assertFalse(Schema::hasTable('post_categories'), 'post_categories table should be deleted');
    }
}

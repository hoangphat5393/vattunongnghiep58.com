<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Copy toàn bộ dữ liệu từ posts sang pages (type='post'), xóa bảng post_categories.
     * Sau này chỉ sản phẩm dùng categories; tin tức (post) nằm trong pages.
     */
    public function up(): void
    {
        if (! Schema::hasTable('posts')) {
            Schema::dropIfExists('post_categories');

            return;
        }

        if (! Schema::hasColumn('pages', 'type')) {
            Schema::table('pages', function (Blueprint $table) {
                $table->string('type')->default('page')->after('id');
            });
        }

        $hasTitle = Schema::hasColumn('pages', 'title');
        $hasName = Schema::hasColumn('pages', 'name');

        $posts = DB::table('posts')->orderBy('id')->get();
        foreach ($posts as $post) {
            if (DB::table('pages')->where('slug', $post->slug)->where('type', 'post')->exists()) {
                continue;
            }
            $slug = $post->slug;
            $originalSlug = $slug;
            $count = 1;
            while (DB::table('pages')->where('slug', $slug)->exists()) {
                $slug = $originalSlug.'-'.$count;
                $count++;
            }

            $pageData = [
                'type' => 'post',
                'slug' => $slug,
                'description' => $post->description ?? null,
                'content' => $post->content ?? null,
                'image' => $post->image ?? null,
                'status' => $post->status ?? 1,
                'created_at' => $post->created_at ?? now(),
                'updated_at' => $post->updated_at ?? now(),
            ];

            if ($hasTitle) {
                $pageData['title'] = $post->name ?? $post->title ?? null;
            }
            if ($hasName) {
                $pageData['name'] = $post->name ?? null;
            }
            if (Schema::hasColumn('pages', 'sort')) {
                $pageData['sort'] = $post->sort ?? 0;
            }
            if (Schema::hasColumn('pages', 'seo_title')) {
                $pageData['seo_title'] = $post->seo_title ?? null;
            }
            if (Schema::hasColumn('pages', 'seo_keyword')) {
                $pageData['seo_keyword'] = $post->seo_keyword ?? null;
            }
            if (Schema::hasColumn('pages', 'seo_description')) {
                $pageData['seo_description'] = $post->seo_description ?? null;
            }

            DB::table('pages')->insert($pageData);
        }

        Schema::dropIfExists('post_categories');
    }

    public function down(): void
    {
        // Không tạo lại post_categories; chỉ rollback dữ liệu nếu cần (tùy chọn).
    }
};

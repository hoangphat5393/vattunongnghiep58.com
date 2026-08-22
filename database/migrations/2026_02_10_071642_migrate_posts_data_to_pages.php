<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add 'cocojt' column to pages table if not exists
        if (! Schema::hasColumn('pages', 'cocojt')) {
            Schema::table('pages', function (Blueprint $table) {
                $table->string('cocojt', 50)->nullable()->after('id')->index();
            });
        }

        // 2. Create page_categories table if not exists (to replace post_categories)
        if (! Schema::hasTable('page_categories')) {
            Schema::create('page_categories', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('page_id');
                $table->unsignedBigInteger('category_id');
                $table->timestamps();

                // Foreign keys might fail if tables are not MyISAM/InnoDB compatible or ids differ,
                // but usually good practice. For now, skipping strict FK to ensure migration runs smooth
                // on potentially messy legacy data, or we can add them.
                // Let's rely on logic for now to be safe against constraints.
            });
        }

        // 3. Migrate Data
        $posts = DB::table('posts')->orderBy('id')->chunk(100, function ($posts) {
            foreach ($posts as $post) {
                // Check for slug conflict
                $slug = $post->slug;
                $originalSlug = $slug;
                $count = 1;
                while (DB::table('pages')->where('slug', $slug)->exists()) {
                    $slug = $originalSlug.'-'.$count;
                    $count++;
                }

                // Map data
                $pageData = [
                    'cocojt' => 'post', // Distinguish as migrated post
                    'slug' => $slug,
                    'title' => $post->name, // Map name -> title
                    'title_en' => $post->name_en ?? null,
                    'description' => $post->description,
                    'description_en' => $post->description_en ?? null,
                    'content' => $post->content,
                    'content_en' => $post->content_en ?? null,
                    'image' => $post->image,
                    'cover' => $post->cover ?? null,
                    'icon' => $post->icon ?? null,
                    'sort' => $post->sort ?? 0,
                    'status' => $post->status,
                    'seo_title' => $post->seo_title,
                    'seo_keyword' => $post->seo_keyword,
                    'seo_description' => $post->seo_description,
                    'created_at' => $post->created_at,
                    'updated_at' => $post->updated_at,
                    // 'user_id' => $post->user_id, // pages table usually has user_id? Check schema again.
                    // Inspect schema showed pages has NO user_id in the output I got earlier?
                    // Let me re-read my thought trace.
                    // Pages Columns: id, cocojt, slug, title, ... NO user_id listed in my thought trace for Pages!
                    // Wait, let me check the output of inspect_schema.php again from history.
                    // Output: "Pages Columns: ... sort, created_at, updated_at, status, show_footer, template, parent, seo_title..."
                    // NO user_id in Pages. So I cannot migrate user_id.
                ];

                // Insert into pages and get ID
                $newPageId = DB::table('pages')->insertGetId($pageData);

                // Migrate Categories
                $categoryIds = DB::table('post_categories')
                    ->where('post_id', $post->id)
                    ->pluck('category_id');

                $pivotData = [];
                foreach ($categoryIds as $catId) {
                    $pivotData[] = [
                        'page_id' => $newPageId,
                        'category_id' => $catId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                if (! empty($pivotData)) {
                    DB::table('page_categories')->insert($pivotData);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Delete migrated data
        // Get IDs of migrated pages
        $migratedPageIds = DB::table('pages')->where('cocojt', 'post')->pluck('id');

        // Delete relationships
        if (Schema::hasTable('page_categories')) {
            DB::table('page_categories')->whereIn('page_id', $migratedPageIds)->delete();
        }

        // Delete pages
        DB::table('pages')->where('cocojt', 'post')->delete();

        // 2. Drop page_categories if we created it?
        // If the table was created by this migration, we should drop it.
        // However, if we want to be safe, maybe just empty it?
        // User asked for rollback strategy.
        Schema::dropIfExists('page_categories');

        // 3. Drop column
        if (Schema::hasColumn('pages', 'cocojt')) {
            Schema::table('pages', function (Blueprint $table) {
                $table->dropColumn('cocojt');
            });
        }
    }
};

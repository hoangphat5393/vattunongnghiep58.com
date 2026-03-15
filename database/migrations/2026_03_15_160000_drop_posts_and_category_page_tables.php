<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Xóa bảng posts và category_page.
     * Tin tức (bài viết) dùng bảng pages với type='post'.
     */
    public function up(): void
    {
        Schema::dropIfExists('category_page');
        Schema::dropIfExists('posts');
    }

    public function down(): void
    {
        // Không tạo lại; dữ liệu đã nằm trong pages (type=post).
    }
};

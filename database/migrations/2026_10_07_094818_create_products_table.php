<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->nullable();
            $table->string('slug')->nullable()->index();
            $table->string('type', 50)->nullable()->comment('product:sản phẩm thương; extra: sản phẩm kèm theo');
            $table->string('name', 1000)->nullable();
            $table->string('name_en')->nullable();
            $table->mediumText('description')->nullable();
            $table->longText('description_en')->nullable();
            $table->longText('content')->nullable();
            $table->longText('content_en')->nullable();
            $table->text('spec_short')->nullable();
            $table->text('image')->nullable();
            $table->string('cover')->nullable();
            $table->string('icon')->nullable();
            $table->longText('gallery')->nullable();
            $table->boolean('hot')->default(false);
            $table->boolean('status')->nullable()->default(false)->comment('0: thành công, 1: lưu nháp chờ duyệt, 2: lưu nháp chưa thanh toán');
            $table->integer('sort')->nullable()->default(0);
            $table->integer('stock')->nullable()->default(0);
            $table->string('price', 50)->nullable()->default('0');
            $table->string('sale_price')->nullable();
            $table->string('price_type', 100)->nullable()->default('price')->comment('price: giá; contact: liên hệ');
            $table->integer('unit_qly')->nullable();
            $table->string('unit', 50)->nullable()->comment('Đơn vị bán (hộp, vĩ)');
            $table->string('delivery_method')->nullable();
            $table->string('sku', 1000)->nullable();
            $table->dateTime('expiry')->nullable();
            $table->string('currency', 20)->nullable();
            $table->longText('seo_title')->nullable();
            $table->longText('seo_keyword')->nullable();
            $table->longText('seo_description')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();

            $table->index(['status', 'id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

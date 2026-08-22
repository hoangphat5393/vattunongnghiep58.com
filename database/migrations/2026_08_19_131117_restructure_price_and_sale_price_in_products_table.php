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
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'sale_price')) {
                $table->string('sale_price', 255)->nullable()->after('price');
            }
        });

        // Migrate existing data from price & cost_price safely
        $products = DB::table('products')->get();
        foreach ($products as $product) {
            $p1 = (float) ($product->price ?? 0);
            $p2 = (float) (isset($product->cost_price) ? $product->cost_price : 0);

            if ($p1 > 0 && $p2 > 0 && $p1 != $p2) {
                $regularPrice = max($p1, $p2);
                $salePrice = min($p1, $p2);
                DB::table('products')->where('id', $product->id)->update([
                    'price' => (string) $regularPrice,
                    'sale_price' => (string) $salePrice,
                ]);
            }
        }

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'cost_price')) {
                $table->dropColumn('cost_price');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'cost_price')) {
                $table->string('cost_price', 255)->nullable()->after('price');
            }
        });

        $products = DB::table('products')->get();
        foreach ($products as $product) {
            $reg = (float) ($product->price ?? 0);
            $sale = (float) ($product->sale_price ?? 0);

            if ($sale > 0 && $reg > 0 && $sale < $reg) {
                DB::table('products')->where('id', $product->id)->update([
                    'price' => (string) $sale,
                    'cost_price' => (string) $reg,
                ]);
            }
        }

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'sale_price')) {
                $table->dropColumn('sale_price');
            }
        });
    }
};

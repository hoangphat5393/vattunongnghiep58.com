<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\StoreProduct;
use App\Http\Requests\Admin\Product\UpdateProduct;
use App\Models\Backend\Category;
use App\Models\Backend\Product;
use App\Models\ProductPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    private function buildCategoryTreeData(): array
    {
        $categories = Category::query()
            ->orderByDesc('sort')
            ->get(['id', 'name', 'parent', 'sort']);

        $childrenMap = $categories->groupBy('parent');
        $categoryTree = $childrenMap->get(0, collect());

        return [$categoryTree, $childrenMap];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::with(['user', 'categories', 'prices'])
            ->filter($request)
            ->orderByDesc('sort')
            ->paginate(20)
            ->appends($request->all());

        $total_item = $products->total();

        return view('backend.product.index', compact('products', 'total_item'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        [$categoryTree, $childrenMap] = $this->buildCategoryTreeData();

        return view('backend.product.single', compact('categoryTree', 'childrenMap'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProduct $request)
    {
        // Không mass-assign id (form tạo mới gửi id=0 → làm product_id=0 khi sync)
        $data = $request->except(['_token', '_method', 'created_at', 'submit', 'category_id', 'id']);

        if ($request->slug) {
            $data['slug'] = addslashes($request->slug);
        } else {
            $data['slug'] = Str::slug($data['name']);
        }

        $data['description'] = $data['description'] ? htmlspecialchars($data['description']) : '';
        $data['description_en'] = $data['description'] ? htmlspecialchars($data['description']) : '';
        $data['content'] = $data['content'] ? htmlspecialchars($data['content']) : '';
        $data['content_en'] = $data['content'] ? htmlspecialchars($data['content']) : '';

        $data['seo_title'] = $data['seo_title'] ? $data['seo_title'] : $data['name'];

        // xử lý gallery
        // $galleries = $request->gallery ?? '';
        // if ($galleries != '') {
        //     $galleries = array_filter($galleries);
        //     $data['gallery'] = $galleries ? serialize($galleries) : '';
        // }

        // ADMIN ID
        $data['user_id'] = Auth::guard('admin')->user()->id;

        // dd($data);
        $product = DB::transaction(function () use ($data, $request) {
            $product = Product::create($data);
            $insert_id = $product->id;

            $product->update(['sort' => $insert_id]);

            $category_id = $request->category_id ?? [];
            $product->categories()->sync($category_id);

            $this->syncProductPrices($product, $request);

            return $product;
        });

        $insert_id = $product->id;

        $save = $request->submit ?? 'apply';
        if ($save == 'apply') {
            $msg = 'Product has been created successfully';
            $url = route('admin.product.edit', [$insert_id]);
            msg_move_page($msg, $url);
        } else {
            return redirect(route('admin.product.index'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product, int $id)
    {
        $product = $product::find($id);

        return view('backend.product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product, int $id)
    {
        $product = $product::findorfail($id);

        if ($product) {
            [$categoryTree, $childrenMap] = $this->buildCategoryTreeData();

            return view('backend.product.single', compact('product', 'categoryTree', 'childrenMap'));
        } else {
            return view('404');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProduct $request, int $id)
    {
        $data = $request->except(['_token', '_method', 'category_id', 'created_at', 'submit', 'user_id', 'id', 'prices', 'prices_default']);

        if ($request->slug) {
            $data['slug'] = addslashes($request->slug);
        } else {
            $data['slug'] = Str::slug($data['name'] ?? '');
        }

        DB::transaction(function () use ($data, $id, $request) {
            $product = Product::findOrFail($id);
            $product->update($data);

            $category_id = $request->category_id ?? [];
            $product->categories()->sync(is_array($category_id) ? $category_id : []);

            $this->syncProductPrices($product, $request);
        });

        $save = $request->submit ?? 'apply';
        if ($save == 'apply') {
            $msg = 'Product has been updated successfully';
            $url = route('admin.product.edit', [$id]);
            msg_move_page($msg, $url);
        } else {
            return redirect(route('admin.product.index'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        Product::findOrFail($id)->delete();

        return redirect()->route('admin.product.index')->with('success', 'Product deleted successfully.');
    }

    public function import()
    {
        return view('backend.product.import');
    }

    public function importProcess(Request $request)
    {
        // TODO: Implement import logic
        return redirect()->back()->with('success', 'Feature under development.');
    }

    protected function syncProductPrices(Product $product, Request $request): void
    {
        $prices = $request->input('prices', []);
        if (! is_array($prices)) {
            $prices = [];
        }

        $prices = array_values(array_filter($prices, function ($row) {
            $label = is_array($row) ? ($row['label'] ?? null) : null;
            $price = is_array($row) ? ($row['price'] ?? null) : null;

            return is_string($label) && trim($label) !== '' && $price !== null && $price !== '';
        }));

        $product->prices()->delete();

        if (count($prices) === 0) {
            return;
        }

        $defaultIndex = $request->input('prices_default');
        $defaultIndex = is_numeric($defaultIndex) ? (int) $defaultIndex : 0;
        if (! array_key_exists($defaultIndex, $prices)) {
            $defaultIndex = 0;
        }

        $defaultRow = null;

        foreach ($prices as $i => $row) {
            $label = trim((string) ($row['label'] ?? ''));
            $price = (int) preg_replace('/\D+/', '', (string) ($row['price'] ?? 0));
            $unit = isset($row['unit']) ? trim((string) $row['unit']) : null;
            $status = isset($row['status']) ? (int) $row['status'] : 1;

            $isDefault = $i === $defaultIndex;

            $productPrice = ProductPrice::create([
                'product_id' => $product->id,
                'label' => $label,
                'price' => max(0, $price),
                'unit' => $unit !== '' ? $unit : null,
                'is_default' => $isDefault,
                'sort' => $i,
                'status' => $status === 0 ? 0 : 1,
            ]);

            if ($productPrice->is_default) {
                $defaultRow = $productPrice;
            }
        }

        if ($defaultRow) {
            $product->update([
                'price_type' => 'price',
                'price' => $defaultRow->price,
                'unit' => $defaultRow->unit,
            ]);
        }
    }
}

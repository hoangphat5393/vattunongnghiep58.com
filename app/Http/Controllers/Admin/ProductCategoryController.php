<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Backend\ProductCategory;
use App\Models\Backend\Category;
use App\Http\Requests\Admin\ProductCategory\StoreProductCategory;
use App\Http\Requests\Admin\ProductCategory\UpdateProductCategory;
use App\Models\Backend\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{
    public $data = [];
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::filter($request)->where(['parent' => 0])
            ->orderBy('sort', 'asc')->paginate(20)
            ->appends($request->all());

        $total_item = $categories->count();

        return view('backend.product-category.index', compact('categories', 'total_item'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.product-category.single');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductCategory $request)
    {
        $data = request()->except(['created_at', 'submit']);

        if ($request->slug) {
            $data['slug'] = addslashes($request->slug);
        } else {
            $data['slug'] = Str::slug($data['name']);
        }

        // $data['name_en'] = $data['name'];
        $data['seo_title'] = $data['seo_title'] ? $data['seo_title'] : $data['name'];

        // USER ID
        $data['user_id'] = Auth::guard('admin')->user()->id;

        $response = Category::create($data);
        $insert_id = $response->id;

        // Update sort
        $response->update(['sort' => $insert_id]);

        $save = $request->submit ?? 'apply';

        if ($save == 'apply') {
            $msg = "Category has been created successfully";
            $url = route('admin.product-category.edit', array($insert_id));
            msg_move_page($msg, $url);
        } else {
            return redirect(route('admin.product-category.index'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductCategory $productCategory, int $id)
    {
        $category = Category::find($id);
        return view('backend.work-category.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductCategory $productCategory, int $id)
    {
        // $this->data['category'] = Category::find($id);
        $category = Category::find($id);
        if ($category) {
            return view('backend.product-category.single', compact('category'));
            // return view('admin.product-category.single', ['data' => $this->data]);
        } else {
            return view('404');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductCategory $request, ProductCategory $productCategory)
    {
        $data = request()->except(['created_at', 'submit', 'admin_id']);

        if ($request->slug) {
            $data['slug'] = addslashes($request->slug);
        } else {
            $data['slug'] = Str::slug($data['name']);
        }

        // $data['name_en'] = $data['name'];

        // id post
        $sid = $request->id ?? 0;

        $productCategory = Category::findOrFail($sid);
        $productCategory->update($data);

        $save = $request->submit ?? 'apply';

        if ($save == 'apply') {
            $msg = "Product category has been updated successfully";
            $url = route('admin.product-category.edit', array($sid));
            msg_move_page($msg, $url);
        } else {
            return redirect(route('admin.product-category.index'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductCategory $productCategory, int $id)
    {
        $productCategory->find($id)->destroy();
        return redirect()->route('admin.product-category.index')->with('success', 'Product category deleted successfully.');
    }
}

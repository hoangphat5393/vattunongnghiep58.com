<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Backend\Category;
use Auth;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostCategoryController extends Controller
{
    public $data = [];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->data['title_head'] = __('Danh mục tin tức');
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        $categories = Category::where(['parent' => 0])->orderBy('sort', 'asc')->paginate(20);

        $total_item = $categories->total();

        $childrenMap = Category::orderBy('sort', 'asc')->get()->groupBy('parent');

        return view('backend.post-category.index', compact('categories', 'total_item', 'childrenMap'));
    }

    public function create()
    {
        $childrenMap = Category::where('status', 1)->orderBy('sort', 'asc')->get()->groupBy('parent');

        return view('backend.post-category.single', ['data' => $this->data, 'childrenMap' => $childrenMap]);
    }

    public function edit($id)
    {
        $this->data['category'] = Category::find($id);
        if ($this->data['category']) {
            $childrenMap = Category::where('status', 1)->orderBy('sort', 'asc')->get()->groupBy('parent');

            return view('backend.post-category.single', ['data' => $this->data, 'category' => $this->data['category'], 'childrenMap' => $childrenMap]);
        } else {
            return view('404');
        }
    }

    public function post(Request $request)
    {
        $data = request()->except(['_token', 'tab_lang', 'gallery', 'category_id', 'created_at', 'submit', 'custom_field']);

        // id post
        $sid = $request->id ?? 0;

        $data['slug'] = addslashes($request->slug);
        // if ($data['slug'] == '') **
        $data['slug'] = Str::slug($data['name']);

        // $slug = addslashes($data['slug']);
        if (empty($slug) || $slug == '') {
            $slug = Str::slug($data['name']);
        }

        $data['description'] = $data['description'] ? htmlspecialchars($data['description']) : '';
        // $data['content'] = $data['content'] ? htmlspecialchars($data['content']) : '';
        $data['seo_title'] = $data['seo_title'] ? $data['seo_title'] : $data['name'];

        $save = $request->submit ?? 'apply';

        // USER ID
        $data['user_id'] = Auth::guard('admin')->user()->id;

        if ($sid > 0) {
            $post_id = $sid;
            $respons = Category::where('id', $sid)->update($data);
        } else {
            $respons = Category::create($data);
            $post_id = $respons->id;

            // if sort = 0 => update sort
            Category::where('id', $post_id)->update(['sort' => $post_id]);
        }

        if ($save == 'apply') {
            $msg = 'Category has been Updated';
            $url = route('admin.post-category.edit', [$post_id]);
            msg_move_page($msg, $url);
        } else {
            return redirect(route('admin.post-category.index'));
        }
    }

    public function store(Request $request)
    {
        return $this->post($request);
    }

    public function update(Request $request, $id)
    {
        $request->merge(['id' => $id]);

        return $this->post($request);
    }

    public function show($id)
    {
        Category::findOrFail($id);

        return redirect()->route('admin.post-category.edit', $id);
    }

    public function destroy($id)
    {
        Category::findOrFail($id)->delete();

        return redirect()->route('admin.post-category.index')->with('success', 'Category deleted successfully.');
    }
}

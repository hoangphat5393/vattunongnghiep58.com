<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Post\StorePost;
use App\Http\Requests\Admin\Post\UpdatePost;
use App\Models\Backend\Category;
use App\Models\Backend\Page;
use Auth;
use DB;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public $data = [];

    public $route = [];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->route = [
            'index' => '',
            'post' => '',
            'edit' => '',
        ];
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        $appends = [
            'search_name' => request('search_name'),
        ];

        $db = Page::posts()->with(['user'])->select('*');
        if (request('search_name') != '') {
            $db->where('name', 'like', '%'.request('search_name').'%');
        }

        // User data
        // if (Auth::guard('admin')->user()->admin_level != 99999) {
        //     $db->where('user_id', '=', Auth::guard('admin')->user()->id);
        // }
        // Page model does not have user_id in schema from inspection, so commenting out for now.

        $data_post = $db->orderByDesc('sort')->paginate(20)->appends($appends);
        $count_item = $db->count();

        return view('backend.post.index')->with(['data' => $data_post, 'total_item' => $count_item]);
    }

    public function create()
    {
        return view('backend.post.single', $this->data);
    }

    public function edit($id)
    {
        $this->data['edit_data'] = Page::posts()->find($id);
        if ($this->data['edit_data']) {
            return view('backend.post.single', $this->data);
        } else {
            return view('404');
        }
    }

    /**
     * REST alias: routes/admin.php maps POST /admin/post → store.
     */
    public function store(StorePost $request)
    {
        return $this->post($request);
    }

    /**
     * REST alias: routes/admin.php maps PUT /admin/post/{id} → update.
     */
    public function update(UpdatePost $request, $id)
    {
        $request->merge(['id' => $id]);

        return $this->post($request);
    }

    public function post(Request $request)
    {
        $data = $request->only([
            'name',
            'name_en',
            'slug',
            'title',
            'description',
            'description_en',
            'content',
            'content_en',
            'image',
            'status',
            'sort',
            'seo_title',
            'seo_description',
            'seo_keyword',
            'type',
        ]);

        // id post
        $sid = $request->id ?? 0;

        $data['name'] = $data['name'] ?? $data['title'] ?? '';
        unset($data['title']); // Cột DB là `name`, không có `title`

        if ($request->slug) {
            $data['slug'] = addslashes($request->slug);
        } else {
            $data['slug'] = Str::slug($data['name'] ?? '');
        }

        $data['description'] = ! empty($data['description']) ? htmlspecialchars($data['description']) : '';
        $data['content'] = ! empty($data['content']) ? htmlspecialchars($data['content']) : '';
        $data['seo_title'] = ! empty($data['seo_title']) ? $data['seo_title'] : ($data['name'] ?? '');

        // $data['description'] = $request->description ? htmlspecialchars($request->description) : '';
        // $data['content'] = $request->content ? htmlspecialchars($request->content) : '';

        // $data['description_en'] = $request->description_en ? htmlspecialchars($request->description_en) : '';
        // $data['content_en'] = $request->content_en ? htmlspecialchars($request->content_en) : '';

        // xử lý gallery
        $galleries = $request->gallery ?? '';
        if ($galleries != '') {
            $galleries = array_filter($galleries);
            $data['gallery'] = $galleries ? serialize($galleries) : '';
        }
        // end xử lý gallery

        $save = $request->submit ?? 'apply';

        // ADMIN ID
        // $data['user_id'] = Auth::guard('admin')->user()->id; // Page has no user_id

        // Bài viết (tin tức) luôn có type = post
        $data['type'] = 'post';

        if ($sid > 0) {
            $post_id = $sid;
            $respons = Page::where('id', $sid)->update($data);
        } else {
            $respons = Page::create($data);
            $insert_id = $respons->id;
            $post_id = $insert_id;

            // // if sort = 0 => update sort
            Page::where('id', $post_id)->update(['sort' => $post_id]);

            // $db = ShopProduct::find(1);
            // $db->sort = $post_id;
            // $db->save();
        }

        // SAVE CATEGORY
        // $category_id = $request->category_id ?? '';
        // if ($category_id != '') {
        //     $product = Page::find($post_id);
        //     $product->categories()->sync($category_id);
        // }

        if ($save == 'apply') {
            $msg = 'Data has been Updated';
            $url = route('admin.post.edit', [$post_id]);
            msg_move_page($msg, $url);
            // return redirect(route('admin.postEdit', array($post_id)));
        } else {
            return redirect(route('admin.post.index'));
        }
    }

    /**
     * REST alias: routes/admin.php maps GET /admin/post/{id} → show.
     */
    public function show($id)
    {
        Page::posts()->findOrFail($id);

        return redirect()->route('admin.post.edit', $id);
    }

    /**
     * REST alias: routes/admin.php maps DELETE /admin/post/{id} → destroy.
     */
    public function destroy($id)
    {
        Page::posts()->findOrFail($id)->delete();

        return redirect()->route('admin.post.index')->with('success', 'Post deleted successfully.');
    }
}

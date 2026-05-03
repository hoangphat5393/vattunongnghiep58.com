<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AlbumItem\StoreAlbumItem;
use App\Http\Requests\Admin\AlbumItem\UpdateAlbumItem;
use App\Models\Backend\AlbumItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlbumItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        //

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(int $album_id)
    {

        $dataResponse = [

            'title_head' => 'Thêm item',

            'parent' => $album_id,
            'image' => '/upload/images/placeholder.png',
        ];

        return response()->json([

            'view' => view('backend.album.includes.form', $dataResponse)->render(),

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAlbumItem $request, int $album_id)
    {

        $data = $request->except(['_token', 'created_at', 'submit', 'tab_lang', 'slider']);
        $data['album_id'] = $album_id;
        $data['description'] = htmlspecialchars((string) ($data['description'] ?? ''));
        $data['description_en'] = htmlspecialchars((string) ($data['description_en'] ?? ''));

        $last_item = AlbumItem::where('album_id', $album_id)->orderby('sort', 'desc')->first();

        if ($last_item && empty($data['sort'])) {
            $data['sort'] = (int) $last_item->sort + 1;
        }

        // dd($request->all(), $album_id, $data);

        // ADMIN ID

        // $data['admin_id'] = Auth::guard('admin')->user()->id;

        AlbumItem::create($data);

        $album_items = AlbumItem::where('album_id', $album_id)->orderby('sort', 'asc')->get();

        // Cache::forget('slider_home');

        $view = view('backend.album.includes.album-items', ['album_items' => $album_items])->render();

        return response()->json(['view' => $view]);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {

        return redirect()->route('admin.albumItem.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {

        // $id = $request->id ?? 0;

        // $parent = $request->parent ?? 0;

        $albumItem = AlbumItem::findorfail($id);

        if ($albumItem) {

            $parent = $albumItem->album_id;

            $dataResponse = [

                'title_head' => 'Sửa slider',

                'album_item' => $albumItem,

                'parent' => $parent,

            ];

            return response()->json([

                'view' => view('backend.album.includes.form', $dataResponse)->render(),

            ]);
        }

        return response('404 data Not Found');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAlbumItem $request, int $id)
    {

        $data = $request->except(['_token', '_method']);
        $data['description'] = htmlspecialchars((string) ($data['description'] ?? ''));
        $data['description_en'] = htmlspecialchars((string) ($data['description_en'] ?? ''));

        $album_item = AlbumItem::findOrFail($id);
        $album_item->update($data);

        // Get all items

        $album_items = AlbumItem::where('album_id', $album_item->album_id)->orderby('sort', 'asc')->get();

        // dd($album_items);

        $view = view('backend.album.includes.album-items', ['album_items' => $album_items])->render();

        return response()->json(['view' => $view]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {

        if ($id) {

            $albumItem = AlbumItem::findorfail($id);
            $album_id = $albumItem->album_id;

            // Delete

            $albumItem->delete();

            // Upate view

            $album_items = AlbumItem::where('album_id', $album_id)->orderby('sort', 'asc')->get();

            $view = view('backend.album.includes.album-items', ['album_items' => $album_items, 'album_id' => $album_id])->render();

            return response()->json(['view' => $view]);
        } else {

            return response('404 data Not Found');
        }
    }

    public function ajaxUpdateSort(Request $request)
    {

        $sort = $request->input('sort');

        if ($sort) {

            // Xử lý logic cập nhật thứ tự các mục theo yêu cầu

            // Ví dụ: Lưu thứ tự mới vào cơ sở dữ liệu

            foreach ($sort as $index => $id) {

                // Cập nhật thứ tự cho từng mục, giả sử bạn có model AlbumItem

                $albumItem = AlbumItem::find($id);
                if ($albumItem) {
                    $albumItem->update(['sort' => $index]);
                }
            }

            return response()->json(['Update success' => true]);
        } else {

            return response('404 data Not Found');
        }
    }

    /**
     * Stored multiple items.
     */
    public function storeMultiple(Request $request, int $album_id)
    {

        // Nhận mảng fileUrls từ request

        $fileUrls = $request->input('fileUrls', []);

        if (! is_array($fileUrls) || empty($fileUrls)) {
            return response()->json([
                'success' => false,
                'message' => 'Danh sách ảnh trống',
                'data' => '',
                'errors' => [],
            ], 422);
        }

        $data = [];

        try {

            $lastSort = (int) AlbumItem::where('album_id', $album_id)->max('sort');
            $now = now();

            // Lưu từng đường dẫn vào cơ sở dữ liệu

            foreach ($fileUrls as $index => $fileUrl) {
                $ex = explode('/', $fileUrl);

                $data[] = [

                    'name' => end($ex),

                    'name_en' => end($ex),

                    'image' => $fileUrl,

                    'image_en' => $fileUrl,

                    'album_id' => $album_id,

                    'sort' => $lastSort + $index + 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                    // 'admin_id' => Auth::guard('admin')->user()->id,

                ];

                // array_push($data, $new_data);

            }

            // $count_row = count($data);

            // $inserted_data = AlbumItem::insert($data);

            AlbumItem::insert($data);

            // if ($inserted_data) {

            // }

            $album_items = AlbumItem::where('album_id', $album_id)->orderBy('sort', 'asc')->get();

            $response = [

                'success' => true, // True nếu status code từ 200-299

                'message' => 'Insert thành công',

                'data' => [],

                'view' => view('backend.album.includes.album-items', ['album_items' => $album_items])->render(),

            ];

            // Trả về phản hồi JSON

            return response()->json($response);
        } catch (\Exception $e) {

            // Trả về lỗi nếu có vấn đề khi lưu

            $response = [

                'success' => false,

                'message' => 'Insert thất bại',

                'data' => '',

                'errors' => [

                    'code' => $e->getCode(),

                    'details' => $e->getMessage(),

                ],

            ];

            return response()->json($response);
        }
    }
}

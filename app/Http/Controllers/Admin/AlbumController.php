<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Album\StoreAlbum;
use App\Http\Requests\Admin\Album\UpdateAlbum;
use App\Models\Backend\Album;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    public $data = [];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $album = Album::filter($request)

            ->orderByDesc('sort')

            ->paginate(20)

            ->appends($request->all());

        $total_item = $album->total();

        return view('backend.album.index', compact('album', 'total_item'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('backend.album.single', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAlbum $request)
    {

        // $request->validate([

        //     'name' => 'required|string|max:255',

        //     'description' => 'nullable|string',

        // ]);

        $data = $request->except(['_token', 'created_at', 'submit', 'tab_lang', 'slider']);

        // ADMIN ID

        $respons = Album::create($data);

        $insert_id = $respons->id;

        // Update sort

        $respons->update(['sort' => $insert_id]);

        $save = $request->submit ?? 'apply';

        if ($save == 'apply') {

            $msg = 'Album has been created successfully';

            $url = route('admin.album.edit', $insert_id);

            msg_move_page($msg, $url);
        } else {

            return redirect(route('admin.album.index'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {

        return redirect()->route('admin.album.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {

        $album = Album::findorfail($id);
        $this->data['album'] = $album;
        $this->data['album_items'] = $album->items()->orderBy('sort', 'asc')->get();

        if ($this->data['album']) {

            return view('backend.album.single', $this->data);
        } else {

            return view('404');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAlbum $request, int $id)
    {

        $data = $request->except(['_token', '_method', 'created_at', 'submit', 'tab_lang', 'slider', 'user_id']);

        // dd($data);

        $sid = $id;

        $save = $request->submit ?? 'apply';

        if ($sid > 0) {

            $album = Album::findOrFail($sid);

            $album->update($data);
        }

        if ($save == 'apply') {

            $msg = 'Album has been updated successfully';

            $url = route('admin.album.edit', [$sid]);

            msg_move_page($msg, $url);
        } else {

            return redirect(route('admin.album.index'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Album $album)
    {

        //

    }

    /**
     * Remove the specified resource from storage.
     */
    public function library(Request $request)
    {

        return view('backend.album.library');
    }
}

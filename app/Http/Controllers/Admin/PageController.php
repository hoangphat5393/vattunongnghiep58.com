<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Page\StorePage;
use App\Http\Requests\Admin\Page\UpdatePage;
use App\Models\Backend\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public $data = [];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pages = Page::pages()->with(['user'])->filter($request)->orderByDesc('sort')->paginate(20)->appends($request->all());

        $total_item = $pages->total();

        return view('backend.page.index', compact('pages', 'total_item'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.page.single');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePage $request)
    {
        $data = $request->except(['created_at', 'submit', 'id']);

        if ($request->slug) {
            $data['slug'] = addslashes($request->slug);
        } else {
            $data['slug'] = Str::slug($data['name']);
        }
        $data['seo_title'] = $data['seo_title'] ? $data['seo_title'] : $data['name'];

        // ADMIN ID
        if (Schema::hasTable('pages') && Schema::hasColumn('pages', 'user_id')) {
            $data['user_id'] = Auth::guard('admin')->user()->id;
        }

        // dd($data);
        $response = Page::create($data);
        $insert_id = $response->id;

        // Update sort
        $response->update(['sort' => $insert_id]);

        $save = $request->submit ?? 'apply';
        if ($save == 'apply') {
            $msg = 'Page has been created successfully';
            $url = route('admin.page.edit', [$insert_id]);
            msg_move_page($msg, $url);
        } else {
            return redirect(route('admin.page.index'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Page $page, $id)
    {
        $page = $page->findorfail($id);

        return view('backend.page.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page, $id)
    {
        $page = Page::pages()->findorfail($id);
        if ($page) {
            return view('backend.page.single', compact('page'));
        } else {
            return view('404');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePage $request, Page $page)
    {
        $data = request()->except(['created_at', 'submit', 'user_id', 'id']);

        if ($request->slug) {
            $data['slug'] = addslashes($request->slug);
        } else {
            $data['slug'] = Str::slug($data['name']);
        }

        $page = Page::findOrFail($request->id);
        $page->update($data);

        $save = $request->submit ?? 'apply';
        if ($save == 'apply') {
            $msg = 'Page has been updated successfully';
            $url = route('admin.page.edit', [$request->id]);
            msg_move_page($msg, $url);
        } else {
            return redirect(route('admin.page.index'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page, $id)
    {
        $page->find($id)->delete();

        return redirect()->route('admin.page.index')->with('success', 'Page deleted successfully.');
    }
}

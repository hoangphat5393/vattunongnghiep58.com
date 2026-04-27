<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Backend\Category;
use App\Models\Backend\Menu;
use App\Models\Backend\MenuItems;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $menulist = Menu::get();

        $indmenu = Menu::find($request->menu);

        $menus = MenuItems::where('menu_id', $request->menu)->orderBy('sort', 'asc')->get();

        $categories = Category::query()
            ->orderByDesc('sort')
            ->get(['id', 'name', 'slug', 'parent', 'sort']);

        $childrenMap = $categories->groupBy('parent');
        $categoryTree = $childrenMap->get(0, collect());

        return view('backend.setting.menu', compact('menus', 'indmenu', 'menulist', 'childrenMap', 'categoryTree'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        //

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $menu = Menu::create(['name' => $request->input('menuname')]);

        return response()->json(['resp' => $menu->id]);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        //

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        //

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $arraydata = $request->input('arraydata');

        if (is_array($arraydata)) {

            foreach ($arraydata as $value) {

                $menuitem = MenuItems::find($value['id']);

                $menuitem->label = $value['label'] ?? '';

                $menuitem->image = $value['image'];

                $menuitem->slug = $value['slug'] ?? '';

                $menuitem->link = $value['link'] ?? '';

                $menuitem->class = $value['class'] ?? '';

                // if (config('menu.use_roles')) {

                //     $menuitem->role_id = $value['role_id'] ? $value['role_id'] : 0;

                // }

                $menuitem->save();

            }

        } else {

            $menuitem = MenuItems::find($request->input('id'));

            $menuitem->label = $request->input('label');

            $menuitem->image = $request->input('image');

            $menuitem->slug = $request->input('slug');

            $menuitem->link = $request->input('url');

            $menuitem->class = $request->input('clases');

            // if (config('menu.use_roles')) {

            //     $menuitem->role_id = request()->input("role_id") ? request()->input("role_id") : 0;

            // }

            $menuitem->save();

        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $menus = new MenuItems;

        $getall = $menus->getall($id);

        if (count($getall) == 0) {

            Menu::destroy($id);

            return response()->json(['message' => 'you delete this item']);

        } else {

            return response()->json(['message' => 'You have to delete all items first', 'error' => 1]);

        }

    }

    public function generatemenucontrol(Request $request)
    {

        $menu = Menu::find($request->input('idmenu'));

        $menu->name = $request->input('menuname');

        $menu->save();

        if (is_array($request->input('arraydata'))) {

            foreach ($request->input('arraydata') as $value) {

                $menuitem = MenuItems::find($value['id']);

                $menuitem->parent = $value['parent'];

                $menuitem->sort = $value['sort'];

                $menuitem->depth = $value['depth'];

                // if (config('menu.use_roles')) {

                //     $menuitem->role_id = request()->input("role_id");

                // }

                $menuitem->save();

            }

        }

        // return json_encode(array("resp" => 1));

        return response()->json(['resp' => 1]);

    }

    /**
     * Store a newly created custom menu in storage.
     */

    // public function addcustommenu(Request $request)

    public function menuItemStore(Request $request, string $menu)
    {

        $menuitem = new MenuItems;

        $menuitem->label = $request->input('labelmenu');

        $menuitem->slug = $request->input('slug');

        $menuitem->link = $request->input('linkmenu');

        $menuitem->menu_id = $menu;

        $menuitem->sort = MenuItems::getNextSortRoot($menu);

        $menuitem->save();

    }

    /**
     * Update the specified menuitem in storage.
     */
    public function updateitem(Request $request)
    {

        $arraydata = $request->input('arraydata');

        if (is_array($arraydata)) {

            foreach ($arraydata as $value) {

                $menuitem = MenuItems::find($value['id']);

                $menuitem->label = $value['label'] ?? '';

                $menuitem->image = $value['image'];

                $menuitem->slug = $value['slug'] ?? '';

                $menuitem->link = $value['link'] ?? '';

                $menuitem->class = $value['class'] ?? '';

                // if (config('menu.use_roles')) {

                //     $menuitem->role_id = $value['role_id'] ? $value['role_id'] : 0;

                // }

                $menuitem->save();

            }

        } else {

            $menuitem = MenuItems::find($request->input('id'));

            $menuitem->label = $request->input('label');

            $menuitem->image = $request->input('image');

            $menuitem->slug = $request->input('slug');

            $menuitem->link = $request->input('url');

            $menuitem->class = $request->input('class');

            // if (config('menu.use_roles')) {

            //     $menuitem->role_id = request()->input("role_id") ? request()->input("role_id") : 0;

            // }

            $menuitem->save();

        }

    }

    /**
     * Remove the specified menuitem from storage.
     */
    public function destroyitemmenu(Request $request, int $id, $child_id)
    {

        MenuItems::destroy($child_id);

    }

    // admin/updateUrl

    public function updateUrl(Request $request)
    {

        // dd($request->getHost(), url('/'), request()->getScheme());

        $old_url = 'http://onehealth.foundation.test/'; // domain cũ

        $url = url('/').'/'; // Domain hiện tại

        // dd($old_url, $url);

        $menuitem = MenuItems::get();

        // dd($menuitem);

        foreach ($menuitem as $record) {

            $record->update([

                'link' => str_replace($old_url, $url, $record->link),

            ]);

        }

        return 'Update successful';

    }
}

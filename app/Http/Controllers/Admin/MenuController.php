<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Menu\GenerateMenu;
use App\Http\Requests\Admin\Menu\StoreMenu;
use App\Http\Requests\Admin\Menu\StoreMenuItem;
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

        $indmenu = $request->filled('menu') && (int) $request->menu > 0
            ? Menu::find((int) $request->menu)
            : null;

        $menus = $indmenu
            ? MenuItems::where('menu_id', $indmenu->id)->orderBy('sort', 'asc')->get()
            : collect();

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
    public function store(StoreMenu $request)
    {

        $menu = Menu::create(['name' => $request->validated('menuname')]);

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
        $menuId = (int) $id;

        if (is_array($arraydata)) {

            foreach ($arraydata as $value) {

                $menuitem = MenuItems::where('menu_id', $menuId)->find($value['id']);

                if (! $menuitem) {
                    continue;
                }

                $menuitem->label = $value['label'] ?? '';

                $menuitem->image = $value['image'] ?? null;

                $menuitem->slug = $value['slug'] ?? '';

                $menuitem->link = $value['link'] ?? '';

                $menuitem->class = $value['class'] ?? '';

                $menuitem->target = $value['target'] ?? null;

                $menuitem->rel = $value['rel'] ?? null;

                // if (config('menu.use_roles')) {

                //     $menuitem->role_id = $value['role_id'] ? $value['role_id'] : 0;

                // }

                $menuitem->save();
            }
        } else {

            $menuitem = MenuItems::where('menu_id', $menuId)->find($request->input('id'));

            if (! $menuitem) {
                return response()->json(['message' => 'Menu item not found'], 404);
            }

            $menuitem->label = $request->input('label');

            $menuitem->image = $request->input('image');

            $menuitem->slug = $request->input('slug');

            $menuitem->link = $request->input('url');

            $menuitem->class = $request->input('clases', $request->input('classes'));

            $menuitem->target = $request->input('target');

            $menuitem->rel = $request->input('rel');

            // if (config('menu.use_roles')) {

            //     $menuitem->role_id = request()->input("role_id") ? request()->input("role_id") : 0;

            // }

            $menuitem->save();
        }

        return response()->json(['success' => true]);
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

    public function generatemenucontrol(GenerateMenu $request)
    {
        $validated = $request->validated();
        $menu = Menu::find($validated['idmenu']);

        if (! $menu) {
            return response()->json(['message' => 'Menu not found'], 404);
        }

        $menu->name = $validated['menuname'];

        $menu->save();

        if (is_array($validated['arraydata'] ?? null)) {

            foreach ($validated['arraydata'] as $value) {

                $menuitem = MenuItems::where('menu_id', $menu->id)->find($value['id']);

                if (! $menuitem) {
                    continue;
                }

                $menuitem->parent = $value['parent'];

                $menuitem->sort = $value['sort'];

                $menuitem->depth = $value['depth'];

                $menuitem->save();
            }
        }

        return response()->json(['resp' => 1]);
    }

    /**
     * Store a newly created custom menu in storage.
     */

    // public function addcustommenu(Request $request)

    public function menuItemStore(StoreMenuItem $request, string $menu)
    {
        $menuId = (int) $menu;
        $validated = $request->validated();

        $menuitem = new MenuItems;

        $menuitem->label = $validated['labelmenu'];

        $menuitem->slug = $validated['slug'] ?? $validated['slugmenu'] ?? '';

        $menuitem->link = $validated['linkmenu'] ?? '';

        $menuitem->menu_id = $menuId;

        $menuitem->sort = MenuItems::getNextSortRoot($menuId);

        if (! empty($validated['targetmenu'])) {
            $menuitem->target = $validated['targetmenu'];
        }

        if (! empty($validated['relmenu'])) {
            $menuitem->rel = $validated['relmenu'];
        }

        $menuitem->save();

        return response()->json(['success' => true]);
    }

    /**
     * Update the specified menuitem in storage.
     */
    public function updateitem(Request $request, string $menu)
    {

        $arraydata = $request->input('arraydata');
        $menuId = (int) $menu;

        if (is_array($arraydata)) {

            foreach ($arraydata as $value) {

                $menuitem = MenuItems::where('menu_id', $menuId)->find($value['id']);

                if (! $menuitem) {
                    continue;
                }

                $menuitem->label = $value['label'] ?? '';

                $menuitem->image = $value['image'] ?? null;

                $menuitem->slug = $value['slug'] ?? '';

                $menuitem->link = $value['link'] ?? '';

                $menuitem->class = $value['class'] ?? '';

                $menuitem->target = $value['target'] ?? null;

                $menuitem->rel = $value['rel'] ?? null;

                // if (config('menu.use_roles')) {

                //     $menuitem->role_id = $value['role_id'] ? $value['role_id'] : 0;

                // }

                $menuitem->save();
            }
        } else {

            $menuitem = MenuItems::where('menu_id', $menuId)->find($request->input('id'));

            if (! $menuitem) {
                return response()->json(['message' => 'Menu item not found'], 404);
            }

            $menuitem->label = $request->input('label');

            $menuitem->image = $request->input('image');

            $menuitem->slug = $request->input('slug');

            $menuitem->link = $request->input('url');

            $menuitem->class = $request->input('class', $request->input('classes'));

            $menuitem->target = $request->input('target');

            $menuitem->rel = $request->input('rel');

            // if (config('menu.use_roles')) {

            //     $menuitem->role_id = request()->input("role_id") ? request()->input("role_id") : 0;

            // }

            $menuitem->save();
        }

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified menuitem from storage.
     */
    public function destroyitemmenu(Request $request, int $id, $child_id)
    {

        $deleted = MenuItems::where('menu_id', $id)->whereKey($child_id)->delete();

        if (! $deleted) {
            return response()->json(['message' => 'Menu item not found'], 404);
        }

        return response()->json(['success' => true]);
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

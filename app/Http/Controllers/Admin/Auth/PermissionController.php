<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Permission\StorePermission;
use App\Http\Requests\Admin\Permission\UpdatePermission;
use App\Models\Backend\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    public $data;

    public $template;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $routes = app()->routes->getRoutes();
        foreach ($routes as $route) {
            if (Str::startsWith($route->uri(), SC_ADMIN_PREFIX)) {
                $prefix = SC_ADMIN_PREFIX ? $route->getPrefix() : ltrim($route->getPrefix(), '/');
                $routeAdmin[$prefix] = [
                    'uri' => 'ANY::'.$prefix.'/*',
                    'name' => $prefix.'/*',
                    'method' => 'ANY',
                ];
                foreach ($route->methods as $key => $method) {
                    if ($method != 'HEAD' && ! collect($this->without())->first(function ($exp) use ($route) {
                        return Str::startsWith($route->uri, $exp);
                    })) {
                        $routeAdmin[] = [
                            'uri' => $method.'::'.$route->uri,
                            'name' => $route->uri,
                            'method' => $method,
                        ];
                    }
                }
            }
        }
        // dd($routeAdmin);
        $this->data['routeAdmin'] = $routeAdmin;
        $this->template = 'admin.permission';
        $this->data['title_head'] = 'Permissions';
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $permissions = Permission::filter($request)
            ->orderBy('id')
            ->paginate(20)
            ->appends($request->all());
        $total_item = $permissions->count();

        return view('backend.permission.index', compact('permissions', 'total_item'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.permission.single', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePermission $request)
    {
        $data = $request->except(['created_at', 'submit']);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $data['http_uri'] = implode(',', ($data['http_uri'] ?? []));

        // Create
        $permission = Permission::create($data);
        $insert_id = $permission->id;

        $save = $request->submit ?? 'apply';
        if ($save == 'apply') {
            $msg = 'Permission has been created successfully';
            $url = route('admin.permission.edit', [$insert_id]);
            msg_move_page($msg, $url);
        } else {
            return redirect(route('admin.permission.index'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission, int $id)
    {
        $permission = $permission::find($id);

        return view('backend.permission.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission, int $id)
    {
        $permission = $permission::findorfail($id);

        $this->data['permission'] = Permission::findorfail($id);

        if ($this->data['permission']) {
            return view('backend.permission.single', $this->data);
        } else {
            return view('404');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePermission $request, int $id)
    {
        $data = $request->all();

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $data['http_uri'] = implode(',', ($data['http_uri'] ?? []));

        $permission = Permission::findorfail($id);
        $permission->update($data);

        $save = $request->submit ?? 'apply';

        if ($save == 'apply') {
            $msg = 'Permission has been updated successfully';
            $url = route('admin.permission.edit', [$id]);
            msg_move_page($msg, $url);
        } else {
            return redirect(route('admin.permission.index'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission, int $id)
    {
        $permission->find($id)->destroy();

        return redirect()->route('admin.permission.index')->with('success', 'Permission deleted successfully.');
    }

    public function roleGroup()
    {
        dd('roleGroup');
    }

    public function without()
    {
        $prefix = SC_ADMIN_PREFIX ? SC_ADMIN_PREFIX.'/' : '';

        return [
            $prefix.'login',
            $prefix.'logout',
            $prefix.'forgot',
            $prefix.'deny',
            $prefix.'locale',
            $prefix.'uploads',
        ];
    }
}

<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Role\StoreRole;
use App\Http\Requests\Admin\Role\UpdateRole;
use App\Models\Backend\Permission;
use App\Models\Backend\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public $template;

    public $data;

    // public function __construct()
    // {
    //     parent::__construct();
    //     $this->data['title_head'] = 'Nhóm quyền';
    // }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $roles = Role::filter($request)
            ->orderBy('id')
            ->paginate(20)
            ->appends($request->all());
        $total_item = $roles->count();

        return view('backend.role.index', compact('roles', 'total_item'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->data['permission'] = Permission::pluck('name', 'id')->all();

        return view('backend.role.single', $this->data);
    }

    /**
     * Post create new item in admin
     *
     * @return [type] [description]
     */
    public function store(StoreRole $request)
    {
        $data = $request->except(['created_at', 'submit']);

        $dataInsert = [
            'name' => $data['name'],
            'slug' => $data['slug'],
        ];

        $role = Role::create($dataInsert);
        $permission = $data['permission'] ?? [];
        $administrators = $data['administrators'] ?? [];

        // Insert permission
        if ($permission) {
            $role->permissions()->attach($permission);
        }
        // Insert administrators
        if ($administrators) {
            $role->administrators()->attach($administrators);
        }

        return redirect()->route('admin.role.index')->with('success');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role, int $id)
    {
        $role = $role::find($id);

        return view('backend.role.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role, int $id)
    {
        $this->data['role'] = Role::find($id);

        if ($this->data['role']) {

            $this->data['permission_selected'] = $this->data['role']->permissions()->pluck('permissions.id')->toArray();
            $this->data['permission'] = Permission::pluck('name', 'id')->all();

            return view('backend.role.single', $this->data);
        } else {
            return view('404');
        }
    }

    /**
     * update status
     */
    public function update(UpdateRole $request, int $id)
    {
        $data = $request->all();

        // Edit
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $dataUpdate = [
            'name' => $data['name'],
            'slug' => $data['slug'],
        ];

        // Edit
        $role = Role::find($id);
        $role->update($dataUpdate);

        $permission = $data['permission'] ?? [];
        $administrators = $data['administrators'] ?? [];
        $role->permissions()->sync($permission);

        // Insert administrators
        $role->administrators()->sync($administrators);

        $save = $data['submit'] ?? 'apply';
        if ($save == 'apply') {
            $msg = 'Permission has been Updated';
            $url = route('admin.role.edit', [$id]);
            msg_move_page($msg, $url);
        } else {
            return redirect(route('admin.role.index'));
        }

        return redirect()->route('admin.role.index')->with('success', sc_language_render('action.edit_success'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role, int $id)
    {
        $role->find($id)->destroy();

        return redirect()->route('admin.role.index')->with('success', 'Role deleted successfully.');
    }

    /*
        Delete list Item
        Need mothod destroy to boot deleting in model
    */
    // public function delete($id)
    // {
    //     $loadDelete = Role::find($id)->delete();
    //     $msg = "Role has been Delete";
    //     $url = route('admin_role.index');
    //     Helpers::msg_move_page($msg, $url);
    // }
}

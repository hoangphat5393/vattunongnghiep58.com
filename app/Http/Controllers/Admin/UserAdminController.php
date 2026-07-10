<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserAdmin\StoreUserAdmin;
use App\Http\Requests\Admin\UserAdmin\UpdateUserAdmin;
use App\Models\Backend\Role as AdminRole;
use App\Models\Backend\User as Admin;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserAdminController extends Controller
{
    public $data;

    public $all_roles;

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

        $this->data['routeAdmin'] = $routeAdmin;
        $this->all_roles = AdminRole::pluck('name', 'id')->all();
    }

    public function index()
    {
        $appends = [
            'search_name' => request('search_name'),
        ];
        if (Auth::guard('admin')->user()->isAdministrator() || Auth::guard('admin')->user()->roles()->count() > 0) {
            $db = Admin::select('*');

            if (request('search_name') != '') {
                $db->where('name', 'like', '%'.request('search_name').'%');
            }
            $count_item = $db->count();
            $data = $db->orderBy('id')->paginate(20)->appends($appends);
        }

        return view('backend.user.index')->with(['users' => $data, 'total_item' => $count_item]);
    }

    public function create()
    {
        $this->data['all_roles'] = $this->all_roles;

        return view('backend.user.single', $this->data);
    }

    public function edit($id)
    {
        $user = Admin::find($id);

        $this->data = [
            'user' => $user,
            'all_roles' => $this->all_roles,
            'user_roles' => $user->roles->pluck('id')->toArray(),
        ];
        if ($user) {
            return view('backend.user.single', $this->data);
        } else {
            return view('404');
        }
    }

    public function store(StoreUserAdmin $request)
    {
        return $this->post($request);
    }

    public function update(UpdateUserAdmin $request, $id)
    {
        $request->merge(['id' => $id]);

        return $this->post($request);
    }

    public function post(Request $request)
    {
        $data = $request->only([
            'fullname',
            'name',
            'username',
            'birthday',
            'email',
            'phone',
            'address',
            'email_info',
            'status',
            'image',
            'province',
            'district',
            'ward',
        ]);

        // id post
        $sid = $request->id ?? 0;

        // $data = $request->all();

        // dd($data);
        $save = $request->submit ?? 'apply';

        if ($sid > 0) {
            $post_id = $sid;

            // NẾU CÓ THAY ĐỔI PASSWORD
            if (isset($request->check_pass)) {
                $data['password'] = bcrypt($request->password);
            }
            $respons = Admin::where('id', $sid)->update($data);
        } else {
            if ($request->password) {
                $data['password'] = bcrypt($request->password);
            }
            $respons = Admin::create($data);
            $insert_id = $respons->id;
            $post_id = $insert_id;

            // if sort = 0 => update sort
            // Admin::where("id", $post_id)->update(['sort' => $post_id]);

            // $db = ShopProduct::find(1);
            // $db->sort = $post_id;
            // $db->save();
        }

        // SAVE ROLE
        $role_id = $request->roles ?? '';
        // dd($role_id);
        if ($role_id != '') {
            $admin = Admin::find($post_id);
            $admin->roles()->sync($role_id);
        }

        if ($save == 'apply') {
            $msg = 'Post has been Updated';
            $url = route('admin.user.edit', [$post_id]);
            msg_move_page($msg, $url);
        } else {
            return redirect(route('admin.userList'));
        }
    }

    public function deleteUserAdmin($id)
    {
        $user_current = Auth::guard('admin')->user();
        if (Auth::guard('admin')->check() && $user_current->id != $id) {
            $loadDelete = Admin::find($id)->delete();
            $msg = 'Admin account has been Delete';
            $url = route('admin.userList');
            msg_move_page($msg, $url);
        }
        $msg = 'Không thực hiện được thao tác này';
        $url = route('admin.userList');
        msg_move_page($msg, $url);
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

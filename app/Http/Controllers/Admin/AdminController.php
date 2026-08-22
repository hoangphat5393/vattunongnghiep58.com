<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Theme\UpdateThemeCss;
use App\Models\Backend\Setting;
// use Illuminate\Support\Str;
// use Illuminate\Support\Facades\Hash;
use App\Models\Backend\Theme;
use App\Models\Backend\User;
use Auth;
use Cache;
use File;
use Hash;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Redirect;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {}

    public $data = [];

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function error()
    {
        return view('errors.404');
    }

    public function clearCache()
    {
        Artisan::call('optimize:clear');

        return redirect()->route('admin.dashboard')->with('success', 'Đã xóa cache hệ thống.');
    }

    public function changePassword()
    {
        return view('backend.change-password');
    }

    public function userDetail($id)
    {
        $user = User::find($id);

        return view('backend.users.detail', ['user' => $user]);
    }

    public function deleteUser($id)
    {
        // $loadDelete = User::find($id)->delete();

        // delete products
        // $productDelete = Theme::all();
        // if($loadDelete){
        //   foreach($productDelete as $value){
        //     if($value->admin_id==$id){
        //             $value->delete();
        //         }
        //     }
        // }

        $msg = 'Customer account has been Delete';
        $url = route('admin.listUsers');
        msg_move_page($msg, $url);
    }

    public function postChangePassword(Request $rq)
    {
        $user = Auth::guard('admin')->user();
        $id = $user->id;
        $current_pass = $user->password;

        if ($rq->check_pass_value == 'off') {

            // no change pass — cập nhật bảng users (admin guard dùng Backend\User -> users)
            $data = [
                'email' => $rq->email,
                'fullname' => $rq->name,
                'phone' => $rq->phone,
                'address' => $rq->address,
            ];
        } else {

            // change pass
            if (Hash::check($rq->current_password, $user->password)) {
                if ($rq->new_password == $rq->confirm_password) {
                    $data = [
                        'email' => $rq->email,
                        'fullname' => $rq->name,
                        'password' => bcrypt($rq->new_password),
                        'phone' => $rq->phone,
                        'address' => $rq->address,
                    ];
                } else {
                    $msg = 'Mật khẩu xác nhận không trùng khớp';

                    return Redirect::back()->withErrors($msg);
                }
            } else {
                $msg = 'Mật khẩu hiện tại không chính xác';

                return Redirect::back()->withErrors($msg);
            }
        }
        // Admin đăng nhập từ bảng users (Backend\User), không còn dùng bảng admins
        $respons = User::where('id', $id)->update($data);
        $msg = 'Thông tin cập nhật thành công!';
        $url = route('admin.changePassword');
        msg_move_page($msg, $url);
    }

    public function listUsers()
    {
        $data_user = User::get();

        return view('backend.users.index')->with(['data_user' => $data_user]);
    }

    public function getThemeOption()
    {
        return view('backend.setting.theme-option');
    }

    public function postThemeOption(Request $request)
    {
        $data = $request->all();
        // dd($data);
        $data_option = $data['header_option'];
        $i = 0;
        $list_option = [];
        // dd($data_option);
        if ($data_option) {
            foreach ($data_option as $key => $option) {
                $type = $key;
                foreach ($option['name'] as $index => $item) {
                    $content = htmlspecialchars($option['value'][$index]);
                    if ($type == 'editor') {
                        $content = htmlspecialchars($content);
                    }
                    $option_db = Setting::updateOrCreate(
                        [
                            'name' => $item,
                        ],
                        [
                            'content' => $content,
                            'type' => $type,
                            'sort' => $i,
                        ]
                    );
                    $list_option[] = $option_db->id;
                    $i++;
                }
            }
        }
        // delete;
        Setting::whereNotIn('id', $list_option)->delete();
        Cache::forget('theme_option');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => true,
                'message' => 'Cấu hình cài đặt đã được lưu thành công!',
            ]);
        }

        return redirect()->route('admin.theme-option')->with('success', 'Cấu hình cài đặt đã được lưu thành công!');
    }

    public function getCSS()
    {
        $this->authorizeThemeCssAccess();

        $cssPath = $this->themeCssPath();
        $scssContent = file_exists($cssPath) ? file_get_contents($cssPath) : '';

        return view('backend.setting.theme-css', compact('scssContent'));
    }

    public function updateCSS(UpdateThemeCss $request)
    {
        $cssPath = $this->themeCssPath();
        $directory = dirname($cssPath);

        if (! is_dir($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        file_put_contents($cssPath, $request->validated('css_content'));

        return redirect()->route('admin.css.get')->with('success', 'CSS file updated successfully.');
    }

    private function authorizeThemeCssAccess(): void
    {
        $user = Auth::guard('admin')->user();

        if (! $user || ! method_exists($user, 'isAdministrator') || ! $user->isAdministrator()) {
            abort(403, 'Unauthorized action.');
        }
    }

    private function themeCssPath(): string
    {
        return public_path('assets/css/user_custom.css');
    }

    public function ajaxUpdateSort(Request $request)
    {
        $sort = $request->input('sort');

        if ($sort) {
            // Xử lý logic cập nhật thứ tự các mục theo yêu cầu
            // Ví dụ: Lưu thứ tự mới vào cơ sở dữ liệu
            foreach ($sort as $index => $id) {
                // Cập nhật thứ tự cho từng mục, giả sử bạn có model Setting
                Setting::find($id)->update(['sort' => $index]);
            }

            return response()->json(['Update success' => true]);
        } else {
            return response('404 data Not Found');
        }
    }

    public function updateProfile(Request $request)
    {
        $id = Auth::guard('admin')->user()->id;
        $user = User::find($id);
        $user->email = $request->email;
        $user->fullname = $request->fullname;
        $user->phone = $request->phone;
        $user->address = $request->address;
        if ($user->save()) {
            $msg = 'Thông tin tài khoản đã được cập nhật';
            $url = route('admin.profile');
            msg_move_page($msg, $url);
        }
    }
}

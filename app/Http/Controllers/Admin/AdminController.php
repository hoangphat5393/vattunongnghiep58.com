<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
// use Illuminate\Support\Str;
// use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Models\Backend\Setting, App\Models\Backend\Addtocard;
use App\Models\Backend\Theme, App\Models\Backend\Rating_Product;
use App\Models\Backend\User;
use Auth, DB, File, Image, Redirect, Cache, Hash, Exception;
use App\Models\Backend\Menus, App\Models\Backend\MenuItems;


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
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function error()
    {
        return view('errors.404');
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
        //$loadDelete = User::find($id)->delete();

        //delete products
        // $productDelete = Theme::all();
        // if($loadDelete){
        //   foreach($productDelete as $value){
        //     if($value->admin_id==$id){
        //             $value->delete();
        //         }
        //     }
        // }

        $msg = "Customer account has been Delete";
        $url = route('admin.listUsers');
        msg_move_page($msg, $url);
    }

    public function postChangePassword(Request $rq)
    {
        $user = Auth::guard('admin')->user();
        $id = $user->id;
        $current_pass = $user->password;

        if ($rq->check_pass_value == 'off') {

            //no change pass — cập nhật bảng users (admin guard dùng Backend\User -> users)
            $data = array(
                'email' => $rq->email,
                'fullname' => $rq->name,
                'phone' => $rq->phone,
                'address' => $rq->address,
            );
        } else {

            //change pass
            if (Hash::check($rq->current_password, $user->password)) {
                if ($rq->new_password == $rq->confirm_password) {
                    $data = array(
                        'email' => $rq->email,
                        'fullname' => $rq->name,
                        'password' => bcrypt($rq->new_password),
                        'phone' => $rq->phone,
                        'address' => $rq->address,
                    );
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
        $msg = "Thông tin cập nhật thành công!";
        $url =  route('admin.changePassword');
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
                    if ($type == 'editor')
                        $content = htmlspecialchars($content);
                    $option_db = Setting::updateOrCreate(
                        [
                            'name'  => $item
                        ],
                        [
                            'content'   => $content,
                            'type'   => $type,
                            'sort'   => $i,
                        ]
                    );
                    $list_option[] = $option_db->id;
                    $i++;
                }
            }
        }
        //delete;
        Setting::whereNotIn('id', $list_option)->delete();
        Cache::forget('theme_option');
        $msg = "Option has been registered";
        $url = route('admin.theme-option');
        msg_move_page($msg, $url);
    }

    public function getCSS()
    {
        // $scssPath = resource_path('sass/user_custom.scss');
        $cssPath = public_path('assets/css/user_custom.css');

        // Đọc nội dung file custom.scss
        $scssContent = file_exists($cssPath) ? file_get_contents($cssPath) : '';

        return view('backend.setting.theme-css', compact('scssContent'));
    }

    public function updateCSS(Request $request)
    {

        // $scssPath = resource_path('sass/user_custom.scss');
        $cssPath = public_path('assets/css/user_custom.css');

        // Lưu nội dung mới vào file user_custom.scss
        file_put_contents($cssPath, $request->input('css_content'));

        // Chạy lệnh npm run prod
        // $process = new Process(['npm', 'run', 'prod']);
        // $process->setWorkingDirectory(base_path()); // Đảm bảo chạy trong thư mục gốc của dự án Laravel
        // $process->run();

        // // Kiểm tra nếu có lỗi khi chạy lệnh npm
        // if (!$process->isSuccessful()) {
        //     throw new ProcessFailedException($process);
        // }

        // Chuyển hướng lại trang edit với thông báo thành công
        return redirect()->route('admin.css.get')->with('success', 'SCSS file updated and compiled successfully!');
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
            $msg = "Thông tin tài khoản đã được cập nhật";
            $url =  route('admin.profile');
            msg_move_page($msg, $url);
        }
    }
}

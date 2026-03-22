<?php

namespace App\Http\Controllers;

use App\Models\Frontend\User, App\Models\Frontend\Customer;

use Auth, Cart, Validator, Redirect, Hash, Mail, DB, Input, File;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Models\Frontend\Category;
use App\Models\Frontend\Addtocard;
use App\Models\Frontend\Shipping_order;
use App\Models\Frontend\Customer_forget_pass_otp;
use App\Models\Frontend\ShopOrderStatus;
use App\Models\Frontend\ShopOrderPaymentStatus;
// use App\Libraries\Helpers;
use Carbon\Carbon;

class CustomerController extends Controller
{

    use \App\Traits\LocalizeController;

    public $currency;
    public $data = [
        'error' => false,
        'success' => false,
        'message' => ''
    ];

    public function __construct()
    {
        parent::__construct();
        // $this->data['statusOrder']    = ShopOrderStatus::getIdAll();
        // $this->data['orderPayment']    = ShopOrderPaymentStatus::getIdAll();

        // CART
        // $this->data['carts'] = Cart::content();
    }

    public function index()
    {
        return view('customer.home');
    }

    public function showLoginForm()
    {
        if (!Auth::check()) {
            $this->localized();
            $this->data['seo'] = [
                'seo_title' => 'Đăng nhập',
            ];
            // return view($this->templatePath . '.customer.login', $this->data);
            return view('theme.customer.login', $this->data)->compileShortcodes();
        }
        return redirect(url('/'));
    }

    public function postLogin(Request $request)
    {
        $data_return = ['status' => "success", 'message' => 'Thành công'];

        $login = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        if ($request->remember_me == 1)
            $remember_me = true;
        else
            $remember_me = false;

        $check_user = \App\User::where('email', $request->email)->first();
        if ($check_user != '' && $check_user->status == 0) {
            if (Auth::attempt($login, $remember_me)) {
                return response()->json([
                    'error' => 0,
                    'redirect_back' => $request->url_back ?? '/', //redirect()->back(),
                    'view' => view($this->templatePath . '.customer.includes.login_success')->render(),
                    'msg'   => __('Login success')
                ]);
            } else
                $message   = __('Email or Password is wrong');
        } else
            $message   = __('Account does not exist!');

        return response()->json([
            'error' => 1,
            'msg'   => $message
        ]);
    }

    public function loginOrregister()
    {
        session()->forget('cart-info');
        $data = request()->all();

        $validation_rules = array(
            'email' => 'required|email|max:255|unique:users',
            'phone' => 'required|unique:users',
        );
        $messages = array(
            'email.required' => 'Please enter your email',
            'email.email' => 'Email address is not in the correct format',
            'email.max' => 'Email address up to 255 characters',
            'email.unique' => 'Email address already exists',
            'phone.required' => 'Please enter your phone',
            'phone.unique' => 'Phone already exists',
        );

        $validator = Validator::make($data, $validation_rules, $messages);

        if ($validator->fails()) {
            $error = $validator->errors()->first();

            return response()->json([
                'error' => 1,
                'msg'   => $error
            ]);
        }

        $fullname = explode('@', $data['email'])[0];

        if (!empty($data['register_auto'])) {
            $validation_rules = array(
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required',
                'password_confirmation' => 'required|same:password',
            );
            $messages = array(
                'email.required' => 'Please enter your email',
                'email.email' => 'Email address is not in the correct format',
                'email.max' => 'Email address up to 255 characters',
                'email.unique' => 'Email address already exists',
                'password.required' => 'Please enter password',
                'password_confirmation.same' => 'Password and re-enter password do not match!',
            );

            $validator = Validator::make($data, $validation_rules, $messages);

            if ($validator->fails()) {
                $error = $validator->errors()->first();

                return response()->json([
                    'error' => 1,
                    'msg'   => $error
                ]);
            }

            $new_cus = (new User)->create([
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'fullname' => $fullname,
            ]);

            $email_admin = 'huunamtn@gmail.com'; //setting_option('email_admin');

            $data_email = [
                'content' => '<h1 style="font-size:22px;font-weight:normal;line-height:22px;margin:0 0 11px 0">Thân gửi, <span style="color: #F04F32">' . $new_cus->fullname . '</span></h1>
                    <p style="font-size:12px;line-height:16px;margin:0 0 8px 0">Cảm ơn bạn đã đăng ký thành viên tại ' . url('/') . '</p>
                    <p>Mật khẩu đăng nhập: ' . $data['password'] . '</p>',
                'email_admin'   => $email_admin,
                'subject' => "Đăng ký tài khoản thành công",
                'subject_sys' => "Thông báo có tài khoản vừa đăng ký",
                'title' => setting_option('company_name'),
                'email_admin' => $email_admin,
                'url_only' => request()->getHttpHost(),
                'fullname' => $new_cus->fullname,
                'phone' => $new_cus->phone,
                'email' => $new_cus->email,
            ];

            Mail::send(
                'email.content',
                $data_email,
                function ($message) use ($data_email) {
                    $message->from($data_email['email_admin'], $data_email['title']);
                    $message->to($data_email['email_admin'])
                        ->subject($data_email['subject_sys'] . " - Website: " . $data_email['url_only']);
                }
            );
            Mail::send(
                'mail.user_register_system',
                $data_email,
                function ($message) use ($data_email) {
                    $message->from($data_email['email_admin'], $data_email['title']);
                    $message->to($data_email['email_admin'])
                        ->subject($data_email['subject_sys'] . " - Website: " . $data_email['url_only']);
                }
            );

            Auth::login($new_cus);
            return response()->json([
                'error' => 2,
                'status' => 'register_success',
                'redirect_back' => $data['url_back'],
                'msg'   => 'Sent password to email'
            ]);
        } elseif (!empty($data['send_password'])) {
            $password_auto = rand(1000, 10000);

            $new_cus = (new User)->create([
                'email' => $data['email'],
                'password' => bcrypt($password_auto),
                'fullname' => $fullname,
            ]);

            $email_admin = setting_option('email_admin');

            $data_email = [
                'content' => '<h1 style="font-size:22px;font-weight:normal;line-height:22px;margin:0 0 11px 0">Thân gửi, <span style="color: #F04F32">' . $new_cus->fullname . '</span></h1>
                    <p style="font-size:12px;line-height:16px;margin:0 0 8px 0">Cảm ơn bạn đã đăng ký thành viên tại ' . url('/') . '</p>
                    <p>Mật khẩu đăng nhập: ' . $password_auto . '</p>',
                'email_admin'   => $email_admin,
                'subject' => "Đăng ký tài khoản thành công",
                'subject_sys' => "Thông báo có tài khoản vừa đăng ký",
                'title' => setting_option('company_name'),
                'email_admin' => $email_admin,
                'url_only' => request()->getHttpHost(),
                'fullname' => $new_cus->fullname,
                'phone' => $new_cus->phone,
                'email' => $new_cus->email,
            ];

            Mail::send(
                'email.content',
                $data_email,
                function ($message) use ($data_email) {
                    $message->from($data_email['email_admin'], $data_email['title']);
                    $message->to($data_email['email_admin'])
                        ->subject($data_email['subject_sys'] . " - Website: " . $data_email['url_only']);
                }
            );
            Mail::send(
                'mail.user_register_system',
                $data_email,
                function ($message) use ($data_email) {
                    $message->from($data_email['email_admin'], $data_email['title']);
                    $message->to($data_email['email_admin'])
                        ->subject($data_email['subject_sys'] . " - Website: " . $data_email['url_only']);
                }
            );

            return response()->json([
                'error' => 2,
                'msg'   => 'Sent password to email'
            ]);
        } else {
            $cart_info['fullname']  = $fullname;
            $cart_info['phone']  = $data['phone'];
            $cart_info['email']  = $data['email'];
            $cart_info['address_line1']  = '';

            session()->put('cart-info', $cart_info);
        }
        // dd($data);
        return response()->json([
            'error' => 0,
            'msg'   => 'Success'
        ]);
    }

    public function registerCustomer()
    {
        $this->data['seo'] = [
            'seo_title' => 'Đăng ký thành viên',
        ];
        return view('theme.customer.register',  $this->data)->compileShortcodes();
    }

    public function createCustomer(Request $request)
    {
        $data_return = ['status' => "success", 'message' => 'Success'];
        $validation_rules = array(
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required',
            'password_confirm' => 'required|same:password',
        );
        $messages = array(
            'email.required' => 'Please enter your email',
            'email.email' => 'Email address is not in the correct format',
            'email.max' => 'Email address up to 255 characters',
            'email.unique' => 'Email address already exists',
            'password.required' => 'Please enter password',
            'password_confirm.same' => 'Password and re-enter password do not match!',
        );
        $data = $request->all();

        $validator = Validator::make($data, $validation_rules, $messages);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            // dd($validator->errors());

            return response()->json([
                'error' => 1,
                'msg'   => $error
            ]);
            // $view = view('customer.includes.modal_register')->render();
        }
        $dataUpdate = [
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'fullname' => $data['fullname'] ?? $data['email'],
        ];
        $new_cus = (new User)->create($dataUpdate);
        // dd($new_cus);

        if ($new_cus->id) {
            $email_admin = setting_option('email');
            $name_admin_email = setting_option('name-admin');
            $url_web = url('/');
            $url_only = request()->getHttpHost();
            //email send to user
            $data = array(
                'fullname' => $new_cus->fullname,
                'phone' => $new_cus->phone,
                'email' => $new_cus->email,
                'subject' => "Đăng ký tài khoản thành công",
                'subject_sys' => "Thông báo có tài khoản vừa đăng ký",
                'title' => setting_option('company_name'),
                'email_admin' => $email_admin,
                'url_only' => $url_only,
            );

            Mail::send(
                'mail.thongbao_user_register',
                $data,
                function ($message) use ($data) {
                    $message->from($data['email'], $data['title']);
                    $message->to($data['email'])->subject($data['subject']);
                }
            );

            //thong bao co thanh vien dang ky
            Mail::send(
                'mail.user_register_system',
                $data,
                function ($message) use ($data) {
                    $message->from($data['email_admin'], $data['title']);
                    $message->to($data['email_admin'])
                        ->subject($data['subject_sys'] . " - Website: " . $data['url_only']);
                }
            );

            Auth::login($new_cus);

            return response()->json([
                'error' => 0,
                'view' => view($this->templatePath . '.customer.includes.register_success')->render(),
                'msg'   => __('Register success')
            ]);

            // return redirect(route('user.register.success'));

            // Auth::login($new_cus);
            // return redirect()->route('index');
        }
    }

    public function createCustomerSuccess()
    {
        return view($this->templatePath . '.customer.includes.register_success')->compileShortcodes();
    }

    public function profile()
    {
        $this->data['user'] = Auth::user();

        $this->data['seo'] = [
            'seo_title' => 'Customer | Profile',
            'seo_image' => '',
            'seo_description'   => 'Customer Update Profile',
            'seo_keyword'   => 'Update, Profile',
        ];
        return view($this->templatePath . '.customer.profile', $this->data);
    }
    public function updateProfile(Request $rq)
    {
        $id = Auth::user()->id;
        $name_field = "avatar_upload";
        if ($rq->avatar_upload) {
            $image_folder = "/images/avatar/";

            $file = $rq->file($name_field);
            $file_name = uniqid() . '-' . $file->getClientOriginalName();
            $name_avatar = $image_folder . $file_name;


            $file->move(base_path() . $image_folder, $file_name);
            if (Auth::user()->avatar != '' && file_exists(base_path() . Auth::user()->avatar)) {
                if (file_exists(asset(base_path() . Auth::user()->avatar)))
                    unlink(asset(base_path() . Auth::user()->avatar));
            }
        } else
            $name_avatar = Auth::user()->avatar;

        $data = array(
            'fullname' => $rq->fullname ?? '',
            'firstname' => $rq->firstname ?? '',
            'lastname' => $rq->lastname ?? '',
            'address' => $rq->address ?? '',
            'birthday' => $rq->birthday ?? null,
            'country' => $rq->country ?? '',
            'province' => $rq->state ?? '',
            'district' => $rq->slt_district ?? '',
            'city' => $rq->city ?? '',
            'postal_code' => $rq->postal_code ?? 0,
            'avatar' => $name_avatar,
            'phone' => $rq->phone,
            'full_phone' => $rq->full_phone,
        );
        $respons = (new \App\User)->find($id)->update($data);
        $msg = "Thông tin tài khoản đã được cập nhật";
        $url =  route('customer.profile');
        msg_move_page($msg, $url);
    }

    public function myPost()
    {
        $this->localized();
        $this->data['products'] = \App\Product::where('user_id', auth()->user()->id)->orderbyDesc('id')->paginate(10);

        return view('theme.customer.my-post', ['data' => $this->data]);
    }

    public function deletePost($id)
    {
        $db = \App\Product::where('id', $id)->where('user_id', auth()->user()->id)->first();
        if ($db->delete()) {
            \App\Models\ThemeInfo::where('theme_id', $id)->delete();
            \App\Models\Join_Category_Theme::where('theme_id', $id)->delete();
            return redirect()->back();
        }
    }

    public function changePassword()
    {
        $this->data['user'] = Auth::user();
        $this->data['seo'] = [
            'seo_title' => 'Customer | Change Password',
        ];
        $this->data['seo'] = [
            'seo_title' => 'Customer | Change Password',
            'seo_image' => '',
            'seo_description'   => 'Customer Change Password',
            'seo_keyword'   => 'Customer, Password',
        ];
        return view('theme.customer.auth.change_pass')->with(['data' => $this->data]);
    }
    public function postChangePassword(Request $rq)
    {
        $user = Auth::user();
        $id = $user->id;
        $current_pass = $user->password;
        if (Hash::check($rq->current_password, $user->password)) {
            if ($rq->new_password != '' && $rq->new_password == $rq->confirm_password) {
                $data = array(
                    'password' => bcrypt($rq->new_password)
                );
            } else {
                $msg = 'Mật khẩu xác nhận không trùng khớp';
                return Redirect::back()->withErrors($msg);
            }
        } else {
            $msg = 'Mật khẩu hiện tại không chính xác';
            return Redirect::back()->withErrors($msg);
        }
        $respons = DB::table('users')->where("id", "=", $id)->update($data);
        $msg = "Mật khẩu đã được thay đổi";
        $url =  route('customer.profile');
        msg_move_page($msg, $url);
    }

    public function checkWallet(Request $request)
    {
        $this->data['status'] = 'success';
        $price_post = $request->price_post;
        $wallet = auth()->user()->wallet;
        $wallet_check = 'ok';
        if ($wallet < $price_post) {
            $wallet_check = 'error';
            $this->data['status'] = 'error';
        }
        $this->data['view'] = view('theme.dangtin.includes.wallet_check', compact('wallet_check'))->render();
        return response()->json($this->data);
    }

    public function wishlist()
    {
        if (auth()->check()) {
            $this->data['wishlist'] = \App\Models\Wishlist::with('product')->where('user_id', auth()->user()->id)->get();
            return view('theme.customer.wishlist', ['data' => $this->data]);
        } else {
            $wishlist = json_decode(\Cookie::get('wishlist'));

            if ($wishlist != '') {
                $this->data['wishlist'] = \App\Product::whereIn('id', $wishlist)->get();
                // dd($this->data['wishlist']);
            }
            return view($this->templatePath . '.customer.wishlist', ['data' => $this->data]);
        }
    }

    public function subscription(Request $request)
    {
        $email = $request->email;

        $validation_rules = array(
            'email' => 'required|email|max:255|unique:subscription',
        );
        // $messages = array(
        //     'email.required' => 'Please enter your email',
        //     'email.email' => 'Email address is not in the correct format',
        //     'email.max' => 'Email address up to 255 characters',
        //     'email.unique' => 'Email address already exists',
        // );
        $data = $request->all();

        // $validator = Validator::make($data, $validation_rules, $messages);
        $validator = Validator::make($data, $validation_rules);

        $this->data['email'] = $data['email'];

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            $this->data['status'] = 'error';
            $this->data['message'] = $error;
        } else {
            \App\Models\Subscription::updateOrCreate(['email' => $email]);
            $this->data['status'] = 'success';
            $this->data['message'] = 'Đăng ký thành công';
        }
        return response()->json($this->data);

        // $this->data['view'] = view($this->templatePath . '.customer.includes.subscription')->render();
        // if ($saved->wasRecentlyCreated) {
        //     $this->data['status'] = 'success';
        // }
    }

    //xử lý quên mật khẩu
    public function forgetPassword()
    {
        // $this->data['seo'] = [
        //     'seo_title' => '',
        //     'seo_image' => '',
        //     'seo_description'   => '',
        //     'seo_keyword'   => '',
        // ];
        return view($this->templatePath . '.customer.auth.forget-password', $this->data);
    }
    public function actionForgetPassword(Request $rq)
    {
        $user = User::where('email', $rq->email)->first();
        if ($user) {
            session_start();
            $customer_forget_pass_otp = new Customer_forget_pass_otp();
            $customer_forget_pass_otp->email = $rq->email;
            $customer_forget_pass_otp->user_id = $user->id;
            $customer_forget_pass_otp->otp_mail = rand(100000, 999999);
            $customer_forget_pass_otp->status = 0;
            $customer_forget_pass_otp->save();
            $_SESSION["otp_forget"] = $customer_forget_pass_otp->otp_mail;
            $_SESSION["email_forget"] = $customer_forget_pass_otp->email;

            $site_name = setting_option('site-name');
            $data = array(
                'email' => $customer_forget_pass_otp->email,
                'emailadmin'   => $email_admin = setting_option('email'),
                'otp' => $customer_forget_pass_otp->otp_mail,
                'name' => $user->first_name,
                'created_at' => $customer_forget_pass_otp->created_at,
                'site_name' => $site_name,
            );
            Mail::send(
                $this->templatePath . '.mail.forget-password.forget-password',
                $data,
                function ($message) use ($data) {
                    $message->from($data['emailadmin'], $data['site_name']);
                    $message->to($data['email'])
                        ->subject($data['otp'] . ' là mã OTP của ' . $data['site_name']);
                }
            );
            return redirect()->route('forgetPassword_step2');
        } else {
            redirect()->back()->withErrors('Email not exist.');
        }
    }

    public function forgetPassword_step2()
    {
        session_start();
        if ((!isset($_SESSION["otp_forget"]) && !isset($_SESSION["email_forget"])) || $_SESSION["otp_forget"] == '' || $_SESSION["email_forget"] == '') {
            session_unset();
            session_destroy();
            return redirect()->route('forgetPassword');
        } else {
            return view('theme.customer.auth.forget-password-step-2', $this->data);
        }
    }

    public function actionForgetPassword_step2(Request $rq)
    {
        session_start();
        $customer_forget_pass_otp = Customer_forget_pass_otp::where('otp_mail', '=', $rq->otp_mail)
            ->where('otp_mail', '=', $_SESSION["otp_forget"])
            ->where('status', '=', 0)
            ->whereRaw("TIME_TO_SEC('" . Carbon::now() . "') - TIME_TO_SEC(created_at) < 300 ")
            ->first();
        if ($customer_forget_pass_otp) {
            $_SESSION["otp_true"] = 1;
            return redirect()->route('forgetPassword_step3');
        } else {
            return redirect()->back()->withErrors('OTP is not correct.');
        }
    }

    public function forgetPassword_step3()
    {
        session_start();
        if ((!isset($_SESSION["otp_forget"]) && !isset($_SESSION["email_forget"]) && !isset($_SESSION["otp_true"])) || $_SESSION["otp_forget"] == '' || $_SESSION["email_forget"] == '') {
            session_unset();
            session_destroy();
            return redirect()->route('forgetPassword');
        } else {
            return view('theme.customer.auth.forget-password-step-3', $this->data);
        }
    }

    public function actionForgetPassword_step3(Request $rq)
    {
        session_start();
        $customer_forget_pass_otp = Customer_forget_pass_otp::where('email', '=', $_SESSION["email_forget"])
            ->where('otp_mail', '=', $_SESSION["otp_forget"])
            ->where('status', '=', 0)
            ->first();
        if ($customer_forget_pass_otp) {
            $validator = Validator::make($rq->all(), [
                'new_password'     => 'required|min:6|required_with:confirm_new_password|same:confirm_new_password',
                'confirm_new_password'     => 'required|min:6',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }
            $customer = User::where('email', '=', $_SESSION["email_forget"])->first();
            $customer->password = bcrypt($rq->new_password);
            $customer->save();

            $customer_forget_pass_otp->status = 1;
            $customer_forget_pass_otp->save();

            session_unset();
            session_destroy();
            $msg = "Password changed successfully";
            // $url =  route('user.login');
            $url =  route('index');
            if ($msg) echo "<script language='javascript'>alert('" . $msg . "');</script>";
            echo "<script language='javascript'>document.location.replace('" . $url . "');</script>";
        } else {
            session_unset();
            session_destroy();
            return redirect()->route('forgetPassword');
        }
    }

    public function myOrder()
    {
        $this->data['user'] = Auth::user();
        $this->data['orders'] = \App\Models\Addtocard::where('user_id', Auth::user()->id)->orderByDesc('cart_id')->paginate(10);

        $this->data['seo'] = [
            'seo_title' => 'Customer |  My Order',
            'seo_image' => '',
            'seo_description'   => 'Customer My Order',
            'seo_keyword'   => 'Order',
        ];

        return view($this->templatePath . '.customer.myorder', $this->data);
    }

    public function myOrderDetail($id_cart)
    {
        $this->data['shop_payment_method'] = \App\Models\ShopPaymentMethod::where('status', 1)->get()->pluck('name', 'code')->toArray();
        $this->data['order'] = \App\Models\Addtocard::find($id_cart);
        $this->data['order_detail'] = \App\Models\Addtocard_Detail::where('cart_id', $this->data['order']->cart_id)->get();

        $total_price = isset($order_detail->total) ? $order_detail->total : 0;

        if ($this->data['order']) {
            $this->data['seo'] = [
                'seo_title' => 'Đơn hàng - ' . $this->data['order']->cart_code,
            ];
            return view($this->templatePath . '.customer.orderdetail', ['data' => $this->data]);
        } else
            return view('errors.404');
    }

    public function orderView()
    {
        $id = request()->id;
        $order = \App\Models\Addtocard::find($id);
        if ($order) {
            $view = view($this->templatePath . '.customer.order-view', compact('order'))->render();
            return response()->json([
                'error' => 1,
                'view' => $view,
            ]);
        } else
            return response()->json([
                'error' => 0,
                'message'   => 'Không tìm thấy đơn hàng!'
            ]);
    }

    public function myPoint()
    {
        $user = request()->user();
        $user_point = $user->getVIP();
        // dd($user_point);

        $this->data = [
            'user'  => $user,
            'user_point'  => $user_point,
            'seo'   => [
                'seo_title' => 'Thông tin tài khoản',
            ]
        ];


        return view($this->templatePath . '.customer.my-point', $this->data);
    }

    public function logoutCustomer()
    {
        Auth::logout();
        return redirect()->route('index');
    }

    public function messages()
    {
        $this->data['user'] = auth()->user();
        $this->data['seo'] = [
            'seo_title' => 'Messages'
        ];
        return view($this->templatePath . '.customer.messages', $this->data);
    }
}

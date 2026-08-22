<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Requests\UpdateCustomerPasswordRequest;
use App\Http\Requests\UpdateCustomerProfileRequest;
use App\Models\Addtocard;
use App\Models\Customer_forget_pass_otp;
use App\Models\Frontend\Contact;
use App\Models\Frontend\Product;
use App\Models\Frontend\ShopOrderPaymentStatus;
use App\Models\Frontend\ShopOrderStatus;
use App\Models\Frontend\User;
use App\Models\Join_Category_Theme;
use App\Models\ThemeInfo;
// use App\Libraries\Helpers;
use App\Traits\LocalizeController;
use Auth;
use Carbon\Carbon;
use Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Mail;
use Redirect;
use Validator;

class CustomerController extends Controller
{
    use LocalizeController;

    public $currency;

    public $data = [
        'error' => false,
        'success' => false,
        'message' => '',
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
        return app(AccountController::class)->index();
    }

    public function showLoginForm()
    {
        return app(CustomerAuthController::class)->showLoginForm();
    }

    public function postLogin(Request $request)
    {
        return app(CustomerAuthController::class)->postLogin($request);
    }

    public function loginOrregister()
    {
        session()->forget('cart-info');
        $data = request()->all();

        $validation_rules = [
            'email' => 'required|email|max:255|unique:users',
            'phone' => 'required|unique:users',
        ];
        $messages = [
            'email.required' => 'Please enter your email',
            'email.email' => 'Email address is not in the correct format',
            'email.max' => 'Email address up to 255 characters',
            'email.unique' => 'Email address already exists',
            'phone.required' => 'Please enter your phone',
            'phone.unique' => 'Phone already exists',
        ];

        $validator = Validator::make($data, $validation_rules, $messages);

        if ($validator->fails()) {
            $error = $validator->errors()->first();

            return response()->json([
                'error' => 1,
                'msg' => $error,
            ]);
        }

        $fullname = explode('@', $data['email'])[0];

        if (! empty($data['register_auto'])) {
            $validation_rules = [
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required',
                'password_confirmation' => 'required|same:password',
            ];
            $messages = [
                'email.required' => 'Please enter your email',
                'email.email' => 'Email address is not in the correct format',
                'email.max' => 'Email address up to 255 characters',
                'email.unique' => 'Email address already exists',
                'password.required' => 'Please enter password',
                'password_confirmation.same' => 'Password and re-enter password do not match!',
            ];

            $validator = Validator::make($data, $validation_rules, $messages);

            if ($validator->fails()) {
                $error = $validator->errors()->first();

                return response()->json([
                    'error' => 1,
                    'msg' => $error,
                ]);
            }

            $new_cus = (new User)->create([
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'fullname' => $fullname,
            ]);

            $email_admin = 'huunamtn@gmail.com'; // setting_option('email_admin');

            $data_email = [
                'content' => '<h1 style="font-size:22px;font-weight:normal;line-height:22px;margin:0 0 11px 0">Thân gửi, <span style="color: #F04F32">'.$new_cus->fullname.'</span></h1>
                    <p style="font-size:12px;line-height:16px;margin:0 0 8px 0">Cảm ơn bạn đã đăng ký thành viên tại '.url('/').'</p>
                    <p>Mật khẩu đăng nhập: '.$data['password'].'</p>',
                'email_admin' => $email_admin,
                'subject' => 'Đăng ký tài khoản thành công',
                'subject_sys' => 'Thông báo có tài khoản vừa đăng ký',
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
                        ->subject($data_email['subject_sys'].' - Website: '.$data_email['url_only']);
                }
            );
            Mail::send(
                'mail.user_register_system',
                $data_email,
                function ($message) use ($data_email) {
                    $message->from($data_email['email_admin'], $data_email['title']);
                    $message->to($data_email['email_admin'])
                        ->subject($data_email['subject_sys'].' - Website: '.$data_email['url_only']);
                }
            );

            Auth::login($new_cus);

            return response()->json([
                'error' => 2,
                'status' => 'register_success',
                'redirect_back' => $data['url_back'],
                'msg' => 'Sent password to email',
            ]);
        } elseif (! empty($data['send_password'])) {
            $password_auto = rand(1000, 10000);

            $new_cus = (new User)->create([
                'email' => $data['email'],
                'password' => bcrypt($password_auto),
                'fullname' => $fullname,
            ]);

            $email_admin = setting_option('email_admin');

            $data_email = [
                'content' => '<h1 style="font-size:22px;font-weight:normal;line-height:22px;margin:0 0 11px 0">Thân gửi, <span style="color: #F04F32">'.$new_cus->fullname.'</span></h1>
                    <p style="font-size:12px;line-height:16px;margin:0 0 8px 0">Cảm ơn bạn đã đăng ký thành viên tại '.url('/').'</p>
                    <p>Mật khẩu đăng nhập: '.$password_auto.'</p>',
                'email_admin' => $email_admin,
                'subject' => 'Đăng ký tài khoản thành công',
                'subject_sys' => 'Thông báo có tài khoản vừa đăng ký',
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
                        ->subject($data_email['subject_sys'].' - Website: '.$data_email['url_only']);
                }
            );
            Mail::send(
                'mail.user_register_system',
                $data_email,
                function ($message) use ($data_email) {
                    $message->from($data_email['email_admin'], $data_email['title']);
                    $message->to($data_email['email_admin'])
                        ->subject($data_email['subject_sys'].' - Website: '.$data_email['url_only']);
                }
            );

            return response()->json([
                'error' => 2,
                'msg' => 'Sent password to email',
            ]);
        } else {
            $cart_info['fullname'] = $fullname;
            $cart_info['phone'] = $data['phone'];
            $cart_info['email'] = $data['email'];
            $cart_info['address_line1'] = '';

            session()->put('cart-info', $cart_info);
        }

        // dd($data);
        return response()->json([
            'error' => 0,
            'msg' => 'Success',
        ]);
    }

    public function registerCustomer()
    {
        return app(CustomerAuthController::class)->registerCustomer();
    }

    public function createCustomer(Request $request)
    {
        $data_return = ['status' => 'success', 'message' => 'Success'];
        $validation_rules = [
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required',
            'password_confirm' => 'required|same:password',
        ];
        $messages = [
            'email.required' => 'Please enter your email',
            'email.email' => 'Email address is not in the correct format',
            'email.max' => 'Email address up to 255 characters',
            'email.unique' => 'Email address already exists',
            'password.required' => 'Please enter password',
            'password_confirm.same' => 'Password and re-enter password do not match!',
        ];
        $data = $request->all();

        $validator = Validator::make($data, $validation_rules, $messages);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            // dd($validator->errors());

            return response()->json([
                'error' => 1,
                'msg' => $error,
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
            // email send to user
            $data = [
                'fullname' => $new_cus->fullname,
                'phone' => $new_cus->phone,
                'email' => $new_cus->email,
                'subject' => 'Đăng ký tài khoản thành công',
                'subject_sys' => 'Thông báo có tài khoản vừa đăng ký',
                'title' => setting_option('company_name'),
                'email_admin' => $email_admin,
                'url_only' => $url_only,
            ];

            Mail::send(
                'mail.thongbao_user_register',
                $data,
                function ($message) use ($data) {
                    $message->from($data['email'], $data['title']);
                    $message->to($data['email'])->subject($data['subject']);
                }
            );

            // thong bao co thanh vien dang ky
            Mail::send(
                'mail.user_register_system',
                $data,
                function ($message) use ($data) {
                    $message->from($data['email_admin'], $data['title']);
                    $message->to($data['email_admin'])
                        ->subject($data['subject_sys'].' - Website: '.$data['url_only']);
                }
            );

            Auth::login($new_cus);

            return response()->json([
                'error' => 0,
                'view' => view($this->templatePath.'.account.includes.register_success')->render(),
                'msg' => __('Register success'),
            ]);

            // return redirect(route('user.register.success'));

            // Auth::login($new_cus);
            // return redirect()->route('index');
        }
    }

    public function createCustomerSuccess()
    {
        return app(CustomerAuthController::class)->createCustomerSuccess();
    }

    public function profile()
    {
        return app(AccountController::class)->profile();
    }

    public function updateProfile(UpdateCustomerProfileRequest $request)
    {
        return app(AccountController::class)->updateProfile($request);
    }

    public function myPost()
    {
        return redirect()->route('customer.dashboard');
    }

    public function deletePost($id)
    {
        $db = Product::where('id', $id)->where('user_id', auth()->user()->id)->first();
        if ($db && $db->delete()) {
            if (Schema::hasTable('theme_info')) {
                ThemeInfo::where('theme_id', $id)->delete();
            }
            if (Schema::hasTable('join_category_theme')) {
                Join_Category_Theme::where('theme_id', $id)->delete();
            }

            return redirect()->back();
        }
    }

    public function changePassword()
    {
        return app(AccountController::class)->changePassword();
    }

    public function postChangePassword(UpdateCustomerPasswordRequest $request)
    {
        return app(AccountController::class)->postChangePassword($request);
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
        $walletView = $this->templatePath.'.dangtin.includes.wallet_check';
        $this->data['view'] = view()->exists($walletView)
            ? view($walletView, compact('wallet_check'))->render()
            : '';

        return response()->json($this->data);
    }

    public function wishlist()
    {
        $this->data['wishlist'] = collect([]);

        if (auth()->check()) {
            // Legacy wishlist table removed — empty state until feature is reintroduced.
        } else {
            $wishlist = json_decode(\Cookie::get('wishlist'));

            if ($wishlist != '') {
                $this->data['wishlist'] = Product::whereIn('id', (array) $wishlist)->get();
            }
        }

        return view($this->templatePath.'.customer.wishlist', ['data' => $this->data]);
    }

    public function subscription(Request $request)
    {
        $email = $request->email;

        $validation_rules = [
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('contacts', 'email')->where(fn ($query) => $query->where('type', 'subscription')),
            ],
        ];
        $data = $request->all();

        $validator = Validator::make($data, $validation_rules);

        $this->data['email'] = $data['email'];

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            $this->data['status'] = 'error';
            $this->data['message'] = $error;
        } else {
            Contact::updateOrCreate(
                ['email' => $email, 'type' => 'subscription'],
                ['name' => $email, 'status' => 1, 'content' => 'Newsletter subscription']
            );
            $this->data['status'] = 'success';
            $this->data['message'] = 'Đăng ký thành công';
        }

        return response()->json($this->data);
    }

    // xử lý quên mật khẩu
    public function forgetPassword()
    {
        // $this->data['seo'] = [
        //     'seo_title' => '',
        //     'seo_image' => '',
        //     'seo_description'   => '',
        //     'seo_keyword'   => '',
        // ];
        return view($this->templatePath.'.account.auth.forget-password', $this->data);
    }

    public function actionForgetPassword(Request $rq)
    {
        $rq->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $rq->email)->first();
        if ($user) {
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }
            $customer_forget_pass_otp = new Customer_forget_pass_otp;
            $customer_forget_pass_otp->email = $rq->email;
            $customer_forget_pass_otp->user_id = $user->id;
            $customer_forget_pass_otp->otp_mail = rand(100000, 999999);
            $customer_forget_pass_otp->status = 0;
            $customer_forget_pass_otp->save();
            $_SESSION['otp_forget'] = $customer_forget_pass_otp->otp_mail;
            $_SESSION['email_forget'] = $customer_forget_pass_otp->email;

            $site_name = setting_option('site-name');
            $data = [
                'email' => $customer_forget_pass_otp->email,
                'emailadmin' => $email_admin = setting_option('email'),
                'otp' => $customer_forget_pass_otp->otp_mail,
                'name' => $user->first_name,
                'created_at' => $customer_forget_pass_otp->created_at,
                'site_name' => $site_name,
            ];
            Mail::send(
                $this->templatePath.'.mail.forget-password.forget-password',
                $data,
                function ($message) use ($data) {
                    $message->from($data['emailadmin'], $data['site_name']);
                    $message->to($data['email'])
                        ->subject($data['otp'].' là mã OTP của '.$data['site_name']);
                }
            );

            return redirect()->route('forgetPassword_step2');
        } else {
            return redirect()
                ->back()
                ->withErrors(['email' => 'Email không tồn tại.'])
                ->withInput();
        }
    }

    public function forgetPassword_step2()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if ((! isset($_SESSION['otp_forget']) && ! isset($_SESSION['email_forget'])) || $_SESSION['otp_forget'] == '' || $_SESSION['email_forget'] == '') {
            if (session_status() === PHP_SESSION_ACTIVE) {
                session_unset();
                session_destroy();
            }

            return redirect()->route('forgetPassword');
        } else {
            return view($this->templatePath.'.account.auth.forget-password-step-2', $this->data);
        }
    }

    public function actionForgetPassword_step2(Request $rq)
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $customer_forget_pass_otp = Customer_forget_pass_otp::where('otp_mail', '=', $rq->otp_mail)
            ->where('otp_mail', '=', $_SESSION['otp_forget'])
            ->where('status', '=', 0)
            ->whereRaw("TIME_TO_SEC('".Carbon::now()."') - TIME_TO_SEC(created_at) < 300 ")
            ->first();
        if ($customer_forget_pass_otp) {
            $_SESSION['otp_true'] = 1;

            return redirect()->route('forgetPassword_step3');
        } else {
            return redirect()->back()->withErrors(['otp_mail' => 'OTP không đúng.']);
        }
    }

    public function forgetPassword_step3()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if ((! isset($_SESSION['otp_forget']) && ! isset($_SESSION['email_forget']) && ! isset($_SESSION['otp_true'])) || $_SESSION['otp_forget'] == '' || $_SESSION['email_forget'] == '') {
            if (session_status() === PHP_SESSION_ACTIVE) {
                session_unset();
                session_destroy();
            }

            return redirect()->route('forgetPassword');
        } else {
            return view($this->templatePath.'.account.auth.forget-password-step-3', $this->data);
        }
    }

    public function actionForgetPassword_step3(Request $rq)
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $customer_forget_pass_otp = Customer_forget_pass_otp::where('email', '=', $_SESSION['email_forget'])
            ->where('otp_mail', '=', $_SESSION['otp_forget'])
            ->where('status', '=', 0)
            ->first();
        if ($customer_forget_pass_otp) {
            $validator = Validator::make($rq->all(), [
                'new_password' => 'required|min:6|required_with:confirm_new_password|same:confirm_new_password',
                'confirm_new_password' => 'required|min:6',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }
            $customer = User::where('email', '=', $_SESSION['email_forget'])->first();
            $customer->password = bcrypt($rq->new_password);
            $customer->save();

            $customer_forget_pass_otp->status = 1;
            $customer_forget_pass_otp->save();

            if (session_status() === PHP_SESSION_ACTIVE) {
                session_unset();
                session_destroy();
            }

            return redirect()
                ->route('index')
                ->with('success', 'Password changed successfully');
        } else {
            if (session_status() === PHP_SESSION_ACTIVE) {
                session_unset();
                session_destroy();
            }

            return redirect()->route('forgetPassword');
        }
    }

    public function myOrder()
    {
        return app(AccountController::class)->myOrder();
    }

    public function myOrderDetail($id_cart)
    {
        return app(AccountController::class)->myOrderDetail($id_cart);
    }

    public function orderView()
    {
        $id = request()->id;
        $order = Addtocard::query()
            ->where('cart_id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if ($order) {
            $view = view($this->templatePath.'.customer.order-view', compact('order'))->render();

            return response()->json([
                'error' => 1,
                'view' => $view,
            ]);
        }

        return response()->json([
            'error' => 0,
            'message' => 'Không tìm thấy đơn hàng!',
        ], 403);
    }

    public function myPoint()
    {
        $user = request()->user();
        $user_point = $user->getVIP();
        // dd($user_point);

        $this->data = [
            'user' => $user,
            'user_point' => $user_point,
            'seo' => [
                'seo_title' => 'Thông tin tài khoản',
            ],
        ];

        return view($this->templatePath.'.customer.my-point', $this->data);
    }

    public function logoutCustomer()
    {
        return app(CustomerAuthController::class)->logoutCustomer();
    }

    public function messages()
    {
        $this->data['user'] = auth()->user();
        $this->data['seo'] = [
            'seo_title' => 'Messages',
        ];

        return view($this->templatePath.'.customer.messages', $this->data);
    }

    public function myReviews()
    {
        return redirect()->route('customer.dashboard');
    }

    public function postReviews(Request $request)
    {
        return redirect()->route('customer.dashboard');
    }

    public function refused()
    {
        return redirect()->route('customer.dashboard');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Customer_forget_pass_otp;
use App\Models\Frontend\Page;
use App\Models\Frontend\User;
use App\Models\Rating_Product;
use App\Models\Sponser;
use App\Models\Theme;
use App\Models\User_register_email;
use Auth;
use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mail;
use Redirect;
use Validator;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $this->validate(request(), [
            // put fields to be validated here
        ]);

        $user = new User;
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->password = Hash::make($request['password']);
        $user->status = $request['acess_user_create_user'];

        // $user->status= $request['acess_user_create_user'];

        // add other fields
        $user->save();

        return redirect('/home');
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index()
    {
        $show_post_home = Page::posts()->select('pages.name', 'pages.slug', 'pages.description', 'pages.image as avt_show_main_post')->where('show_main_post', 1)->first();
        $show_page_home = Page::pages()->select('pages.name', 'pages.slug', 'pages.description', 'pages.image as avt_show_main_post')->where('show_main_post', 1)->first();
        $sponser_home = Sponser::where('sponser.status', '=', 0)->orderBy('order', 'asc')->get();

        return view(
            'home',
            [
                'show_post_home' => $show_post_home,
                'show_page_home' => $show_page_home,
                'sponser_home' => $sponser_home,
            ]
        );
    }

    public function searchNews(Request $rq)
    {
        $key_search = $rq->query_string;
        // $data_customers = Page::posts()->join('page_categories', 'page_categories.page_id', '=', 'pages.id')
        //                     ->join('categories', 'categories.id', '=', 'page_categories.category_id')
        //                     ->where('pages.title', 'LIKE', '%' . $key_search . '%')
        //                     ->orderBy('pages.created_at')
        //                     ->select('pages.*', 'categories.name as categoryName', 'categories.slug as categorySlug');
        //                     ->paginate(config('app.item_list_post'));
        // ->load('category_post')
        $data_customers_2 = Page::posts()->where('pages.name', 'LIKE', '%'.$key_search.'%')
            ->orderBy('pages.created_at')
            ->select('pages.*')
            ->paginate(config('app.item_list_post'));

        return view('tintuc.search', ['data_customers_2' => $data_customers_2]);
    }

    public function searchNewsAjax(Request $rq)
    {
        $key_search = $rq->query_string;
        $html_post = '';
        $url_img = 'images/article';
        if ($key_search != '') {
            // $data_posts = Page::posts()->with('categories')->where('pages.title', 'LIKE', '%' . $key_search . '%')
            // ->orderBy('pages.created_at')
            // ->select('post.*')
            // ->get();

            $name_product = $rq->query_string;
            $data_customers = DB::table('category_theme')
                ->join('join_category_theme', 'category_theme.categoryID', '=', 'join_category_theme.id_category_theme')
                ->join('theme', 'join_category_theme.id_theme', '=', 'theme.id')
                ->where('theme.title', 'like', '%'.$name_product.'%')
                ->where('theme.status', '=', 0)
                // ->orderBy('theme.price_promotion',$order)
                ->groupBy('theme.slug')
                ->orderByRaw('theme.order_short DESC')
                ->select('theme.*', 'category_theme.categoryName', 'category_theme.categorySlug', 'category_theme.categoryDescription', 'category_theme.categoryID', 'category_theme.categoryContent', 'category_theme.categoryContent_en', 'category_theme.categoryDescription_en')
                ->get();

            foreach ($data_customers as $value_post) {
                if (! empty($value_post->thubnail) && $value_post->thubnail != '') {
                    // $thumbnail_thumb= Helpers::getThumbnail($url_img,$value_post->thubnail, 80, 74, "resize");
                    // if(strpos($thumbnail_thumb, 'placehold') !== false):
                    //     $thumbnail_thumb=$url_img.$thumbnail_thumb;
                    // endif;
                    $thubnail = $value_post->thubnail;
                    $thumbnail_thumb = 'images/product/'.$thubnail;
                } else {
                    $thumbnail_thumb = 'https://dummyimage.com/80x74/000/fff';
                }

                $link_url = route('tintuc.details', [$value_post->categorySlug, $value_post->slug]);
                $title_post = $value_post->title;
                $html_post .= '  
                    <div class="box-list">
                    <div class="img-post-ajax">
                      <a href="'.$link_url.'"><img width="80" height="74" src="'.$thumbnail_thumb.'" alt=""></a>
                    </div>
                    <div class="title-post-ajax">
                      <a href="'.$link_url.'">'.$title_post.'</a>
                    </div>
                  </div>
                ';
            }
            echo $html_post;
        }
    }

    public function sendRequest(Request $request)
    {
        $this->validate($request, [
            'your_name' => 'required',
            'your_email' => 'required|email',
            'your_mobile' => 'required',
            'your_message' => 'required',
            // 'g-recaptcha-response' => 'required|captcha',
        ], [
            'your_name.required' => 'Mời Bạn nhập vào tên của bạn',
            'your_email.required' => 'Mời bạn nhập Email',
            'your_mobile.required' => 'Mời bạn nhập Số điện thoại',
            'your_message.required' => 'Mời bạn nhập nội dung',
        ]);
        // $data=$request->all();
        $email_admin = setting_option('emailadmin');
        $cc_email = setting_option('toemail');
        $name_admin_email = setting_option('name-admin');
        $subject_default = setting_option('title-email');
        $data = [
            'name_product' => $request->name_product,
            'link_product' => $request->link_product,
            'your_name' => $request->your_name,
            'your_email' => $request->your_email,
            'your_mobile' => $request->your_mobile,
            'your_message' => $request->your_message,
            'email_admin' => $email_admin,
            'cc_email' => $cc_email,
            'name_email_admin' => $name_admin_email,
            'subject_default' => $subject_default,
        ];

        $note_message_contact = setting_option('note-email');
        Mail::send(
            'email.customer_request',
            $data,
            function ($message) use ($data) {
                $message->from($data['your_email'], $data['your_name']);
                $message->to($data['email_admin'])
                    ->cc($data['cc_email'], $data['name_email_admin'])
                    ->subject($data['subject_default'].'- Người dùng:'.$data['your_name']);
            }
        );

        return redirect($request->link_product)
            ->with('success_msg', $note_message_contact);
    }

    public function completeOrder()
    {
        return view('theme.page-thank');
    }

    public function amp()
    {
        return view('amp');
    }

    public function postUserRegisterEmail(Request $request)
    {
        if ($request->email_register != '') {
            $check = User_register_email::where('email', '=', $request->email_register)->first();
            if ($check) {
                return redirect()->back();
            } else {
                $data = new User_register_email;
                $data->email = $request->email_register;
                $data->save();

                return redirect()->back();
            }
        }
    }

    public function registerCustomer()
    {
        return view('customer.auth.register');
    }

    public function createCustomer(Request $rq)
    {
        $validation_rules = [
            'full_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'birthday_day' => 'required|max:2',
            'birthday_year' => 'required|max:4',
            'birthday_month' => 'required|max:2',
            'phone' => 'required|max:10|unique:users',
            'slt_province' => 'required|max:255',
            'slt_district' => 'required|max:255',
            'slt_ward' => 'required|max:255',
            'address' => 'required|max:255',
        ];
        $messages = [
            'full_name.required' => 'Hãy nhập họ của bạn',
            'full_name.max' => '"Họ" tối đa 255 ký tự',
            'email.required' => 'Hãy nhập vào địa chỉ Email',
            'email.email' => 'Địa chỉ Email không đúng định dạng',
            'email.max' => 'Địa chỉ Email tối đa 255 ký tự',
            'email.unique' => 'Địa chỉ Email đã tồn tại',
            'birthday_month.required' => 'Nhập tháng',
            'birthday_month.max' => 'Tối đa 2 ký tự',
            'birthday_day.required' => 'Nhập ngày',
            'birthday_day.max' => 'Tối đa 2 ký tự',
            'birthday_year.required' => 'Nhập năm',
            'birthday_year.max' => 'Tối đa 4 ký tự',
            'password.required' => 'Hãy nhập mật khẩu',
            'password.min' => 'Mật khẩu tối thiểu 6 ký tự',
            'password.confirmed' => 'Xác nhận mật khẩu không đúng',
            'phone.required' => 'Hãy nhập số điện thoại',
            'phone.max' => 'Số điện thoại tối đa 12 ký tự',
            'phone.unique' => 'Số điện thoại đã tồn tại',
            'address.required' => 'Hãy nhập vào địa chỉ của bạn',
            'address.max' => 'Địa chỉ được phép nhập tối đa 255 ký tự',
            'slt_province.required' => 'Chọn tỉnh thành',
            'slt_district.required' => 'Chọn quận/huyện',
            'slt_ward.required' => 'Chọn phường/xã',
        ];
        $validator = Validator::make($rq->all(), $validation_rules, $messages);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }

        $new_cus = new User;
        $new_cus->name = $rq->full_name;
        $new_cus->email = $rq->email;
        $new_cus->birthday = $rq->birthday_year.'-'.$rq->birthday_month.'-'.$rq->birthday_day;
        $new_cus->phone = $rq->phone;
        $new_cus->address = $rq->address;
        $new_cus->province = $rq->slt_province;
        $new_cus->district = $rq->slt_district;
        $new_cus->ward = $rq->slt_ward;
        $new_cus->password = bcrypt($rq->password);
        $new_cus->save();

        Auth::login($new_cus);

        // $check_discount_for_new_customer = Helpers::get_option_minhnn('on-off-discount-for-new-customer');
        // if($check_discount_for_new_customer == 'on'):
        //     while (true) {
        //         $code_discount = Helpers::auto_code_discount();
        //         $checkcode = Discount_code::where('discount_code.code', '=', $code_discount)->get();
        //         if(count($checkcode)==0){
        //             break;
        //         }
        //     }

        //     $date = date("d");
        //     $month = date("m");
        //     $year = date("Y");
        //     $hours = date("H");
        //     $min = date("i");
        //     $sec = date("s");
        //     $expired =date('Y-m-d H:i:s',mktime($hours,$min,$sec,$month,($date+60),$year));
        //     $convert_expired = date('d-m-Y H:i:s',strtotime($expired));

        //     $discount = new Discount_code;
        //     $discount->code = $code_discount;
        //     $discount->expired =$expired;
        //     $discount->type = 'onetime';
        //     $discount->percent = Helpers::get_option_minhnn('discount-for-new-customer');
        //     $discount->status = 0;
        //     $discount->save();

        //     $data = array(
        //         'name'=>$new_cus->first_name.' '.$new_cus->last_name,
        //         'email'=>$new_cus->email,
        //         'code_discount' => $discount->code,
        //         'expired' => $discount->expired,
        //         'subject_default'=>'Phiếu giảm giá từ Siêu thị Ánh Dương chào mừng thành viên mới!'
        //     );
        //     Mail::send('email.user_register',
        //         $data,
        //         function($message) use ($data) {
        //             $message->from($data['email'],'DADA BEAUTY');
        //             $message->to($data['email'])
        //                 ->subject($data['subject_default']);
        //         }
        //     );
        // endif;
        return redirect()->route('index');
    }

    public function showLoginForm()
    {
        return view('customer.auth.login');
    }

    public function postLogin(Request $request)
    {
        $login = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        if ($request->remember_me == 1) {
            $remember_me = true;
        } else {
            $remember_me = false;
        }
        if (Auth::attempt($login, $remember_me)) {
            return redirect()->back();
        } else {
            return redirect()->back()->with('status', 'Email hoặc Password không chính xác');
        }
    }

    // xử lý quên mật khẩu
    public function forgetPassword()
    {
        return view('customer.auth.forget-password');
    }

    public function actionForgetPassword(Request $rq)
    {
        $rq->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', '=', $rq->email)->first();
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
            $data = [
                'email' => $customer_forget_pass_otp->email,
                'emailadmin' => $email_admin = setting_option('emailadmin'),
                'otp' => $customer_forget_pass_otp->otp_mail,
                'name' => $user->first_name,
                'created_at' => $customer_forget_pass_otp->created_at,
            ];
            Mail::send(
                'email.forget-password.forget-password',
                $data,
                function ($message) use ($data) {
                    $message->from($data['emailadmin'], 'SIÊU THỊ Miền Nam');
                    $message->to($data['email'])
                        ->subject($data['otp'].' là mã OTP của SIÊU THỊ Miền Nam.');
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
            return view('customer.auth.forget-password-step-2');
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
            return view('customer.auth.forget-password-step-3');
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
                return redirect()->back()
                    ->withErrors($validator);
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
                ->route('user.login')
                ->with('success', 'Mật khẩu đã được thay đổi.');
        } else {
            if (session_status() === PHP_SESSION_ACTIVE) {
                session_unset();
                session_destroy();
            }

            return redirect()->route('forgetPassword');
        }
    }

    public function getStart(Request $request)
    {

        $check_rating_user = Rating_Product::where('user_id', $request->userid)->where('id_product', $request->proid)->first();
        $user = User::find($request->userid);
        $ev_content = $request->ev_content;
        $proid = $request->proid;
        $userid = $request->userid;
        $num_start = $request->num_start;

        $rating_product_data = [
            'id_product' => $proid,
            'user_id' => $userid,
            'rating' => $num_start,
            'rating_content' => $ev_content,
            'link_product' => $request->link_product,
        ];
        if (empty($check_rating_user)) {
            // echo 'Da danh gia';
            $rating_product = Rating_Product::create($rating_product_data);

            // send mail to admin
            $email_admin = setting_option('emailadmin');
            $cc_email = setting_option('toemail');
            // $name_admin_email=Helpers::get_option_minhnn('name-admin');
            $name_admin_email = setting_option('name-admin');
            $subject_default = setting_option('title-email-card');
            $data_admin = [
                'user' => $user->name,
                'rating' => $num_start,
                'rating_content' => $ev_content,
                'link_product' => $request->link_product,
                'type' => 'rating',
                'email_admin' => $email_admin,
                'name_admin_email' => $name_admin_email,
                'subject_default' => $subject_default,

            ];
            $jobClass = '\\App\\Jobs\\SendMailNewOrder';
            if (class_exists($jobClass)) {
                dispatch(new $jobClass($data_admin));
            }
            //     Mail::send('email.user_rating',
            //     $data_admin,
            //     function($message) use ($data_admin) {
            //         $message->from($data_admin['email_admin']);
            //         $message->to('nttung9597@gmail.com')
            //         ->subject('Đánh giá sản phẩm từ khách hàng');
            //     }
            // );

            return response()->json([
                'name' => $user->name,
                'num_start' => $num_start,
                'rating_cont' => $ev_content,
                'user_id' => $userid,
            ]);
        }
    }

    public function countView(Request $rq)
    {
        $pro_id = $rq->pro_id;
        $product = Theme::find($pro_id);
        $countView = $product->pro_views + 1;
        // $product->update(['pro_views' => $countView]);
        Theme::where('id', $pro_id)->update(['pro_views' => $countView]);
        // echo $pro_id;
    }
}

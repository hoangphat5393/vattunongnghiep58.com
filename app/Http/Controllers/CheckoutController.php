<?php

namespace App\Http\Controllers;

use App\Jobs\Job_SendMail;
use App\Models\Addtocard;
use App\Models\Addtocard_Detail;
use App\Models\ContactPayment;
use App\Models\Frontend\EmailTemplate;
use App\Models\Frontend\Product;
use App\Models\Frontend\ShopOrderPaymentStatus;
use App\Models\Frontend\ShopOrderStatus;
use App\Models\Frontend\User;
use App\Models\Page;
use App\Support\EmailTemplateCodes;
use App\Traits\LocalizeController;
use Auth;
use Cart;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    use LocalizeController;

    public $data = [
        'error' => false,
        'success' => false,
        'message' => '',
    ];

    public function __construct()
    {
        parent::__construct();
        $this->data['statusOrder'] = ShopOrderStatus::getIdAll();
        $this->data['orderPayment'] = ShopOrderPaymentStatus::getIdAll();
    }

    public function checkout()
    {
        $this->localized();
        if (Cart::count()) {
            session()->forget('option');

            // dd(Cart::checkout());

            if (session()->has('cart-info')) {
                $data = session()->get('cart-info');
                $this->data['cart_info'] = $data;
            }

            $this->data['seo'] = [
                'seo_title' => 'Đặt hàng',
            ];

            return view('theme.cart.checkout', $this->data);
        } else {
            return $this->cart();
        }
    }

    public function checkPayment($cart_id)
    {
        $this->localized();
        $this->data['cart'] = \App\Models\Frontend\Addtocard::where('cart_id', $cart_id)->first();
        // dd($cart);
        if ($this->data['cart'] && $this->data['cart']['cart_status'] == 'waiting-payment') {
            return view($this->templatePath.'.cart.check-payment', $this->data);
        } else {
            return redirect(url('/'));
        }
    }

    // CHECK EMAIL EXISTS
    public function checkEmail()
    {
        $this->localized();
        $data = request()->all();
        $user = User::where('email', $data['email'])->first();

        if (! empty($user)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    // CHECK PHONE EXISTS
    public function checkPhone()
    {
        $this->localized();
        $data = request()->all();
        $user = User::where('phone', $data['phone'])->first();

        if (! empty($user)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    public function checkoutConfirm()
    {
        $this->localized();
        $data = request()->all();

        $this->data['carts'] = Cart::content();

        if ($data) {
            session()->forget('cart-info');

            if (auth()->check()) {
                $user = auth()->user();
                $data['fullname'] = $user->fullname;
                $data['phone'] = $user->phone;
                $data['email'] = $user->email;
                $data['address_line1'] = $user->address;
            } else {
                $data['fullname'] = $data['ship_name'] ?? $data['ship_phone'] ?? 'N/A';
                $data['email'] = $data['ship_email'] ?? '';
                $data['phone'] = $data['ship_phone'] ?? '';
            }

            session()->put('cart-info', $data);

            return redirect()->route('cart.checkout');
        } else {
            // dd(session()->all());
            if (session()->has('order-waiting')) {
                if (session()->has('order-waiting')) {
                    $cart_id = session('order-waiting')[0];
                    session()->forget('order-waiting');

                    return redirect()->route('cart.check_payment', $cart_id);
                }
            } elseif (! session()->has('cart-info')) {
                return redirect()->route('cart');
                // return redirect()->route('cart.checkout'); // check again
            }

            $data = session()->get('cart-info');
        }

        $this->data['cart_info'] = $data;
        if (isset($data['postal_code'])) {
            $ships = $this->USPS($data['pounds'], $data['ounces'], setting_option('postal_code'), $data['postal_code']);
            // dd($ships);
            if (is_array($ships)) {
                // $this->data['ships'] = array_slice($ships, 0, 4);
                $this->data['ships'] = $ships;
                $ship_first = current($this->data['ships']);
                $ship_rate = $ship_first['Rate'] ?? 0;
                $cart_total = $ship_rate + Cart::total(2);
                $this->data['data_shipping'] = [
                    'shipping_cost' => $ship_first['Rate'],
                    'shipment_amount' => render_price($ship_first['Rate']),
                    'cart_total' => render_price($cart_total),
                ];
            }
        } else {
            $this->data['data_shipping'] = [
                'shipping_cost' => 0,
                'shipment_amount' => 0,
                'cart_total' => 0,
            ];
        }

        $this->data['seo'] = [
            'seo_title' => 'Xác nhận đơn hàng',
        ];

        return view($this->templatePath.'.checkout.checkout', $this->data);
    }

    public function quickBuyConfirm()
    {
        return redirect()->route('cart.checkout');
    }

    public function checkoutProcess(Request $request)
    {
        return redirect()
            ->route('cart.checkout')
            ->with(
                'checkout_recaptcha_error',
                'Luồng thanh toán cũ đã ngừng. Vui lòng hoàn tất đơn hàng tại trang đặt hàng mới.'
            );
    }

    public function checkoutProcessLegacy(Request $request)
    {
        $this->forgetCartSession();
        $data_request = request()->all();
        $data = session()->get('cart-info');
        if (! $data) {
            if (auth()->check()) {
                $user = auth()->user();
                $data['fullname'] = $user->fullname;
                $data['phone'] = $user->phone;
                $data['email'] = $user->email;
                $data['address_line1'] = $user->address;
                session()->put('cart-info', $data);
            } else {
                return redirect(route('cart'));
            }
        }

        $data['delivery'] = $data['delivery'] ?? 'pick_up';

        $payment_method = [];
        if (! empty($data_request['payment_method'])) {
            $payment_method = explode('__', $data_request['payment_method']);
        }

        $shipping_cost = $data_request['shipping_cost'] ?? 0;

        $option_session = session()->get('option');
        if ($option_session) {
            $option = json_decode($option_session[0], true);
            $total = $option['price'] * $option['qty'];

            $cart_content[] = $option;
            $price = $total; // + $shipping_cost;
        } else {
            $price = Cart::total(2); // + $shipping_cost;
            $total = $price;
            $cart_content = Cart::content()->toArray();
        }

        if (isset($data_request['ship'])) {
            $shipping_type = explode('__', $data_request['ship'])[1] ?? '';
        }

        $database = [
            'firstname' => $data['firstname'] ?? '',
            'lastname' => $data['lastname'] ?? '',
            'name' => $data['fullname'] ?? '',
            'cart_phone' => $data['phone'],
            'cart_email' => $data['email'],
            'cart_address' => $data['address_line1'] ?? '',
            'shipping_type' => $data['delivery'] ?? '',
            'shipping_cost' => $shipping_cost,
            'cart_note' => $data['cart_note'] ?? '',
            'payment_method' => $data_request['payment_method'] ?? '',
            'cart_total' => $total,
            'user_id' => Auth::check() ? Auth::user()->id : 0,
            'cart_code' => auto_code(),
            'cart_payment' => 2, // chua thanh toan
            'cart_status' => 1, // cho xac nhan
        ];

        if (! empty($data['delivery']) && $data['delivery'] == 'shipping') {
            $database['cart_address'] = $data['address_line1'] ?? '';
            $database['cart_address2'] = $data['address_line2'] ?? '';
            $database['city'] = $data['city_locality'] ?? '';
            $database['province'] = $data['state_province'] ?? '';
            $database['country_code'] = $data['country_code'] ?? '';
            $database['postal_code'] = $data['postal_code'] ?? '';
            $database['company'] = $data['company'] ?? '';
        }

        $respons = \App\Models\Frontend\Addtocard::create($database);

        $order_id = auto_code('Order', $respons->cart_id);
        $cart = \App\Models\Frontend\Addtocard::where('cart_id', $respons->cart_id)->first();
        $cart->cart_code = $order_id;
        $cart->save();

        // insert in Addtocard_Detail
        foreach ($cart_content as $key => $value) {
            $product_item = Product::find($value['id']);

            \App\Models\Frontend\Addtocard_Detail::create([
                'product_id' => $value['id'],
                'cart_id' => $respons->cart_id,
                'user_id' => $product_item->user_id ?? 0,
                'name' => $value['name'] ?? $product_item->name,
                'quanlity' => $value['qty'],
                'subtotal' => $value['subtotal'] ?? ($value['price'] * $value['qty']),
            ]);
        }

        if (! empty($payment_method[0]) && $payment_method[0] == 'stripe') {
            session()->push('cart_code', $cart->cart_code);

            $data_request['cart_code'] = $cart->cart_code;
            $data_request['cart_content'] = $cart_content;
            $data_request['payment_method'] = $payment_method[1] ?? 'card';

            // dd($data_request);
            return (new StripeController)->checkout($data_request);
        }

        $id_post = $respons->cart_id;
        $title = 'Order payment '.$order_id;

        if (Cart::count() || $option_session) {
            if ($price) {
                try {
                    $data_email = $cart->toArray();
                    $data_email['email_admin'] = setting_option('email_admin');
                    $data_email['subject_default'] = 'Payment success';

                    $checkContent = EmailTemplate::findPublishedByCode(EmailTemplateCodes::ORDER_TO_USER);
                    $checkContent_admin = EmailTemplate::findPublishedByCode(EmailTemplateCodes::ORDER_TO_ADMIN);
                    if ($checkContent || $checkContent_admin) {
                        $email_admin = setting_option('email_admin');
                        $company_name = setting_option('company_name');

                        $content = htmlspecialchars_decode($checkContent->text);
                        $content_admin = htmlspecialchars_decode($checkContent_admin->text);

                        $order_detail = Addtocard_Detail::where('cart_id', $id_post)->get();
                        $orderDetail = '';
                        foreach ($order_detail as $key => $detail) {
                            $product = $detail->getProduct;
                            $nameProduct = $detail->name;
                            $product_attr = '';

                            if ($detail->attribute) {
                                $attribute = json_decode(htmlspecialchars_decode($detail->attribute), true);
                                // $attributesGroup = \App\Variable::where('status', 0)->where('parent', 0)->pluck('name', 'id')->all();

                                // foreach ($attribute as $groupAtt => $att) {
                                //     $product_attr .= '<tr><td>' . $attributesGroup[$groupAtt] . '</td><td><strong>' . render_option_name($att) . '</strong></td></tr>';
                                // }
                            }
                            $orderDetail .= '<tr><td colspan="2"><b>'.($key + 1).'.'.$detail->name.'</b></td></tr>';
                            $orderDetail .= $product_attr;
                            $orderDetail .= '<tr><td width="150">Price:</td><td><strong>'.render_price($detail->subtotal / $detail->quanlity).'</strong></td></tr>';
                            $orderDetail .= '<tr><td>Qty:</td><td><strong>'.number_format($detail->quanlity).'</strong></td></tr>';
                            $orderDetail .= '<tr><td>Total:</td><td><strong>'.render_price($detail->subtotal).'</strong></td></tr>';
                            $orderDetail .= '<tr><td colspan="2"><hr></td></tr>';
                        }

                        // Phương thức nhận hàng
                        $receive = $cart->shipping_type ? '' : 'pick_up';
                        // $receive_method = $cart->shipping_type ? 'Nhận hàng: '.$cart->shipping_type : 'Pick up';
                        // $receive_html = '<p>- '. $receive_method .'</p>';
                        $receive_html = '';
                        if ($receive == 'pick_up') {
                            $address_full = '';
                            $receive_html .= '<div>- Nhận tại cửa hàng: </div>'.htmlspecialchars_decode(setting_option('pickup_address'));
                        } else {
                            $address_full = implode(', ', array_filter([$cart->cart_address, $cart->city, $cart->province, $cart->country_code]));
                            $receive_html .= '<p>- Vận chuyển đến: '.$address_full.'<p>';
                        }

                        // phuong thuc thanh toan
                        $payment_method = $cart->payment_method ?? 'cash';
                        $payment_method_html = '';
                        if ($payment_method == 'cash') {
                            $payment_method_html = '<div>- Thanh toán bằng tiền mặt khi nhận hàng</div>';
                        } else {
                            $payment_method_html = '<div class="mb-3">- '.__('bank_transfer').'</div>';
                            $payment_method_html .= htmlspecialchars_decode(setting_option('banks'));
                        }

                        $dataFind = [
                            '/\{\{\$orderID\}\}/',
                            '/\{\{\$toname\}\}/',
                            '/\{\{\$email\}\}/',
                            '/\{\{\$address\}\}/',
                            '/\{\{\$phone\}\}/',
                            '/\{\{\$comment\}\}/',
                            '/\{\{\$subtotal\}\}/',
                            '/\{\{\$total\}\}/',
                            '/\{\{\$receive\}\}/',
                            '/\{\{\$orderDetail\}\}/',
                            '/\{\{\$payment_method\}\}/',
                        ];
                        $dataReplace = [
                            $order_id ?? '',
                            $data_email['name'] ?? '',
                            $data_email['cart_email'] ?? '',
                            $data_email['cart_address'] ?? '',
                            $data_email['cart_phone'] ?? '',
                            $data_email['cart_note'] ?? '',
                            render_price($cart->cart_total - $cart->shipping_cost),
                            render_price($cart->cart_total),
                            $receive_html,
                            $orderDetail,
                            $payment_method_html,
                        ];
                        $content = preg_replace($dataFind, $dataReplace, $content);
                        $content_admin = preg_replace($dataFind, $dataReplace, $content_admin);
                        // dd($content);
                        $dataView = [
                            'content' => $content,
                        ];
                        $config = [
                            'to' => $data_email['cart_email'],
                            'subject' => 'Đơn hàng mới - Mã đơn hàng: '.$order_id,
                        ];

                        $dataView_sys = [
                            'content' => $content_admin,
                        ];
                        $config_sys = [
                            'to' => $email_admin,
                            'subject' => 'Đơn hàng mới - Mã đơn hàng: '.$order_id,
                        ];

                        $send_mail = new SendMail('email.content', $dataView, $config);
                        $sendEmailJob = new Job_SendMail($send_mail);
                        dispatch($sendEmailJob)->delay(now()->addSeconds(5));

                        $send_mail_admin = new SendMail('email.content', $dataView_sys, $config_sys);
                        $sendEmailJob_admin = new Job_SendMail($send_mail_admin);
                        dispatch($sendEmailJob_admin)->delay(now()->addSeconds(3));
                    }

                    Cart::destroy();
                    session()->forget('option');
                    session()->forget('cart-info');
                    if ($option_session) {
                        session()->forget('option_session');
                    }
                    session()->forget('cart_code');
                    session()->push('cart_code', $cart->cart_code);

                    return redirect()->route('cart.checkout.success');
                } catch (Exception $e) {
                    return $e->getMessage();
                }
            }
        }
    }

    public function forgetCartSession()
    {
        session()->forget('cart_code');
    }

    public function success()
    {
        $cart_code = session()->get('cart_code');
        if ($cart_code) {
            $cart = Addtocard::where('cart_code', $cart_code)->first();

            $content_success = Page::find(94);

            $link = '<a href="'.route('cart.view', $cart->cart_code).'" title="">'.$cart->cart_code.'</a>';
            $content_success->content = str_replace('{$order_link}', $link, $content_success->content);
            // dd($content_success);
            $this->data['cart'] = $cart;
            $this->data['seo'] = [
                'seo_title' => $content_success->title,

            ];
            $this->data['content_success'] = $content_success;

            return view($this->templatePath.'.cart.checkout-success', $this->data);
        }

        return redirect(url('/'));
    }

    public function view($id)
    {
        if ($id) {
            // $this->data['order_status'] = $this->orderStatus();
            // $this->data['orderPayment'] = $this->orderPayment();
            // dd($this->orderPayment);
            $this->data['order'] = Addtocard::where('cart_code', $id)->first();
            $this->data['order_detail'] = $order_detail = Addtocard_Detail::where('cart_id', $this->data['order']->cart_id)->get();

            $total_price = isset($order_detail->total) ? $order_detail->total : 0;

            // $data = Addtocard::where('user_id', Auth::user()->id)->where('cart_id', $id_cart)->first();
            if ($this->data['order']) {
                $this->data['seo'] = [
                    'seo_title' => 'Đơn hàng - '.$this->data['order']->cart_code,

                ];

                return view($this->templatePath.'.cart.view', $this->data);
            } else {
                return view('errors.404');
            }
        }
    }

    public function requestPaymentSuccess($cart_code)
    {
        $cart = Addtocard::where('cart_code', $cart_code)->first();
        // dd($cart);
        if ($cart) {
            $this->data['cart'] = $cart;
            $this->data['seo'] = [
                'seo_title' => 'Yêu cầu đã thanh toán cho đơn hàng '.$cart_code,
            ];

            return view($this->templatePath.'.cart.request_payment', $this->data);
        } else {
            return view('errors.404');
        }
    }

    public function post_requestPaymentSuccess()
    {
        $data_email = request()->all();

        $checkContent = EmailTemplate::findPublishedByCode(EmailTemplateCodes::REQUEST_PAYMENT_SUCCESS);
        if ($checkContent) {
            $email_admin = 'huunamtn@gmail.com'; // setting_option('email_admin');
            $company_name = setting_option('company_name');

            $content = htmlspecialchars_decode($checkContent->text);
            $orderID_link = '<a href="'.route('cart.view', $data_email['cart_code']).'">'.$data_email['cart_code'].'</a>';
            $dataFind = [
                '/\{\{\$orderID_link\}\}/',
                '/\{\{\$orderID\}\}/',
                '/\{\{\$toname\}\}/',
                '/\{\{\$email\}\}/',
                '/\{\{\$phone\}\}/',
                '/\{\{\$comment\}\}/',
            ];
            $dataReplace = [
                $orderID_link ?? '',
                $data_email['cart_code'] ?? '',
                $data_email['request_name'] ?? '',
                $data_email['request_email'] ?? '',
                $data_email['request_phone'] ?? '',
                $data_email['request_message'] ?? '',
            ];
            $content = preg_replace($dataFind, $dataReplace, $content);

            $dataView = [
                'content' => $content,
            ];
            $config = [
                'to' => $email_admin,
                'subject' => 'Thông báo đơn hàng '.$data_email['cart_code'].' đã được thanh toán.',
            ];

            $file_path = '';
            $attach = [];
            $folderPath = '/images/paid-file';
            if (isset($data_email['request_file'])) {
                $file = request()->file('request_file');

                $filename_original = $file->getClientOriginalName();
                $filename_ = pathinfo($filename_original, PATHINFO_FILENAME);
                $extension_ = pathinfo($filename_original, PATHINFO_EXTENSION);

                $file_slug = Str::slug($filename_);
                $file_name = uniqid().'-'.$file_slug.'.'.$extension_;
                $img_name = $folderPath.'/'.$file_name;
                $file_path = $img_name;

                $attach['fileAttach'][] = [
                    'file_path' => asset($img_name),
                    'file_name' => $filename_,
                ];

                $file->move(base_path().$folderPath, $file_name);
            }

            ContactPayment::create([
                'name' => $data_email['request_name'] ?? '',
                'email' => $data_email['request_email'] ?? '',
                'phone' => $data_email['request_phone'] ?? '',
                'content' => $data_email['request_message'] ?? '',
                'file' => $file_path,
            ]);

            $send_mail = new SendMail('email.content', $dataView, $config, $attach);
            $sendEmailJob = new Job_SendMail($send_mail);
            dispatch($sendEmailJob)->delay(now()->addSeconds(5));

            return response()->json(
                [
                    'error' => 0,
                    'view' => view($this->templatePath.'.cart.includes.send_request_payment_success')->render(),
                    'msg' => 'Đăng nhập thành công!',
                ]
            );
        }
    }

    public function getStamps(Request $request)
    {
        dd($request->all());
    }

    public function stampsForm($value = '')
    {
        return view($this->templatePath.'.stamps.form');
    }

    public function orderStatus()
    {
        $data = [
            '0' => 'Chờ xác nhận',
            '1' => 'Đã hủy',
            '2' => 'Đã nhận',
            '3' => 'Đang giao hàng',
            '4' => 'Hoàn thành',
        ];

        return $data;
    }

    public function orderPayment()
    {
        $data = [
            '0' => 'Chưa thanh toán',
            '1' => 'Đã thanh toán',
        ];

        return $data;
    }
}

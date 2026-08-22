<?php

namespace App\Http\Controllers;

use App\Models\Addtocard;
use App\Models\Addtocard_Detail;
use App\Models\Dangtin;
use App\Models\DangtinCheckDate;
use App\Models\Page;
use App\Models\Payment;
use App\Models\Theme;
use App\Traits\LocalizeController;
use Auth;
use Cart;
use DB;
use Illuminate\Http\Request;
use Mail;
use Omnipay\Omnipay;
use Stripe\Charge;
use Stripe\Stripe;

class PaypalTestController extends Controller
{
    use LocalizeController;

    public $gateway;

    public $currency;

    public function __construct()
    {

        $paypalConfigs = config('paypal');

        $this->gateway = Omnipay::create('PayPal_Rest');

        $this->gateway->setClientId($paypalConfigs['client_id']);

        $this->gateway->setSecret($paypalConfigs['secret']);

        $this->gateway->setTestMode(true); // set it to 'false' when go live

        /*$this->gateway->setUsername('sb-wvdqq4768666_api1.business.example.com');

        $this->gateway->setPassword('WCYRS3DXCX9SVQ7M');

        $this->gateway->setSignature('AyqIvE308XAc-qnj0PDuSew8t-pvAe4ifxlb6j9kIJ9VClf.IkPZZ3qg');*/

        // $this->gateway->setTestMode(true);

        $this->currency = 'USD';

    }

    public function test(Request $request)
    {

        $stripe_config = config('services.stripe');

        // dd($stripe_config['secret']);

        return view('payment.stripe', ['stripe_config' => $stripe_config]);

    }

    public function testPost(Request $request)
    {

        $stripe_config = config('services.stripe');

        Stripe::setApiKey($stripe_config['secret']);

        $data = request()->all();

        $token = $data['res_token'];

        $amount = $data['shipping_cost'] + $data['cart_total'];

        // $post_id = $request->id_post;

        $charge = Charge::create([

            'source' => $token,

            'currency' => 'USD',

            'amount' => $amount * 100,

            'description' => 'example charge',

        ]);

        if ($charge['object'] == 'charge') {

            $shipping_cost = $data['shipping_cost'] ?? 0;

            $database = [

                'firstname' => $data['firstname'] ?? '',

                'lastname' => $data['lastname'] ?? '',

                'cart_phone' => $data['phone'] ?? '',

                'cart_email' => $data['email'] ?? '',

                'cart_address' => $data['address_line1'] ?? '',

                'shipping_cost' => $shipping_cost,

                'cart_note' => $data['cart_note'] ?? '',

                'payment_id' => $charge['id'] ?? '',

                'payment_method' => $data['payment_method'] ?? '',

                'cart_total' => Cart::total(2),

                'user_id' => Auth::check() ? Auth::user()->id : 0,

                'cart_code' => auto_code(),

                'cart_status' => ($charge['paid'] == true) ? 'success' : 'waiting-payment',

            ];

            if ($data['delivery'] == 'shipping') {

                $database['cart_address'] = $data['address_line1'] ?? '';

                $database['cart_address2'] = $data['address_line2'] ?? '';

                $database['city'] = $data['city_locality'] ?? '';

                $database['province'] = $data['state_province'] ?? '';

                $database['country_code'] = $data['country_code'] ?? '';

                $database['postal_code'] = $data['postal_code'] ?? '';

                $database['company'] = $data['company'] ?? '';

            }

            $respons = Addtocard::create($database);

            $order_id = auto_code('Order', $respons->cart_id);

            $cart = Addtocard::find($respons->cart_id)->update(['cart_code' => $order_id]);

            // insert in Addtocard_Detail

            $cart_content = Cart::content();

            foreach ($cart_content as $key => $value) {

                $data_addcard_detal = [

                    'product_id' => $value->id,

                    'cart_id' => $respons->cart_id,

                    'user_id' => Theme::find($value->id)->user_id ?? 0,

                    'name' => $value->title,

                    'quanlity' => $value->qty,

                    'subtotal' => $value->subtotal,

                ];

                Addtocard_Detail::create($data_addcard_detal);

            }

            $price = Cart::total(2) + $shipping_cost;

            $id_post = $respons->cart_id;

            $title = 'Order payment '.$order_id;

            /*///--------------------------

            Mail::send('email.payment',

                $data,

                function($message) use ($data) {

                    $message->to($data['email_admin'])

                        ->subject($data['subject_default']."- Tài khoản: ".$data['your_name']);

                }

            );



            Mail::send('email.payment_to_user',

                $data,

                function($message) use ($data) {

                    $message->to($data['your_email'])

                        ->subject($data['subject_default']."- Tài khoản: ".$data['your_name']);

                }

            );*/

            // $content_success = DB::table('page')->where('id',28)->first();

            // dd($content_success);

            // dd($charge);

            Cart::destroy();

            return response()->json([

                'error' => 0,

                'order_id' => $order_id,

                'msg' => 'Payment success',

            ]);

        } else {

            if ($charge['error'] != '') {

                $error = $charge['error'];

                $data_error = ['declineCode' => $error['charge'], 'declineCode' => $error['code'], 'decline_code' => $error['decline_code'], 'message' => $error['message'], 'type' => $error['type']];

                $data_error = json_encode($data_error);

                /*$payment = new Payment;

                $payment->currency      = $this->currency;

                $payment->payment_status = $error['code'];

                $payment->post_id       = $post_id;

                $payment->type          = 'stripe';

                $payment->save();*/

                dd($charge['error']);

            }

        }

    }

    public function index()
    {

        return view('payment.payment');

    }

    public function charge(Request $request)
    {

        $data = session()->get('cart-info')[0];

        $data_request = request()->all();

        $shipping_cost = $data_request['shipping_cost'] ?? 0;

        $option_session = session()->get('option');

        if ($option_session) {

            $option = json_decode($option_session[0], true);

            $total = $option['price'] * $option['qty'];

            $cart_content[] = $option;

            $price = $total + $shipping_cost;

        } else {

            $price = Cart::total(2) + $shipping_cost;

            $total = Cart::total(2);

            $cart_content = Cart::content();

        }

        if (isset($data_request['ship'])) {

            $shipping_type = explode('__', $data_request['ship'])[1] ?? '';

        }

        $database = [

            'firstname' => $data['firstname'] ?? '',

            'lastname' => $data['lastname'] ?? '',

            'cart_phone' => $data['phone'],

            'cart_email' => $data['email'],

            'cart_address' => $data['address_line1'],

            'shipping_type' => $shipping_type ?? '',

            'shipping_cost' => $shipping_cost,

            'cart_note' => $data['cart_note'],

            'payment_method' => $data['payment_method'],

            'cart_total' => $price,

            'user_id' => Auth::check() ? Auth::user()->id : 0,

            'cart_code' => auto_code(),

            'cart_status' => 'waiting-payment',

        ];

        if ($data['delivery'] == 'shipping') {

            $database['cart_address'] = $data['address_line1'] ?? '';

            $database['cart_address2'] = $data['address_line2'] ?? '';

            $database['city'] = $data['city_locality'] ?? '';

            $database['province'] = $data['state_province'] ?? '';

            $database['country_code'] = $data['country_code'] ?? '';

            $database['postal_code'] = $data['postal_code'] ?? '';

            $database['company'] = $data['company'] ?? '';

        }

        $respons = Addtocard::create($database);

        $order_id = auto_code('Order', $respons->cart_id);

        $cart = Addtocard::find($respons->cart_id)->update(['cart_code' => $order_id]);

        // insert in Addtocard_Detail

        if ($option_session) {

            foreach ($cart_content as $key => $value) {

                $data_addcard_detal = [

                    'product_id' => $value['id'],

                    'cart_id' => $respons['cart_id'],

                    'user_id' => Theme::find($value['id'])->user_id ?? 0,

                    'name' => $value['title'],

                    'quanlity' => $value['qty'],

                    'subtotal' => $value['subtotal'] ?? ($value['price'] * $value['qty']),

                ];

                Addtocard_Detail::create($data_addcard_detal);

            }

        } else {

            foreach ($cart_content as $key => $value) {

                $data_addcard_detal = [

                    'product_id' => $value->id,

                    'cart_id' => $respons->cart_id,

                    'user_id' => Theme::find($value->id)->user_id ?? 0,

                    'name' => $value->name,

                    'quanlity' => $value->qty,

                    'subtotal' => $value->subtotal,

                ];

                Addtocard_Detail::create($data_addcard_detal);

            }

        }

        $id_post = $respons->cart_id;

        $title = 'Order payment '.$order_id;

        if (Cart::count() || $option_session) {

            $purchase = [

                'amount' => $price,

                'currency' => $this->currency,

                'transactionId' => $id_post,

                'description' => $title,

                'returnUrl' => url('paymentsuccess'),

                'cancelUrl' => url('paymenterror'),

            ];

            if ($price) {

                try {

                    $gateway = $this->gateway;

                    $response = $gateway->purchase($purchase)->send();

                    Cart::destroy();

                    session()->push('order-waiting', $id_post);

                    // dd($response);

                    if ($response->isRedirect()) {

                        $response->redirect(); // this will automatically forward the customer

                    } else {

                        // not successful

                        dd($response);

                        return $response->getMessage();

                    }

                } catch (Exception $e) {

                    return $e->getMessage();

                }

            }

        }

    }

    public function paymentOrder($cart_id)
    {

        $cart = Addtocard::where('cart_id', $cart_id)->first();

        if ($cart && $cart->cart_status == 'waiting-payment') {

            if ($cart->payment_method == 'paypal') {

                $title = 'Order payment '.$cart->cart_code;

                $purchase = [

                    'amount' => $cart->cart_total,

                    'currency' => $this->currency,

                    'transactionId' => $cart->cart_id,

                    'description' => $title,

                    'returnUrl' => url('paymentsuccess'),

                    'cancelUrl' => url('paymenterror'),

                ];

                if ($cart->cart_total) {

                    try {

                        $gateway = $this->gateway;

                        $response = $gateway->purchase($purchase)->send();

                        // Cart::destroy();

                        // dd($response);

                        if ($response->isRedirect()) {

                            $response->redirect(); // this will automatically forward the customer

                        } else {

                            // not successful

                            dd($response);

                            return $response->getMessage();

                        }

                    } catch (Exception $e) {

                        return $e->getMessage();

                    }

                }

            }

        }

    }

    public function payment_success(Request $request)
    {

        $this->localized();

        // Once the transaction has been approved, we need to complete it.

        if ($request->input('paymentId') && $request->input('PayerID')) {

            $transaction = $this->gateway->completePurchase([

                'payer_id' => $request->input('PayerID'),

                'transactionReference' => $request->input('paymentId'),

            ]);

            $response = $transaction->send();

            if ($response->isSuccessful()) {

                // The customer has successfully paid.

                $arr_body = $response->getData();

                // dd($arr_body);

                $cart_id = $arr_body['transactions'][0]['invoice_number'] ?? 0;

                $transaction_total = $arr_body['transactions'][0]['amount']['total'] ?? 0;

                // Insert transaction data into the database

                $isPaymentExist = Addtocard::where('cart_id', $cart_id)->first();

                $date_now = date('Y-m-d H:i:s');

                if ($isPaymentExist) {

                    $isPaymentExist->cart_status = 'success';

                    $isPaymentExist->save();

                    $data = $isPaymentExist->toArray();

                    $data['email_admin'] = setting_option('email_admin');

                    $data['subject_default'] = 'Payment success';

                    Cart::destroy();

                    session()->forget('order-waiting');

                    /*Mail::send('mail.payment',

                        $data,

                        function($message) use ($data) {

                            $message->to($data['email_admin'])

                                ->subject($data['subject_default']."- Order ID: ".$data['cart_code']);

                        }

                    );



                    Mail::send('mail.payment_to_user',

                        $data,

                        function($message) use ($data) {

                            $message->to($data['cart_email'])

                                ->subject($data['subject_default']."- Order ID: ".$data['cart_code']);

                        }

                    );*/

                    $content_success = Page::find(81);

                    $link = '<a href="'.route('cart.view', $isPaymentExist->cart_code).'" title="">'.$isPaymentExist->cart_code.'</a>';

                    $content_success->content = str_replace('{$order_link}', $link, $content_success->content);

                    // dd($content_success);

                    return view('americannail.cart.checkout-success', ['content_success' => $content_success]);

                } else {
                    return view('americannail.cart.checkout-error');
                }

                /*if(!$isPaymentExist){

                    $payment = new Payment;

                    $payment->payment_id = $arr_body['id'];

                    $payment->payer_id = $arr_body['payer']['payer_info']['payer_id'];

                    $payment->payer_email = $arr_body['payer']['payer_info']['email'];

                    $payment->amount = $arr_body['transactions'][0]['amount']['total'];

                    $payment->currency = $this->currency;

                    $payment->payment_status = $arr_body['state'];

                    $post_id = $arr_body['transactions'][0]['invoice_number'];

                    $post_id = explode('-', $post_id)[0];

                    $payment->post_id = $post_id;

                    $payment->type = 'paypal';

                    $payment->save();

                    if($arr_body['state']=='approved'){

                        $post = Dangtin::where("id",$post_id)->first();

                        // $dangtin_checkdate = DangtinCheckDate::where("id_post",$post_id)->where('status', 0)->first();

                        $dangtin_checkdate = DangtinCheckDate::where("id_post",$post_id)->where('status', 0)->orderBy('id','desc')->first();

                        if($dangtin_checkdate){

                            $dangtin_checkdate->status = 1;

                            $dangtin_checkdate->save();

                        }



                        $payment->date_end = $post->date_end;

                        $payment->save();



                        $user = DB::table('users')->where('id', $post->author)->first();

                        $email_admin = setting_option('toemail');

                        $name_admin_email = setting_option('name-admin');

                        $hotline = setting_option('hotline');

                        $subject_default = 'Thanh toán thành công';

                        $data = array(

                            'total_pay'=> $payment->amount,

                            'date_end'=> $post->date_end,

                            'hotline'=> $hotline,

                            'post'=> $post,

                            'currency'=> $payment->currency,

                            'date_now'=>$date_now,

                            'your_name'=>$user->name,

                            'your_email'=>$user->email,

                            'your_mobile'=>$user->phone,

                            'email_admin' => $email_admin,

                            'name_email_admin' => $name_admin_email,

                            'subject_default'=>$subject_default

                        );

                        Mail::send('email.payment',

                            $data,

                            function($message) use ($data) {

                                $message->to($data['email_admin'])

                                    ->subject($data['subject_default']."- Tài khoản: ".$data['your_name']);

                            }

                        );



                        Mail::send('email.payment_to_user',

                            $data,

                            function($message) use ($data) {

                                $message->to($data['your_email'])

                                    ->subject($data['subject_default']."- Tài khoản: ".$data['your_name']);

                            }

                        );



                        $content_success = DB::table('page')->where('id',28)->first();

                        // dd($content_success);

                        return view('dangtin.success', ['content_success'=>$content_success]);

                    }





                }*/

                // return "Payment is successful. Your transaction id is: ". $arr_body['id'];

            } else {

                return $response->getMessage();

            }

        } else {

            return 'Transaction is declined';

        }

    }

    public function payment_error()
    {

        return 'User is canceled the payment.';

    }

    public function paymentStrip_success($id = 0)
    {

        if ($id) {

            $content_success = Page::find(83);

            $link = '<a href="'.route('cart.view', $id).'" title="">'.$id.'</a>';

            $content_success->content = str_replace('{$order_link}', $link, $content_success->content);

            return view('americannail.cart.checkout-success', ['content_success' => $content_success]);

        }

    }
}

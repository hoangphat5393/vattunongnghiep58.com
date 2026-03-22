<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\Frontend\EmailTemplate;
use App\Models\Frontend\ShopOrderStatus, App\Models\Frontend\ShopOrderPaymentStatus;
use App\Models\Frontend\AddToCard, App\Models\Frontend\AddToCardDetail;
use App\Models\Frontend\Product;
// use App\Models\Frontend\Province
use Cart, Auth, Exception;
use Lunaweb\RecaptchaV3\Facades\RecaptchaV3;


class CartController extends Controller
{
    use \App\Traits\LocalizeController;

    public $currency,
        $statusOrder,
        $orderPayment;
    public $data = [
        'error' => false,
        'success' => false,
        'message' => ''
    ];

    public function __construct()
    {
        parent::__construct();
        $this->data['statusOrder']    = ShopOrderStatus::getIdAll();
        $this->data['orderPayment']    = ShopOrderPaymentStatus::getIdAll();
    }

    public function cart()
    {
        $this->localized();

        $this->data['carts'] = Cart::content();
        // $this->data['states'] = Province::get();

        $this->data['seo'] = ['seo_title' => 'Giỏ hàng'];


        // return view($this->templatePath . '.cart.cart', $this->data);
        return view('frontend.cart.cart', $this->data);
    }

    public function addCart()
    {
        $data = request()->all();

        $product = Product::find($data['product']);

        // dd($product);

        // if (!$product) {
        //     return response()->json(
        //         [
        //             'error' => 1,
        //             'msg' => 'Product not found',
        //         ]
        //     );
        // }
        // if ($product->stock < $data['qty']) {
        //     return response()->json(
        //         [
        //             'error' => 2,
        //             'msg' => 'Product out of stock',
        //         ]
        //     );
        // }


        // // Tiến hành giảm giá nếu có;
        // if ($promotion->qty_to_promotion && $promotion_price && $data['qty'] >= $promotion->qty_to_promotion) {
        //     if ($promotion_unit == '%') {
        //         $price = $product->price * $data['qty'] - $promotion_price;
        //     } else {
        //         $price = ($product->price * $data['qty'] * (100 - $promotion_price)) / 100;
        //     }
        // }

        $price = $product->price ?? '0';

        // $form_attr = ['promotion_id' => $data['promotion_id']];
        // dd($promotion, $price);


        // Check product allow for sale
        // if (Cart::get($product->id)) {
        //     dd(123);
        // }

        Cart::add(
            array(
                'id'      => $product->id,
                'name'    => $product->name,
                'qty'     => $data['qty'],
                'price'   => $price,
                'options' => $form_attr ?? []
            )
        );

        // dd(Cart::content());

        // Cart::update($data['rowId'], ['qty' => $data['qty']]);

        return response()->json(
            [
                'error' => 0,
                'count_cart' => Cart::count(),
                // 'view' => view($this->templatePath . '.cart.cart-mini')->render(),
                'msg' => 'Add to cart success',
            ]
        );
    }

    public function updateCarts()
    {
        $data = request()->validate([
            'rowId' => 'required|string',
            'qty' => 'required|numeric|min:1',
        ]);

        Cart::update($data['rowId'], ['qty' => (int) $data['qty']]);

        $carts = Cart::content();

        $subtotal = 0;
        foreach ($carts as $cart) {
            $subtotal += (float) $cart->price * (int) $cart->qty;
        }

        // Dùng cùng partial với trang cart (cart-table + sidebar), không dùng cart-list/cart-mini (App\Product, game.detail → 500).
        return response()->json([
            'error' => 0,
            'count_cart' => Cart::count(),
            'subtotal' => $subtotal,
            'total' => number_format(Cart::total()),
            'view' => view('frontend.cart.cart-table', compact('carts'))->render(),
            'view_sidebar' => view('frontend.cart.includes.cart-sidebar', compact('carts'))->render(),
            'msg' => 'Cập nhật số lượng thành công',
        ]);
    }

    public function removeCarts()
    {
        Cart::destroy();
        return redirect(route('cart'));
    }

    public function removeCart()
    {
        $rowId = request('rowId');
        if ($rowId && array_key_exists($rowId, Cart::content()->toArray())) {
            Cart::remove($rowId);

            $carts = Cart::content();

            return response()->json(
                [
                    'error' => 0,
                    'count_cart' => Cart::count(),
                    'total' => number_format(Cart::total()),
                    'msg' => 'Xóa thành công',
                    // Trang giỏ hàng cần HTML mới để thay thế bảng + sidebar (cart.blade.php)
                    'view' => view('frontend.cart.cart-table', compact('carts'))->render(),
                    'view_sidebar' => view('frontend.cart.includes.cart-sidebar', compact('carts'))->render(),
                ]
            );
        }
        return response()->json(
            [
                'error' => 1,
                'msg' => 'Delete error',
            ]
        );
    }

    public function checkout()
    {
        $this->localized();
        if (Cart::count()) {
            session()->forget('option');

            // dd(Cart::checkout());

            if (session()->has('cart-info')) {
                $data = session()->get('cart-info');
                $this->data["cart_info"] = $data;
            }

            $this->data['seo'] = [
                'seo_title' => 'Đặt hàng',
            ];
            return view('frontend.cart.checkout', $this->data);
        } else
            return $this->cart();
    }

    public function checkPayment($cart_id)
    {
        $this->localized();
        $this->data['cart'] = \App\Models\Frontend\Addtocard::where('cart_id', $cart_id)->first();
        // dd($cart);
        if ($this->data['cart'] && $this->data['cart']['cart_status'] == 'waiting-payment')
            return view('frontend.cart.check-payment', $this->data);
        else
            return redirect(url('/'));
    }


    public function checkoutConfirm(Request $request)
    {
        // $data = $request->all();
        $data_input = $request->input('order', []);

        $score = RecaptchaV3::verify($request->get('g-recaptcha-response'), 'order');

        $this->data['carts'] = Cart::content();

        // dd($score);
        if ($score > 0.7) {
            if ($data_input && Cart::content()->isNotEmpty()) {

                // dd(Cart::content(), $data_input);

                // $mail_customer = EmailTemplate::where('group', 'order_admin')->first();
                // $mail_content = $mail_customer->text;

                $data = array(
                    'fullname' => $data_input['name'],
                    'email' => $data_input['email'],
                    'phone' => $data_input['phone'],
                    'address' => $data_input['address'],
                    'content' => $data_input['content'],
                    'total_price' => Cart::total(),
                );

                // Mail content
                // $dataFind = [
                //     '/\{\{\$name\}\}/',
                //     '/\{\{\$email\}\}/',
                //     '/\{\{\$phone\}\}/',
                //     '/\{\{\$address\}\}/',
                //     '/\{\{\$content\}\}/',
                // ];
                // $mail_content = preg_replace($dataFind, $data, $mail_content);

                // $respons = AddtoCard::updateOrCreate($data);
                $respons = AddToCard::Create($data);
                $id_insert = $respons->id;


                foreach (Cart::content() as $item) {

                    $cart_item = array(
                        'product_id' => $item->id,
                        'price' => $item->price,
                        'quanlity' => $item->qty,
                        'addtocard_id' => $id_insert
                    );
                    AddToCardDetail::Create($cart_item);
                }

                Cart::destroy();

                return redirect()->route('checkout_completed')->with('cart_id', $id_insert);
            }
        } else {
            return view('errors.404');
        }
    }

    public function completed(Request $request)
    {
        $cart = AddToCard::find(session('cart_id'));
        // $cart = AddToCard::find(80);
        // dd($cart);

        if ($cart)
            return view('frontend.checkout.completed', compact('cart'));
        return view('errors.404');
    }


    // CHECK EMAIL EXISTS
    public function checkEmail()
    {
        $this->localized();
        $data = request()->all();
        $user = \App\Models\Frontend\User::where('email', $data['email'])->first();

        if (!empty($user)) {
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
        $user = \App\Models\Frontend\User::where('phone', $data['phone'])->first();

        if (!empty($user)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    // public function forgetCartSession()
    // {
    //     session()->forget('cart_code');
    // }

    // public function success()
    // {
    //     $cart_code = session()->get('cart_code');
    //     if ($cart_code) {
    //         $cart = \App\Models\Addtocard::where('cart_code', $cart_code)->first();

    //         $content_success = \App\Models\Page::find(94);

    //         $link = '<a href="' . route('cart.view', $cart->cart_code) . '" title="">' . $cart->cart_code . '</a>';
    //         $content_success->content = str_replace('{$order_link}', $link, $content_success->content);
    //         // dd($content_success);
    //         $this->data['cart'] = $cart;
    //         $this->data['seo'] = [
    //             'seo_title' => $content_success->title,

    //         ];
    //         $this->data['content_success'] = $content_success;
    //         return view($this->templatePath . '.cart.checkout-success', $this->data);
    //     }
    //     return redirect(url('/'));
    // }


    // public function view($id)
    // {
    //     if ($id) {
    //         // $this->data['order_status'] = $this->orderStatus();
    //         // $this->data['orderPayment'] = $this->orderPayment();
    //         // dd($this->orderPayment);
    //         $this->data['order'] = \App\Models\Addtocard::where('cart_code', $id)->first();
    //         $this->data['order_detail'] = $order_detail = \App\Models\Addtocard_Detail::where('cart_id', $this->data['order']->cart_id)->get();

    //         $total_price = isset($order_detail->total) ? $order_detail->total : 0;

    //         // $data = Addtocard::where('user_id', Auth::user()->id)->where('cart_id', $id_cart)->first();
    //         if ($this->data['order']) {
    //             $this->data['seo'] = [
    //                 'seo_title' => 'Đơn hàng - ' . $this->data['order']->cart_code,

    //             ];
    //             return view($this->templatePath . '.cart.view', $this->data);
    //         } else
    //             return view('errors.404');
    //     }
    // }



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

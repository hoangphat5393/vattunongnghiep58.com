<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Frontend\Order;
use App\Models\Frontend\OrderItem;
use App\Models\Frontend\Product;
use App\Models\Frontend\ShopOrderPaymentStatus;
use App\Models\Frontend\ShopOrderStatus;
use App\Models\Frontend\User;
// use App\Models\Frontend\Province
use App\Models\ProductPrice;
use App\Traits\FrontendDataTransform;
use App\Traits\LocalizeController;
use Auth;
use Cart;
use Illuminate\Http\Request;
use Lunaweb\RecaptchaV3\Facades\RecaptchaV3;

class CartController extends Controller
{
    use FrontendDataTransform;
    use LocalizeController;

    public $currency;

    public $statusOrder;

    public $orderPayment;

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

    public function cart()
    {
        $this->localized();

        $this->hydrateCartViewData();

        $this->data['seo'] = ['seo_title' => 'Giỏ hàng'];

        return view('frontend.cart.cart', $this->data);
    }

    public function addCart()
    {
        $data = request()->validate([
            'product' => ['required', 'integer'],
            'qty' => ['required', 'integer', 'min:1'],
            'product_price_id' => ['nullable', 'integer'],
        ]);

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

        $price = (int) ($product->price ?? 0);
        $options = [];

        if (! empty($data['product_price_id'])) {
            $pp = ProductPrice::where('id', $data['product_price_id'])
                ->where('product_id', $product->id)
                ->where('status', 1)
                ->first();

            if ($pp) {
                $price = (int) $pp->price;
                $options['product_price_id'] = $pp->id;
                $options['price_label'] = $pp->label;
                $options['price_unit'] = $pp->unit;
            }
        }

        // $form_attr = ['promotion_id' => $data['promotion_id']];
        // dd($promotion, $price);

        // Check product allow for sale
        // if (Cart::get($product->id)) {
        //     dd(123);
        // }

        Cart::add(
            [
                'id' => $product->id,
                'name' => $product->name,
                'qty' => $data['qty'],
                'price' => $price,
                'options' => $options,
            ]
        );

        // dd(Cart::content());

        // Cart::update($data['rowId'], ['qty' => $data['qty']]);

        return response()->json(
            [
                'error' => 0,
                'count_cart' => Cart::count(),
                // 'view' => view($this->templatePath . '.cart.cart-mini')->render(),
                'msg' => 'Đã thêm vào giỏ hàng',
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
        $cartItems = $this->transformCartLines($carts);
        $cartSummary = $this->transformCartSummary($cartItems);

        return response()->json([
            'error' => 0,
            'count_cart' => Cart::count(),
            'subtotal' => $cartSummary['subtotal'],
            'total' => number_format(Cart::total()),
            'view' => view('frontend.cart.cart-table', [
                'cart_items' => $cartItems,
                'cart_summary' => $cartSummary,
            ])->render(),
            'view_sidebar' => view('frontend.cart.includes.cart-sidebar', [
                'cart_items' => $cartItems,
                'cart_summary' => $cartSummary,
            ])->render(),
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
            $cartItems = $this->transformCartLines($carts);
            $cartSummary = $this->transformCartSummary($cartItems);

            return response()->json(
                [
                    'error' => 0,
                    'count_cart' => Cart::count(),
                    'total' => number_format(Cart::total()),
                    'msg' => 'Xóa thành công',
                    'view' => view('frontend.cart.cart-table', [
                        'cart_items' => $cartItems,
                        'cart_summary' => $cartSummary,
                    ])->render(),
                    'view_sidebar' => view('frontend.cart.includes.cart-sidebar', [
                        'cart_items' => $cartItems,
                        'cart_summary' => $cartSummary,
                    ])->render(),
                ]
            );
        }

        return response()->json(
            [
                'error' => 1,
                'msg' => 'Lỗi khi xóa sản phẩm khỏi giỏ hàng',
            ]
        );
    }

    public function checkout()
    {
        $this->localized();
        if (Cart::count()) {
            session()->forget('option');

            // dd(Cart::checkout());

            $this->data['cart_info'] = session()->get('cart-info', []);

            $this->data['seo'] = [
                'seo_title' => 'Đặt hàng',
            ];

            $this->hydrateCartViewData();

            return view('frontend.checkout.checkout', $this->data);
        } else {
            return $this->cart();
        }
    }

    public function checkPayment($cart_id)
    {
        $this->localized();
        $this->data['cart'] = Order::where('cart_id', $cart_id)->first();
        // dd($cart);
        if ($this->data['cart'] && $this->data['cart']['cart_status'] == 'waiting-payment') {
            return view('frontend.checkout.check-payment', $this->data);
        } else {
            return redirect(url('/'));
        }
    }

    public function checkoutConfirm(CheckoutRequest $request)
    {
        $data_input = $request->input('order', []);

        $score = $this->recaptchaOrderScore($request);

        $this->data['carts'] = Cart::content();

        if ($score <= 0.7) {
            return redirect()->route('cart.checkout')
                ->withInput()
                ->with('checkout_recaptcha_error', 'Không xác minh bảo mật (reCAPTCHA). Vui lòng tải lại trang và thử lại.');
        }

        if (! $data_input || Cart::content()->isEmpty()) {
            return redirect()->route('cart')
                ->with('checkout_recaptcha_error', 'Giỏ hàng trống hoặc thiếu thông tin. Vui lòng thêm sản phẩm rồi đặt lại.');
        }

        $data = [
            'name' => $data_input['name'],
            'cart_email' => $data_input['email'],
            'cart_phone' => $data_input['phone'],
            'cart_address' => $data_input['address'],
            'cart_note' => $data_input['content'],
            'cart_total' => Cart::total(),
        ];

        if (Auth::check()) {
            $data['user_id'] = Auth::id();
        }

        $respons = Order::Create($data);
        $id_insert = $respons->cart_id;

        foreach (Cart::content() as $item) {
            $productPriceId = data_get($item->options, 'product_price_id');
            $priceLabel = data_get($item->options, 'price_label');
            $priceUnit = data_get($item->options, 'price_unit');

            $cart_item = [
                'product_id' => $item->id,
                'product_price_id' => is_numeric($productPriceId) ? (int) $productPriceId : null,
                'price_label' => is_string($priceLabel) && $priceLabel !== '' ? $priceLabel : null,
                'price_unit' => is_string($priceUnit) && $priceUnit !== '' ? $priceUnit : null,
                'price' => $item->price,
                'quanlity' => $item->qty,
                'cart_id' => $id_insert,
            ];
            OrderItem::Create($cart_item);
        }

        Cart::destroy();

        return redirect()->route('checkout_completed')->with('cart_id', $id_insert)
            ->with('checkout_success', 'Đã ghi nhận thông tin. Chúng tôi sẽ liên hệ bạn sớm để xác nhận đơn hàng.');
    }

    /**
     * reCAPTCHA v3 trên localhost / domain .test thường fail → trước đây trả về view 404 nên người dùng thấy 404 tại /checkout.
     * Nếu chưa cấuậu hình RECAPTCHAV3_SECRET thì bỏ qua (môi trường dev).
     */
    protected function recaptchaOrderScore(Request $request): float
    {
        $secret = config('recaptchav3.secret');
        if ($secret === '' || $secret === null) {
            return 1.0;
        }

        $token = $request->get('g-recaptcha-response');
        if (empty($token)) {
            return 0.0;
        }

        try {
            $score = RecaptchaV3::verify($token, 'order');
        } catch (\Throwable $e) {
            report($e);

            return app()->environment('local') ? 1.0 : 0.0;
        }

        return is_numeric($score) ? (float) $score : 0.0;
    }

    public function completed(Request $request)
    {
        $cart = Order::find(session('cart_id'));
        // $cart = Order::find(80);
        // dd($cart);

        if ($cart) {
            return view('frontend.checkout.completed', compact('cart'));
        }

        return view('errors.404');
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

    public function quickBuyConfirm()
    {
        return redirect()->route('cart.checkout');
    }

    public function legacyCheckoutProcessRedirect()
    {
        return redirect()
            ->route('cart.checkout')
            ->with(
                'checkout_recaptcha_error',
                'Luồng thanh toán cũ đã ngừng. Vui lòng hoàn tất đơn hàng tại trang đặt hàng mới.'
            );
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

    protected function hydrateCartViewData(): void
    {
        $cartItems = $this->transformCartLines(Cart::content());
        $this->data['cart_items'] = $cartItems;
        $this->data['cart_summary'] = $this->transformCartSummary($cartItems);
        $this->data['carts'] = Cart::content();
    }
}

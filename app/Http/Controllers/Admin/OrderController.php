<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Pagination\Paginator;
use App\Http\Controllers\Controller;
use App\Models\Backend\Order, App\Models\Backend\OrderItem, App\Models\Backend\Shipping_order;
use App\Models\Backend\ShopOrderStatus;
use App\Models\Backend\ShopOrderPaymentStatus;
use Auth, DB, File, Image, Config;


class OrderController extends Controller
{
    public $currency,
        $statusOrder,
        $orderPayment;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->statusOrder    = ShopOrderStatus::getIdAll();
        // $this->orderPayment    = ShopOrderPaymentStatus::getIdAll();
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        $appends = [
            'search_name' => request('search_name'),
        ];

        if (Auth::guard('admin')->user()->admin_level == 99999) {

            $db = Order::select('*');

            if (request('search_name') != '') {
                $db->where('name', 'like', '%' . request('search_name') . '%');
            }

            $count_item = $db->count();
            $data_order = $db->orderByDesc('cart_id')->paginate(40)->appends($appends);
        } else {
            $data_order = Order::where('user_id', '=', Auth::guard('admin')->user()->id)
                ->orderByDesc('cart_id')
                ->paginate(40)
                ->appends($appends);
            $count_item = Order::where('user_id', '=', Auth::guard('admin')->user()->id)
                ->count();
        }

        return view('backend.orders.index')->with(['data' => $data_order, 'total_item' => $count_item]);
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function listOrder()
    {
        // $data['order_status'] = $this->orderStatus();
        $data['orderPayment'] = $this->orderPayment;
        $data['statusOrder'] = $this->statusOrder;
        $list = (new Order);
        if (request('cart_status') != '')
            $list = $list->where('cart_status', request('cart_status'));
        if (request('cart_code') != '')
            $list = $list->where('cart_code', request('cart_code'));
        $data['data_order'] = $list->orderBy('cart_id')->paginate(20);
        return view('backend.orders.index', $data);
    }

    public function searchOrder(Request $rq)
    {
        $data_order = Order::select('shop_orders.*')
            ->orderBy('shop_orders.created_at', 'DESC')
            ->paginate(20);
        $query = '';

        if ($rq->search_title != '' && $rq->order_status == '') {
            $data_order = Order::select('shop_orders.*')
                ->where('shop_orders.cart_code', 'LIKE', '%' . $rq->search_title . '%')
                ->orderBy('shop_orders.created_at', 'DESC')
                ->paginate(20);
        } elseif ($rq->search_title == '' && $rq->order_status != '') {
            $data_order = Order::select('shop_orders.*')
                ->where('shop_orders.cart_status', '=', $rq->order_status)
                ->orderBy('shop_orders.created_at', 'DESC')
                ->paginate(20);
        } else {
            $data_order = Order::select('shop_orders.*')
                ->where('shop_orders.cart_code', 'LIKE', '%' . $rq->search_title . '%')
                ->where('shop_orders.cart_status', '=', $rq->order_status)
                ->orderBy('shop_orders.created_at', 'DESC')
                ->paginate(20);
        }

        return view('backend.orders.filter')->with(['data_order' => $data_order]);
    }

    public function createOrder()
    {
        return view('backend.orders.single');
    }

    public function orderDetail($id)
    {
        $data['order_detail'] = Order::where('cart_id', $id)->first();
        if ($data['order_detail']) {
            $data['orderPayment'] = [0 => 'Chưa thanh toán', 1 => 'Đã thanh toán'];
            $data['statusOrder'] = [
                0 => 'Chờ xác nhận',
                1 => 'Đang xử lý',
                2 => 'Hoàn thành',
                3 => 'Đã hủy',
            ];
            return view('backend.orders.single', $data);
        } else {
            return view('404');
        }
    }

    public function postOrderDetail()
    {
        $data = request()->all();
        $cart_id = $data['cart_id'];
        // dd($data);
        //xử lý content
        $content = htmlspecialchars($data['admin_note']);
        $status_order = (int)$data['cart_status'];
        if ($cart_id > 0) {
            //update
            $dataUpdate = array(
                "cart_note" => $content,
                "cart_status" => $status_order,
                "cart_payment" => $data['cart_payment'] ?? 0,
                "shipping_cost" => $data['shipping_cost'],
            );
            $respons = Order::where("cart_id", $cart_id)->update($dataUpdate);
            $msg = "Order has been Updated";
            $url = route('admin.order.detail', array($cart_id));
            msg_move_page($msg, $url);
        }
    }
}

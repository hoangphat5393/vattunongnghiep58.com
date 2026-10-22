<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Order\UpdateOrderDetail;
use App\Models\Backend\Order;
use App\Models\Backend\ShopOrderPaymentStatus;
use App\Models\Backend\ShopOrderStatus;
use Auth;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public $currency;

    public $statusOrder;

    public $orderPayment;

    public function __construct()
    {
        // $this->statusOrder    = ShopOrderStatus::getIdAll();
        // $this->orderPayment    = ShopOrderPaymentStatus::getIdAll();
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        $appends = [
            'search_name' => request('search_name'),
        ];

        if (Auth::guard('admin')->user()->admin_level == 99999) {
            $db = Order::select('*');

            if (request('search_name') != '') {
                $db->where('name', 'like', '%'.request('search_name').'%');
            }

            $count_item = $db->count();
            $data_order = $db->orderByDesc('id')->paginate(40)->appends($appends);
        } else {
            $data_order = Order::where('user_id', '=', Auth::guard('admin')->user()->id)
                ->orderByDesc('id')
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
     * @return Renderable
     */
    public function listOrder()
    {
        $data['orderPayment'] = $this->orderPayment;
        $data['statusOrder'] = $this->statusOrder;
        $list = (new Order);

        $statusFilter = request('status') ?? request('cart_status');
        if ($statusFilter !== null && $statusFilter !== '') {
            $list = $list->where('status', $statusFilter);
        }

        $codeFilter = request('code') ?? request('cart_code');
        if ($codeFilter !== null && $codeFilter !== '') {
            $list = $list->where('code', $codeFilter);
        }

        $data['data_order'] = $list->orderByDesc('id')->paginate(20);

        return view('backend.orders.index', $data);
    }

    public function searchOrder(Request $rq)
    {
        $query = Order::query();

        $searchTitle = $rq->search_title ?? $rq->code;
        $orderStatus = $rq->order_status ?? $rq->status;

        if ($searchTitle != '') {
            $query->where('code', 'LIKE', '%'.$searchTitle.'%');
        }

        if ($orderStatus !== null && $orderStatus !== '') {
            $query->where('status', '=', $orderStatus);
        }

        $data_order = $query->orderBy('created_at', 'DESC')->paginate(20);

        return view('backend.orders.filter')->with(['data_order' => $data_order]);
    }

    public function createOrder()
    {
        return view('backend.orders.single');
    }

    public function orderDetail($id)
    {
        $data['order_detail'] = Order::where('id', $id)->first();
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

    public function postOrderDetail(UpdateOrderDetail $request)
    {
        $data = $request->validated();
        $id = (int) ($data['id'] ?? $data['cart_id'] ?? 0);
        $content = htmlspecialchars($data['admin_note'] ?? '');
        $status_order = (int) ($data['status'] ?? $data['cart_status'] ?? 0);

        if ($id > 0) {
            $dataUpdate = [
                'note' => $content,
                'status' => $status_order,
                'payment_status' => (int) ($data['payment_status'] ?? $data['cart_payment'] ?? 0),
                'shipping_cost' => (float) ($data['shipping_cost'] ?? 0),
            ];
            Order::where('id', $id)->update($dataUpdate);
            $msg = 'Order has been Updated';
            $url = route('admin.order.detail', [$id]);
            msg_move_page($msg, $url);
        }
    }
}

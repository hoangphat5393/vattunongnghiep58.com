@php
    extract($data);

    $avatar = url('theme/images/user-none.png');
    if (auth()->user()->avatar) {
        // $avatar = url('img/users/avatar/' . $user->avatar);
        $avatar = url(auth()->user()->avatar);
    }
    $user = auth()->user();
@endphp

@extends('frontend.layouts.master')

@section('seo')
    @include('frontend.layouts.seo', $seo ?? [])
@endsection

@section('content')
    <section class="py-5 my-post position-relative customer">

        <div class="container">
            <div class="breadcrumbs">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active">My Order</li>
                </ol>
            </div>

            <div class="row justify-content-end">
                <div class="col-lg-7 col-12 mb-4">
                    @if (auth()->check())
                        <div id="talkjs-container" style="width: 100%; margin: 30px 0; height: 600px"></div>
                    @endif
                </div>
                <div class="col-lg-5 col-12">
                    <div class="page-title">
                        <h1>{{ __('Order detail') }} {{ $order->cart_code }}</h1>
                    </div>
                    <link rel="stylesheet" type="text/css" href="{{ asset('public/css/style_customer.css') }}">
                    <div class="infor-shipping">
                        <div class="information-ship">
                            <table class="table table-striped table-my-orders">
                                <tr>
                                    <td style="width: 200px;">Mã đơn hàng:</td>
                                    <td><b>{{ $order->cart_code }}</b></td>
                                </tr>

                                <tr>
                                    <td>{{ __('Order status') }}:</td>
                                    <td>
                                        @if ($order->cart_status == 5)
                                            <span class="badge bg-success badge-shadow">
                                            @else
                                                <span class="badge bg-light badge-shadow">
                                        @endif
                                        {{ $statusOrder[$order->cart_status] ?? 'Chờ xác nhận' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Họ tên:</td>
                                    <td>{{ $order->name }}</td>
                                </tr>

                                <tr>
                                    <td>Điện thoại:</td>
                                    <td>{{ $order->cart_phone }}</td>
                                </tr>
                                <tr>
                                    <td>Email:</td>
                                    <td>{{ $order->cart_email }}</td>
                                </tr>
                                <tr>
                                    <td>Địa chỉ:</td>
                                    <td>{{ $order->cart_address }}</td>
                                </tr>
                                <tr>
                                    <td>Phương thức thanh toán:</td>
                                    <td>
                                        @if (!empty($shop_payment_method[$order->payment_method]))
                                            <div>
                                                {{ $shop_payment_method[$order->payment_method] }}
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Phương thức nhận hàng:</td>
                                    <td>
                                        @if ($order->shipping_type == 'shipping')
                                            <div>Giao hàng nhanh</div>
                                        @else
                                            <div>Nhận sau khi thanh toán thành công</div>
                                        @endif
                                    </td>
                                </tr>
                                @if ($order->shipping_type == 'shipping')
                                    @php
                                        $address_full = implode(', ', array_filter([$order->cart_address, $order->city, $order->province, $order->country_code]));
                                    @endphp
                                    <tr>
                                        <td>Địa chỉ nhận hàng</td>
                                        <td>{{ $address_full }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td>Trạng thái thanh toán:</td>
                                    <td>
                                        @if ($order->cart_payment == 1)
                                            <span class="badge bg-info">{{ $orderPayment[$order->cart_payment] }}</span>
                                        @else
                                            <span class="badge bg-primary">{{ $orderPayment[$order->cart_payment] ?? 'Chưa thanh toán' }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Ghi chú:</td>
                                    <td>{{ $order->cart_note }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <h3 class="order-product-detail mb-2">List products</h3>
                    <div class="myorder-detail">
                        <div class="table-responsive">
                            <?php
                            // $total_price = isset($order_detail->cart_total) ? $order_detail->cart_total : '';
                            
                            $total_price = isset($order_detail->total) ? $order_detail->total : '';
                            
                            $url_img_sp = '/images/product/';
                            $j = 0;
                            $count = 0;
                            $cart_id = 0;
                            $Products = [];
                            $List_cart = '';
                            $bg_child_tb = '';
                            ?>
                            <table class="table table-striped table-my-orders" id="tbl-order-detail">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="30">No</th>
                                        <th class="text-center" width="100">Hình ảnh</th>
                                        <th class="text-center">Tên SP</th>
                                        <th class="text-center">Giá</th>
                                        <th class="text-center">SL</th>
                                        <th class="text-center">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <td colspan="3">&nbsp;</td>
                                        <td colspan="2" style="text-align: right;"><strong>Tổng tiền </strong></td>
                                        <td colspan="2" style="text-align: center;">
                                            <span class="sum_price">
                                                <b>{!! render_price($order->cart_total + $order->shipping_cost) !!}</b>
                                            </span>
                                        </td>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($order_detail as $index => $item)
                                        @php
                                            $product = \App\Product::find($item->product_id);
                                        @endphp
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><img src="{{ asset($product->image) }}" onerror="if (this.src != '/assets/images/no-image.jpg') this.src = '/assets/images/no-image.jpg';" style="width: 70px;" /></td>
                                            <td style="border-left-color: rgb(203, 203, 203);">
                                                <a href="{{ route('shop.detail', $product->slug) }}" target="_blank">{{ $product->name }}</a>
                                            </td>
                                            <td align="center"><span class="price">{!! render_price($item->subtotal / $item->quanlity) !!}</span></td>
                                            <td align="center">
                                                <b>{{ $item->quanlity }}</b>
                                            </td>
                                            <td align="center"><span class="red">{!! render_price($item->subtotal) !!}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        jQuery(document).ready(function($) {
            //chat
            @if (auth()->check())
                Talk.ready.then(function() {
                    var me = new Talk.User({
                        id: '{{ $user->id }}',
                        name: '{{ $user->fullname }}',
                        email: '{{ $user->email }}',
                        photoUrl: '{{ asset($user->avatar) }}',
                        welcomeMessage: 'Hey there! How are you? :-)',

                        role: "buyer"
                    });

                    window.talkSession = new Talk.Session({
                        appId: "{{ config('talkjs.app_id') }}",
                        me: me
                    });

                    var operator = new Talk.User({
                        // just hardcode any user id, as long as your real users don't have this id
                        id: "{{ setting_option('talkjs_id') }}",
                        name: "{{ setting_option('talkjs_name') }}",
                        email: "{{ setting_option('talkjs_email') }}",
                        photoUrl: "{{ setting_option('talkjs_photoUrl') }}",
                        welcomeMessage: "{{ setting_option('talkjs_welcomeMessage') }}",

                        type: "SystemMessage"
                    });

                    // Now, let's start or continue the conversation with the operator and
                    // show the chatbox.

                    // You control the ID of a conversation. In this example, we use the item ID as 
                    // the conversation ID in order to tie this conversation to this item.
                    var conversation = window.talkSession.getOrCreateConversation("order_{{ $order->cart_id }}");
                    conversation.setParticipant(me);
                    conversation.setParticipant(operator);

                    conversation.setAttributes({
                        subject: "{{ $order->cart_code }}",
                        photoUrl: "{{ setting_option('favicon') }}",
                        welcomeMessages: [
                            '{{ __('Order created') }}: <{{ route('customer.myordersdetail', [$order->cart_id]) }}|{{ $order->cart_code }}>'
                        ]
                    });


                    var chatbox = window.talkSession.createChatbox();
                    // chatbox.messageField.setText("Hey, is this item still available?");

                    chatbox.select(conversation);
                    chatbox.mount(document.getElementById('talkjs-container'));
                });
            @endif
            //chat
        });
    </script>
@endpush

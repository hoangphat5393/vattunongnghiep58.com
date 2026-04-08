@extends($templatePath .'.layouts.index')



@section('seo')

@include($templatePath .'.layouts.seo', $seo??[] )

@endsection



@section('content')

<section class="py-5 my-post customer">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-9 col-12">



            <div class="page-title">

                <h1>Chi tiết đơn hàng {{ $order->cart_code }}</h1>

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

                            <td>Trạng thái đơn hàng:</td>

                            <td>

                                @if($order->cart_status == 5)

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

                                @if(!empty($shop_payment_method[$order->payment_method]))

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

            <h4 class="order-product-detail my-2 mt-4">Danh sách sản phẩm</h4>

            <div class="myorder-detail">          

                <div class="table-responsive">          

                <?php

                    // $total_price = isset($order_detail->cart_total) ? $order_detail->cart_total : '';

                    

                    $total_price = isset($order_detail->total) ? $order_detail->total : '';

                    

                        $url_img_sp='/images/product/';

                        $j=0;

                        $count=0;

                        $cart_id=0;

                        $Products=array();

                        $List_cart="";

                        $bg_child_tb="";

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

                                <td colspan="2" style="text-align: right;"><strong>Vận chuyển</strong></td>

                                <td colspan="2" style="text-align: right;">

                                    <span class="sum_price"> 

                                        <b>{!! render_price($order->shipping_cost) !!}</b>

                                    </span>

                                </td>

                            </tr>

                            <tr>

                                <td colspan="3">&nbsp;</td>

                                <td colspan="2" style="text-align: right;"><strong>Tổng tiền </strong></td>

                                <td colspan="2" style="text-align: right;">

                                    <span class="sum_price"> 

                                        <b>{!! render_price( $order->cart_total + $order->shipping_cost) !!}</b>

                                    </span>

                                </td>

                            </tr>

                        </tfoot>

                        <tbody>

                            @foreach($order_detail as $index => $item)

                            @php

                                $product = \App\Models\Frontend\Product::find($item->product_id);
                            @endphp

                            <tr>

                                <td>{{ $index+1 }}</td>

                                <td><img src="{{ asset($product->image) }}" onerror="if (this.src != '/assets/images/no-image.jpg') this.src = '/assets/images/no-image.jpg';" style="width: 70px;"/></td>

                                <td style="border-left-color: rgb(203, 203, 203);">

                                    <a href="{{ route('shop.detail', $product->slug) }}" target="_blank">{{ $product->name }}</a>

                                </td>

                                <td align="center"><span style="color:#F00;">{!! render_price($item->subtotal / $item->quanlity) !!}</span></td>

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

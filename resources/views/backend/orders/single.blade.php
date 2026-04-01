@extends('backend.layouts.master')
@section('seo')
    <?php
    $data_seo = [
        'title' => 'Order Detail: ' . $order_detail->cart_code . ' | E-Bike Dashboard',
        'keywords' => setting_option('seo-keywords-add'),
        'description' => setting_option('seo-description-add'),
        'og_title' => 'Order Detail: ' . $order_detail->cart_code . ' | E-Bike Dashboard',
        'og_description' => setting_option('seo-description-add'),
        'og_url' => Request::url(),
        'og_img' => asset('assets/images/logo_seo.png'),
        'current_url' => Request::url(),
        'current_url_amp' => '',
    ];
    $seo = WebService::getSEO($data_seo);
    
    $total_price = isset($order_detail->cart_total) ? $order_detail->cart_total : '';
    $cart_content_cart = unserialize($order_detail->cart_content);
    $order_products = \App\Models\Backend\OrderItem::where('cart_id', $order_detail->cart_id)->get();
    ?>
    @include('backend.partials.seo')
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Order Detail: {{ $order_detail->cart_code }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Order Detail: {{ $order_detail->cart_code }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <div class="app-content">
        <div class="container-fluid">
            <form action="{{ route('admin.order.update', [$order_detail->cart_id]) }}" method="POST" id="frm-order-detail">
                @csrf
                <div class="row">
                    <input type="hidden" name="cart_id" value="{{ $order_detail->cart_id }}">
                    <input type="hidden" name="cart_code" value="{{ $order_detail->cart_code }}">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Thông tin khách hàng</h3>
                            </div> <!-- /.card-header -->
                            <div class="card-body p-0">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td style="width: 200px;">Mã đơn hàng:</td>
                                            <td>{{ $order_detail->cart_code }}</td>
                                        </tr>

                                        <tr>
                                            <td>Họ tên:</td>
                                            <td>{{ $order_detail->name }}</td>
                                        </tr>

                                        <tr>
                                            <td>Điện thoại:</td>
                                            <td>{{ $order_detail->cart_phone }}</td>
                                        </tr>
                                        <tr>
                                            <td>Email:</td>
                                            <td>{{ $order_detail->cart_email }}</td>
                                        </tr>
                                        <tr>
                                            <td>Địa chỉ:</td>
                                            <td>{{ $order_detail->cart_address }}</td>
                                        </tr>
                                        <tr>
                                            <td>Phương thức thanh toán:</td>
                                            <td>
                                                @if ($order_detail->payment_method == 'cash')
                                                    <div>- Thanh toán bằng tiền mặt khi nhận hàng</div>
                                                @elseif($order_detail->payment_method == 'bank_transfer')
                                                    <div class="mb-3">- @lang('bank_transfer')</div>
                                                    {!! htmlspecialchars_decode(setting_option('banks')) !!}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Phương thức nhận hàng:</td>
                                            <td>
                                                @if ($order_detail->shipping_type == 'shipping')
                                                    <div>Giao hàng nhanh</div>
                                                @else
                                                    <div>Nhận hàng tại cửa hàng:</div>
                                                    <div class="mt-3">{!! htmlspecialchars_decode(setting_option('pickup_address')) !!}</div>
                                                @endif
                                            </td>
                                        </tr>
                                        @if ($order_detail->shipping_type == 'shipping')
                                            @php
                                                $address_full = implode(', ', array_filter([$order_detail->cart_address, $order_detail->city, $order_detail->province, $order_detail->country_code]));
                                            @endphp
                                            <tr>
                                                <td>Địa chỉ nhận hàng</td>
                                                <td>{{ $address_full }}</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td>Trang thái thanh toán:</td>
                                            <td>
                                                @if ($order_detail->cart_payment == 1)
                                                    <span class="badge bg-info">{{ $orderPayment[$order_detail->cart_payment] }}</span>
                                                @else
                                                    <span class="badge bg-primary">{{ $orderPayment[$order_detail->cart_payment] ?? 'Chưa thanh toán' }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Phí vận chuyển:</td>
                                            <td>
                                                {!! render_price($order_detail->shipping_cost) !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Ghi chú:</td>
                                            <td>{{ $order_detail->cart_note }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div> <!-- /.card-body -->
                        </div><!-- /.card -->
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Chi tiết đơn hàng</h3>
                            </div> <!-- /.card-header -->
                            <div class="card-body p-0">
                                @if ($order_products->count())
                                    <table class="table table-striped" id="tbl-order-detail">
                                        <thead>
                                            <tr>
                                                <th>STT</th>
                                                <th>Tên sản phẩm</th>
                                                <th>Hình ảnh</th>
                                                <th>Giá</th>
                                                <th>Số lượng</th>
                                                <th>Thành tiền</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            @if ($order_detail->shipping_cost)
                                                <tr>
                                                    <td colspan="3">&nbsp;</td>
                                                    <td colspan="2">Phí ship</td>
                                                    <td colspan="1">
                                                        <div class="fee_ship">{!! render_price($order_detail->shipping_cost) !!}</div>
                                                    </td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td colspan="3">&nbsp;</td>
                                                <td colspan="2"><strong>Tổng tiền</strong></td>
                                                <td colspan="1">
                                                    <div><span class="sum_price">{!! render_price($total_price + $order_detail->shipping_cost) !!}</span> </div>
                                                </td>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            @foreach ($order_products as $key => $order_item)
                                                @php
                                                    $product = \App\Product::find($order_item->product_id);
                                                @endphp
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td style="border-left-color: rgb(203, 203, 203);">
                                                        <a href="{{ route('shop.detail', $product->slug) }}" target="_blank">{{ $product->name }}</a><br />

                                                    </td>
                                                    <td>
                                                        @if ($product->image)
                                                            <img src="{{ asset($product->image) }}" height="50" />
                                                        @endif
                                                    </td>
                                                    <td align="center">
                                                        <span style="color:#F00;">{!! render_price($order_item->subtotal / $order_item->quanlity) !!}</span>
                                                    </td>
                                                    <td align="center">
                                                        <b>{{ $order_item->quanlity }}</b>
                                                    </td>
                                                    <td align="center"><span class="red">{!! render_price($order_item->subtotal) !!}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div> <!-- /.card-body -->
                        </div><!-- /.card -->
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title mb-0">Dành cho quản trị viên cập nhật đơn hàng</h3>
                            </div> <!-- /.card-header -->
                            <div class="card-body p-0">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td>Phí vận chuyển:</td>
                                            <td>
                                                <input type="number" name="shipping_cost" value="{{ $order_detail->shipping_cost }}" class="form-control">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Thanh toán:</td>
                                            <td>
                                                <select name="cart_payment" class="form-control">
                                                    @foreach ($orderPayment as $key => $item)
                                                        <option value="{{ $key }}" {{ $order_detail->cart_payment == $key ? 'selected' : '' }}>{{ $item }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Tình trạng</td>
                                            <td>
                                                <select name="cart_status" class="form-control">
                                                    @foreach ($statusOrder as $key => $item)
                                                        <option value="{{ $key }}" {{ $order_detail->cart_status == $key ? 'selected' : '' }}>{{ $item }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Ghi chú:</td>
                                            <td>
                                                <textarea id="admin_note" name="admin_note">{!! htmlspecialchars_decode($order_detail->cart_excerpt) !!}</textarea>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="2" style="text-align: right;">
                                                <input type="submit" name="btn_submit_order" class="btn btn-success" value="Cập nhật đơn hàng">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div> <!-- /.card-body -->
                        </div><!-- /.card -->
                    </div> <!-- /.col -->
                </div> <!-- /.row -->
            </form>
        </div> <!-- /.container-fluid -->
    </div>
    <script>
        $(function() {
            editorQuote('admin_note');
        })
    </script>
@endsection

@extends('frontend.layouts.master')

@section('seo')
@endsection


@section('content')
    <main id="checkout_complete" class="main">

        {{-- Menu --}}
        @include('frontend.includes.menu')

        {{-- Page Header Start --}}
        {{-- <div class="container-fluid page-header py-5 wow fadeIn" data-wow-delay="0.1s">
            <div class="container text-center">
                <h4 class="animated slideInDown mb-3 fw-bold">Hoàn tất đặt hàng</h4>
                <nav aria-label="breadcrumb animated slideInDown">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">@lang('Home')</a></li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('Contact completed')</li>
                    </ol>
                </nav>
            </div>
        </div> --}}
        {{-- Page Header End --}}

        {{-- Contact Start --}}
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-7">
                    <img src="{{ asset('images/icon-success.png') }}" class="img-fluid mx-auto d-block animated slideInDown " alt="Đặt hàng thành công" style="width: 80px">
                    <h3 class="animated slideInDown fw-bold text-center mt-4 mb-5">Đặt hàng thành công</h3>
                </div>
                <div class="col-md-8">
                    <div class="block-warp p-4">

                        <div class="mb-3">
                            <p>Chào anh/chị <span class="fw-700">{{ $cart->fullname }}</span></p>
                            <p>Chúc mừng anh/chị đã đặt hàng thành công tại <span class="fw-700 text-success">{{ setting_option('webtitle') }}</span></p>
                            <dl class="row">
                                <dt class="col-sm-3 fw-400">Mã đơn hàng:</dt>
                                <dd class="col-sm-9 fw-700">{{ $cart->id }}</dd>

                                <dt class="col-sm-3 fw-400">Tổng thanh toán</dt>
                                <dd class="col-sm-9 fw-700 text-red">
                                    {{ number_format($cart->total_price, 0, ',', '.') }} đ
                                </dd>

                                <dt class="col-sm-3 fw-400">Tình trạng</dt>
                                <dd class="col-sm-9 fw-700 text-red">
                                    @switch($cart->status)
                                        @case(0)
                                            Chưa thanh toán
                                        @break

                                        @case(1)
                                            Đã thanh toán
                                        @break

                                        @default
                                            Đang xử lý
                                    @endswitch
                                </dd>
                            </dl>

                            <hr>

                            <p>
                                Cảm ơn bạn đã tin tưởng và giao dịch tại <span class="fw-700">{{ setting_option('website') }}</span><br>
                                Cửa hàng sẽ liên hệ trong thời gian sớm nhất để xác nhận đơn hàng
                            </p>

                        </div>


                        @if ($cart->items()->exists())
                            <div id="collapseExample" class="row cart-detail">
                                <div class="col-md-12 pt-sm-3">

                                    <div class="table-container">
                                        <div class="table-responsive">
                                            <table class="table table-hover vcenter">
                                                <thead>
                                                    <tr>
                                                        <th scope="col"></th>
                                                        <th scope="col">Sản phẩm</th>
                                                        <th scope="col" class="text-center">Số lượng</th>
                                                        <th scope="col" class="text-center">Giá</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($cart->items as $item)
                                                        @php
                                                            $product = $item->product;
                                                        @endphp
                                                        <tr class="cart-items">
                                                            <td class="cart-thumb">
                                                                {{-- <a class="flex-shrink-0" href="{{ route('product.detail', [$product->slug, $product->id]) }}" title="Ớt Sừng Vàng Lai F1 Phi Châu">
                                                                </a> --}}
                                                                <img class="img-fluid item-image d-block mx-auto" src="{{ $product->image }}" alt="Ớt Sừng Vàng Lai F1 Phi Châu" width="70">
                                                            </td>
                                                            <td style="width: 250px">
                                                                {{-- <a class="flex-shrink-0 item-name" href="https://vattunongnghiep58.com.test/product/ot-sung-vang-lai-f1-phi-chau-7.html" title="Ớt Sừng Vàng Lai F1 Phi Châu">Ớt Sừng Vàng Lai F1 Phi Châu</a> --}}
                                                                {{ $product->name }}
                                                            </td>
                                                            <td>
                                                                <div class="mx-auto mx-sm-0 text-center text-sm-start" style="max-width: 9rem;">
                                                                    {{ $item->quanlity }}
                                                                </div>
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="text-accent">{{ number_format($item->quanlity * $product->price, 0, ',', '.') }} đ</div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="d-flex justify-content-around">
                                    <div class="return-btn">
                                        <a href="{{ route('index') }}" class="btn btn-success">Tiếp tục mua hàng</a>
                                    </div>
                                    <div class="">
                                        {{-- <button class="btn btn-danger" data-bs-toggle="collapse" data-bs-target="#collapseExample">Chi tiết đơn hàng</button> --}}
                                        <button class="btn btn-danger" id="toggleButton">Chi tiết đơn hàng</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>





        </div>
        {{-- Contact End --}}
    </main>
@endsection


@push('scripts')
    <script>
        $('#toggleButton').on('click', function() {
            // $('#collapseExample').slideToggle();
            $('#collapseExample').toggleClass('show');
        });
    </script>
@endpush

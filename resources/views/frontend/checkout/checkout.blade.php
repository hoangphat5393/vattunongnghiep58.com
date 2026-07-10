@extends('frontend.layouts.master')
@section('seo')
    @include('frontend.layouts.seo', $seo ?? [])
@endsection

@section('content')
    @include('frontend.includes.menu')

    <div class="container mx-auto px-4 py-4">
        <div class="text-sm text-gray-500 flex items-center gap-2 flex-wrap">
            <a href="{{ route('index') }}" class="hover:text-leaf-600">Trang chủ</a>
            <span>/</span>
            <a href="{{ route('cart') }}" class="hover:text-leaf-600">Giỏ hàng</a>
            <span>/</span>
            <span class="text-leaf-700 font-bold">Đặt hàng</span>
        </div>
    </div>

    <div class="bg-leaf-50 grow pb-12">
        <div class="container mx-auto px-4 py-6 md:py-8">
            @if (session('checkout_recaptcha_error'))
                <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900" role="alert">
                    {{ session('checkout_recaptcha_error') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-2">Xác nhận đơn hàng</h1>
            <p class="text-gray-600 text-sm md:text-base mb-6 md:mb-8 max-w-2xl">Điền thông tin liên hệ bên dưới. Chúng tôi sẽ gọi điện xác nhận đơn và hướng dẫn giao hàng — không cần chọn thanh toán trực tuyến.</p>

            <input type="hidden" id="checkout_cart_total_ref" value="{{ Cart::total(2) }}" data-origin="{{ Cart::total(2) }}">

            <div class="flex flex-col lg:flex-row gap-6 lg:gap-8 carts-content checkout-content">
                <div class="lg:w-2/3 space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-leaf-100 overflow-hidden">
                        <div class="px-4 py-3 md:px-6 md:py-4 border-b border-leaf-100 bg-leaf-50/80">
                            <h2 class="font-bold text-lg text-gray-900">Đơn hàng của bạn</h2>
                            <p class="text-sm text-gray-500 mt-0.5">Kiểm tra sản phẩm trước khi xác nhận</p>
                        </div>
                        <div class="cart-table-include">
                            @include('frontend.cart.includes.checkout_cart_item')
                        </div>
                    </div>

                    @include('frontend.cart.includes.customer-info', ['showPayment' => false, 'contactCheckout' => true])

                    <div class="msg-error mb-3 hidden rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"></div>
                </div>

                <aside class="lg:w-1/3 checkout-sidebar-include">
                    <div class="bg-white rounded-2xl shadow-sm border border-leaf-100 p-6 lg:sticky lg:top-24">
                        <h3 class="font-bold text-xl text-gray-900 mb-6 border-b border-gray-100 pb-4">Cộng đơn hàng</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between text-gray-700">
                                <span>Tạm tính</span>
                                <span class="font-bold">{!! render_price($cart_summary['subtotal'], 'VND') !!}</span>
                            </div>
                            <div class="flex justify-between text-gray-700">
                                <span>Phí vận chuyển</span>
                                <span class="font-bold text-leaf-600">Miễn phí</span>
                            </div>
                            <div class="pt-4 border-t border-gray-100">
                                <div class="flex justify-between items-center gap-2">
                                    <span class="font-bold text-lg text-gray-900">Tổng cộng</span>
                                    <span class="text-2xl font-extrabold text-leaf-600">{!! render_price($cart_summary['total'], 'VND') !!}</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1 text-right">Đã bao gồm VAT (nếu có)</p>
                            </div>
                        </div>

                        <button type="submit" form="checkout_form" class="submit-confirm mt-6 hidden w-full cursor-pointer bg-leaf-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-leaf-500/30 hover:bg-leaf-700 transition transform hover:-translate-y-1 lg:flex lg:w-full lg:items-center lg:justify-center lg:gap-2">
                            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span>Gửi thông tin liên hệ</span>
                        </button>

                        <a href="{{ route('cart') }}" class="mt-4 flex items-center justify-center gap-2 text-sm font-semibold text-gray-600 hover:text-leaf-700">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Quay lại giỏ hàng
                        </a>

                        <div class="mt-6 pt-4 border-t border-gray-100">
                            <div class="flex items-center gap-2 text-gray-500 text-sm justify-center">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                <span>Thông tin được mã hóa an toàn</span>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
@endsection

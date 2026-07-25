@extends('americannail.layouts.index')
@php

@endphp
@section('content')
    <div id="page-content" class="page-template page-checkout">
        <!--Page Title-->
        <div class="page section-header text-center">
            <div class="page-title">
                <div class="wrapper">
                    <h1 class="page-width"><i class="fa fa-exclamation-circle"></i> Đang chờ thanh toán</h1>
                </div>
            </div>
        </div>
        <div class="container py-5">
            <p class="text-center">Vui lòng thanh toán số tiền <b>{{ render_price($cart->cart_total) }}</b> cho đơn hàng <b>{{ $cart->cart_code }}</b></p>
            <div class="text-center">
                <a href="{{ url('/') }}" class="btn btn-info">Trang chủ</a>
                <a href="{{ route('payment.order', $cart->cart_id) }}" class="btn btn-primary">Thanh toán đơn hàng</a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush

@extends('americannail.layouts.index')
@php

@endphp
@section('content')
    <div id="page-content" class="page-template page-checkout">
        <!--Page Title-->
        <div class="page section-header text-center">
            <div class="page-title">
                <div class="wrapper">
                    <h1 class="page-width"><i class="fa fa-exclamation-circle"></i> @lang('Waiting for payment')</h1>
                </div>
            </div>
        </div>
        <div class="container py-5">
            {{-- <p class="text-center">{!! __('order_payment_warning', ['total' => render_price($cart->cart_total), 'id' => $cart->cart_code]) !!}</p> --}}
            <p class="text-center">Please pay <b>{{ render_price($cart->cart_total) }}</b> order <b>{{ $cart->cart_code }}</b></p>
            <div class="text-center">
                <a href="{{ url('/') }}" class="btn btn-info">@lang('Home')</a>
                <a href="{{ route('payment.order', $cart->cart_id) }}" class="btn btn-primary">@lang('Payment order')</a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush

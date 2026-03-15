@extends('frontend.layouts.master')
@section('seo')
@include($templatePath . '.layouts.seo', $seo ?? [])
@endsection

@push('head-style')
<link rel="stylesheet" href="{{ url($templateFile . '/css/cart.css') }}">
<style>
    .msg-error {
        color: #f00;
    }
</style>
@endpush

@php
$carts = Cart::content();
$variable_group = App\Models\Variable::where('status', 0)
->where('parent', 0)
->orderBy('stt', 'asc')
->pluck('name', 'id');

$states = \App\Models\Province::get();

if (Auth::check()) {
extract(
auth()
->user()
->toArray(),
);
}
@endphp

@section('content')
<div class="carts-content checkout-content">
    <div class="cart_bg">
        <div class="container pt-4 pb-3 py-sm-4">
            <nav class="breadcrumbs my-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a class="text-nowrap" href="/"><i class="ci-home"></i>Home</a>
                    </li>
                    <li class="breadcrumb-item text-nowrap">
                        <a href="{{ route('cart') }}">Cart</a>
                    </li>
                    <li class="breadcrumb-item text-nowrap active" aria-current="page">Checkout</li>
                </ol>
            </nav>

            <div class="rounded-3 shadow-lg mt-4 mb-5">
                {{-- <ul class="nav nav-tabs nav-justified mb-sm-4">
                        <li class="nav-item"><a class="nav-link fs-lg fw-medium py-4" href="{{ route('cart') }}">1. Giỏ hàng</a></li>
                <li class="nav-item"><a class="nav-link fs-lg fw-medium py-4 active" href="{{ route('cart.checkout') }}">2. Đặt hàng</a></li>
                </ul> --}}
                <form class="needs-validation px-sm-3 pt-sm-1 pb-4 pb-sm-5" action="{{ route('cart.checkout.confirm') }}" method="post" id="form-checkout">
                    @csrf()
                    <input type="hidden" name="shipping_cost" value="0">
                    <input type="hidden" name="cart_total" value="{{ Cart::total(2) }}" data-origin="{{ Cart::total(2) }}">
                    <input type="hidden" name="res_token" id="res_token" value="">

                    <div class="row billing-fields">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 pt-4 pb-3 customer_info">

                            {{-- SHIPPING INFO --}}
                            @include($templatePath . '.cart.includes.cart-shipping')

                            {{-- CUSTOMER INFO --}}
                            <div class="create-ac-content bg-light-gray">

                                @include($templatePath . '.cart.includes.customer-info')

                                <div class="msg-error mb-3" style="display: none;"></div>
                                <div class="cart-btn">
                                    {{-- <button type="button" class="btn btn-info get_shipping_usps" title="Continue to shipping">Continue to shipping</button> --}}
                                    {{-- <a href="{{ url('cart') }}" class="btn btn-sm btn-info mt-3" title="Continue to shipping"><i class="fas fa-arrow-left"></i>&nbsp; BACK TO CART</a> --}}
                                    <button class="btn btn-primary btn-checkout d-block w-100 mt-3 submit-confirm" value="Place order" type="button">CHECKOUT</button>
                                </div>
                            </div>

                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="your-order-payment">
                                {{-- CART TABLE --}}
                                @include($templatePath . '.cart.includes.checkout_cart_item')

                                {{-- PAYMENT METHOD --}}
                                @include($templatePath . '.cart.includes.payment-method')
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection



@push('after-footer')
<script type="application/javascript" src="https://checkout.stripe.com/checkout.js"> </script>
<script src="{{ asset('/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset($templateFile . '/js/cart.js?ver=' . time()) }}"></script>

<script>
    var strip_key = '{{ config('
    services.stripe ')['
    key '] }}';
    jQuery(document).ready(function($) {
        $('input[name="delivery"]:checked').parent().find('.ship-content').show();
        $('input[name="payment_method"]:checked').parent().find('.payment-content').show();

        $('input[name="payment_method"]').on('change', function() {
            $('.payment-content').hide();
            $(this).parent().find('.payment-content').show();
        });

        $('input[name="delivery"]').on('change', function() {
            $('.ship-content').hide();
            $(this).parent().find('.ship-content').show();
            var val = $(this).val();
            $('.delivery_content').hide();
            $('.' + val + '_content').show();
            if (val == 'pick_up') {
                $('.get_shipping_cost').hide();
                $('.submit-checkout').show();
                $('.shipping_cost').text(0);
                $('.cart_total').html('{!! render_price(Cart::total(2)) !!}');
                $('input[name="cart_total"]').val('{{ Cart::total(2) }}');
            } else {
                $('.get_shipping_cost').show();
                $('.submit-checkout').hide();
                $('.shipping_cost').text('Calculated at next step');
            }
        });


        $(document).on('change', '.shipping-list input', function() {
            var price = $(this).val();
            var total = $('input[name="cart_total"]').data('origin');

            $('.shipping_cost').text('$' + price);
            $('input[name="shipping_cost"]').val(price);

            total = parseFloat(total) + parseFloat(price);
            $('.cart_total').text('$' + total);
            $('input[name="cart_total"]').val(total);
        });
    });
</script>
@endpush
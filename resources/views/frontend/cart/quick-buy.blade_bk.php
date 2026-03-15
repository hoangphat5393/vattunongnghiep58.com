@extends('frontend.layouts.master')

@section('seo')
@include($templatePath . '.layouts.seo', $seo ?? [])
@endsection

@php
$carts = Cart::content();
$variable_group = App\Models\Variable::where('status', 0)
->where('parent', 0)
->orderBy('stt', 'asc')
->pluck('name', 'id');
$states = \App\Models\Province::orderBy('name', 'ASC')->get();

if (Auth::check()) {
extract(
auth()
->user()
->toArray(),
);
}

$option = session()->get('option');
if (is_array($option)) {
$option = json_decode($option[0], true);
}

$price_total = $option['price'] ?? $product->price;

$total = $price_total * $option['qty'] - $option['options']['promotion'];
// dd($option);
@endphp

@section('content')
<div id="page-content" class="page-template page-checkout checkout-content">
    <div class="cart_bg">
        <div class="container pt-4 pb-3 py-sm-4">
            {{-- Page Title --}}
            <div class="page section-header text-center">
                <div class="page-title">
                    <div class="wrapper">
                        <h3 class="page-width mb-5">Confirm order</h3>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('quick_buy.checkout.confirm') }}" method="post" id="form-checkout" class="px-sm-3 pt-sm-1 pb-4 pb-sm-5">
                @csrf()
                <input type="hidden" name="shipping_cost" value="0">
                <input type="hidden" name="cart_total" value="{{ $total }}" data-origin="{{ $total }}">
                <input type="hidden" name="res_token" id="res_token" value="">
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <div class="container">
                    <div class="row billing-fields">

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 pt-4 pb-3 customer_info">
                            {{-- SHIPPING INFO --}}
                            @include($templatePath . '.cart.includes.cart-shipping')

                            {{-- CUSTOMER INFO --}}
                            <div class="create-ac-content bg-light-gray">

                                @include($templatePath . '.cart.includes.customer-info')

                                <div class="msg-error mb-3" style="display: none;"></div>
                                <div class="cart-btn">
                                    <button class="btn btn-primary btn-checkout d-block w-100 mt-3 submit-confirm" value="Place order" type="button">CHECKOUT</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="your-order-payment">
                                @include($templatePath . '.cart.includes.quick_buy_cart_item')

                                @include($templatePath . '.cart.includes.payment-method', ['payment_method' => $cart_info['payment_method'] ?? ''])
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection

@push('head-style')
<link rel="stylesheet" href="{{ url($templateFile . '/css/cart.css') }}">
<style>
    .msg-error {
        color: #f00;
    }
</style>
@endpush
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
                $('.cart_total').html('{!! render_price($total) !!}');
                $('input[name="cart_total"]').val('{{ $total }}');
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
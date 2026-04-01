@extends('frontend.layouts.master')

@section('seo')
    @include('frontend.layouts.seo', $seo ?? [])
@endsection

@php
    extract($data);
    $carts = Cart::content();
    $variable_group = App\Models\Variable::where('status', 0)->where('parent', 0)->orderBy('stt', 'asc')->pluck('name', 'id');

    $states = \App\Models\State::get();

    if (Auth::check()) {
        extract(auth()->user()->toArray());
    }

    $option = session()->get('option');
    if (is_array($option)) {
        $option = json_decode($option[0], true);
    }

    $price_total = $option['price'] ?? $product->price;
    $total = $price_total * $option['qty'];

@endphp
@section('content')
    <div class="carts-content checkout-content">
        <div class="cart_bg">
            <div class="container pt-4 pb-3 py-sm-4">
                <div class="breadcrumbs mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item">Quick Buy</li>
                    </ol>
                </div>

                <form action="{{ route('cart_checkout.process') }}" method="post" id="form-checkout">
                    @csrf()
                    <input type="hidden" name="shipping_cost" value="{{ $data_shipping['shipping_cost'] }}">
                    <input type="hidden" name="cart_total" value="{{ $total + $data_shipping['shipping_cost'] }}" data-origin="{{ $total }}">
                    <input type="hidden" name="res_token" id="res_token" value="">

                    <div class="row billing-fields">
                        <div class="col-12 col-lg-8 pt-sm-3">
                            @include($templatePath . '.cart.includes.quick_buy_cart_item')

                            @include($templatePath . '.cart.includes.payment-method', ['payment_method' => $payment_method ?? ''])

                            {{-- CUSTOMER INFO --}}
                            <div class="create-ac-content bg-light-gray">

                                @include($templatePath . '.cart.includes.customer-info', ['showPayment' => false])

                                <div class="msg-error mb-3" style="display: none;"></div>
                                <div class="cart-btn">
                                    <button class="btn btn-primary d-block w-100 mt-3 submit-checkout" value="Place order" type="button">@lang('CHECKOUT')</button>
                                    <!-- <button class="btn btn-primary btn-checkout d-block w-100 mt-3 submit-confirm" value="Place order" type="button">CHECKOUT</button> -->
                                </div>
                            </div>
                        </div>

                        {{-- SIDEBAR --}}
                        <div class="col-12 col-lg-4 pt-sm-4 pt-md-3">
                            <div class="cart-total bdr">
                                <p class="heading">CART TOTAL</p>
                                <div class="body">
                                    <div class="mb-3 pb-3 into-money">
                                        <div class="d-flex justify-content-between">
                                            <p class="subtotal text-uppercase">Total:</p>
                                            <p class="subtotal">
                                                {!! render_price($total) !!}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- END SIDEBAR --}}
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
    <script type="application/javascript" src = "https://checkout.stripe.com/checkout.js" > </script>
    <script src="{{ asset($templateFile . '/js/cart.js?ver=' . time()) }}"></script>
    <script>
        var strip_key = '{{ config('services.stripe')['key'] }}';
        jQuery(document).ready(function($) {
            $(document).on('change', '.shipping-list input', function() {
                var price = $(this).val().split('__')[0];
                var total = $('input[name="cart_total"]').data('origin');


                $('.shipping_cost').text('$' + price);
                $('input[name="shipping_cost"]').val(price);

                total = parseFloat(total) + parseFloat(price);
                $('.cart_total').text('$' + total);
                $('input[name="cart_total"]').val(total);
            });
        });

        window.addEventListener("pageshow", function(event) {
            var historyTraversal = event.persisted ||
                (typeof window.performance != "undefined" &&
                    window.performance.navigation.type === 2);
            if (historyTraversal) {
                window.location.reload();

            }
        });
    </script>
@endpush

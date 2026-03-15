@extends('frontend.layouts.master')
@section('seo')
    @include('frontend.layouts.seo', $seo ?? [])
@endsection

@inject('ProductModel', 'App\Product')

@push('head-style')
    <link rel="stylesheet" href="{{ url($templateFile . '/css/cart.css?ver=1.00') }}">
    <style>
        .msg-error {
            color: #f00;
        }
    </style>
@endpush

@php
    // $states = \App\Models\Province::get();
    // $countries = \App\Models\Country::get();

    $carts = Cart::content();

    $subtotal = 0;
    // $auth_discount = 0;
    foreach ($carts as $cart) {
        $subtotal += $cart->price;
    }

    // dd(Cart::total(2));

    // $variable_group = App\Models\Variable::where('status', 0)->where('parent', 0)->orderBy('stt', 'asc')->pluck('name', 'id');

    // if (Auth::check()) {
    //     extract(auth()->user()->toArray());
    //     $auth_discount = ($subtotal * setting_cost('auth_discount')) / 100;
    // }

@endphp

@section('content')
    <div class="carts-content checkout-content">
        <div class="cart_bg">
            <div class="container pt-4 pb-3 py-sm-4">
                <div class="breadcrumbs my-4">
                    <div class="breadcrumbs mb-4">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item text-nowrap">
                                <a href="{{ route('cart') }}">Cart</a>
                            </li>
                            <li class="breadcrumb-item text-nowrap active" aria-current="page">Checkout</li>
                        </ol>
                    </div>
                </div>

                <div class="rounded-3 shadow-lg">
                    <form class="needs-validation " action="{{ route('cart_checkout.process') }}" method="post" id="form-checkout">
                        @csrf
                        <input type="hidden" name="shipping_cost" value="0">
                        <input type="hidden" name="cart_total" value="{{ Cart::total(2) }}" data-origin="{{ Cart::total(2) }}">
                        <input type="hidden" name="res_token" id="res_token" value="">

                        <div class="row billing-fields">
                            <div class="col-12 col-lg-8 pt-sm-3">

                                {{-- CART TABLE --}}
                                <div class="table-container bdr mb-4">
                                    <div class="table-responsive cart-table-include">
                                        @include($templatePath . '.cart.includes.checkout_cart_item')
                                    </div>
                                </div>

                                {{-- PAYMENT METHOD --}}
                                @include($templatePath . '.cart.includes.payment-method')

                                {{-- CUSTOMER INFO --}}
                                <div class="create-ac-content bg-light-gray">

                                    @include($templatePath . '.cart.includes.customer-info')

                                    <div class="msg-error mb-3" style="display: none;"></div>
                                    <div class="cart-btn">
                                        {{-- <button type="button" class="btn btn-info get_shipping_usps" title="Continue to shipping">Continue to shipping</button> --}}
                                        {{-- <a href="{{ url('cart') }}" class="btn btn-sm btn-info mt-3" title="Continue to shipping"><i class="fas fa-arrow-left"></i>&nbsp; BACK TO CART</a> --}}
                                    </div>
                                </div>
                            </div>

                            {{-- SIDEBAR --}}
                            <div class="col-12 col-lg-4 pt-sm-4 pt-md-3">
                                <div class="cart-total bdr sticky-top">
                                    <div class="heading">CART TOTAL</div>
                                    <div class="body">
                                        <div class="mb-2">
                                            <div class="d-flex justify-content-between">
                                                <div>Subtotal:</div>
                                                <div class="">
                                                    {!! render_price($subtotal) !!}
                                                </div>
                                            </div>
                                        </div>
                                        {{-- @if ($auth_discount)
                                            <div class="mb-2">
                                                <div class="d-flex justify-content-between">
                                                    <div>VIP Discount ({{ setting_cost('auth_discount') }}%):</div>
                                                    <div class="">
                                                        {!! render_price($auth_discount) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif --}}

                                        <div class="mb-3 pb-3 into-money">
                                            <div class="d-flex justify-content-between">
                                                <p class="subtotal text-uppercase">Total:</p>
                                                <p class="subtotal">
                                                    {!! render_price($subtotal) !!}
                                                </p>
                                            </div>
                                        </div>
                                        <button class="btn btn-primary btn-checkout d-block w-100 mt-3 submit-confirm" value="Place order" type="button">CHECKOUT</button>
                                    </div>
                                </div>
                            </div>
                            {{-- END SIDEBAR --}}

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection



@push('after-footer')
    <script type="application/javascript" src = "https://checkout.stripe.com/checkout.js" > </script>
    <script src="{{ asset('/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset($templateFile . '/js/cart.js?ver=' . time()) }}"></script>

    <script>
        $(document).ready(function($) {
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

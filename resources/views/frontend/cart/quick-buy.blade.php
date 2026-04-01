@extends('frontend.layouts.master')
@section('seo')
    @include('frontend.layouts.seo', $seo ?? [])
@endsection

@php
    if (Auth::check()) {
        extract(auth()->user()->toArray());
    }

    $option = session()->get('option');
    if (is_array($option)) {
        $option = json_decode($option[0], true);
    }

    $price_total = $option['price'] ?? $product->price;

    if ($option['options']['promotion_unit'] == '$') {
        $total = $price_total * $option['qty'] - $option['options']['promotion'];
    } else {
        $total = ($price_total * $option['qty'] * (100 - $option['options']['promotion'])) / 100;
    }
@endphp

@push('head-style')
    <link rel="stylesheet" href="{{ url($templateFile . '/css/cart.css?ver=1.00') }}">
@endpush


@section('content')
    <div class="carts-content">
        <div class="cart_bg">
            <div class="container pt-4 pb-3 py-sm-4">
                <div class="breadcrumbs mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item">Quick Buy</li>
                    </ol>
                </div>

                @if ($product)
                    <div class="row">
                        <div class="col-12 col-lg-8 pt-sm-3">
                            <form class="needs-validation" action="{{ route('cart_checkout.process') }}" method="post" id="form-checkout">
                                @csrf()
                                <input type="hidden" name="shipping_cost" value="0">
                                <input type="hidden" name="cart_total" value="{{ $total }}" data-origin="{{ $total }}">
                                <input type="hidden" name="res_token" id="res_token" value="">
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                {{-- CART TABLE --}}
                                <div class="row mb-2">
                                    <div class="col-md-12">

                                        <div class="table-container bdr">
                                            <div class="table-responsive">
                                                <table class="table table-borderless align-middle table-cart">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Image</th>
                                                            <th scope="col">Product</th>
                                                            <th scope="col">Price</th>
                                                            <th scope="col" class="text-center">Quantity</th>
                                                            <th scope="col" class="text-center">Promotion</th>
                                                            <th scope="col"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="cart-items cart__row_item">
                                                            <td class="cart-thumb">
                                                                @php
                                                                    $image = asset('assets/images/placeholder.png');
                                                                    if ($product->image && file_exists(public_path($product->image))) {
                                                                        $image = $product->image;
                                                                    }
                                                                @endphp
                                                                <a class="flex-shrink-0" href="{{ route('game.detail', $product->slug) }}" title="{{ $product->name }}">
                                                                    <img class="img-fluid item-image d-block mx-auto" src="{{ $image }}" alt="{{ $product->name }}">
                                                                </a>
                                                            </td>
                                                            <td style="width: 250px">
                                                                <a class="flex-shrink-0 item-name" href="{{ route('game.detail', $product->slug) }}" title="{{ $product->name }}">{{ $product->name }}</a>
                                                            </td>
                                                            <td>
                                                                {!! render_price($product->price) !!}
                                                            </td>
                                                            <td class="text-center">
                                                                <b>{{ $product->unit_qly > 0 ? $option['qty'] * $product->unit_qly : $option['qty'] * 1 }}{{ $product->unit }}</b>
                                                            </td>

                                                            <td class="text-center">
                                                                @if ($option['options']['promotion_unit'] == '$')
                                                                    <b>{!! render_price($option['options']['promotion']) !!}</b>
                                                                @else
                                                                    <b>{{ $option['options']['promotion'] }}{{ $option['options']['promotion_unit'] }}</b>
                                                                @endif
                                                            </td>
                                                            <td style="width: 80px"></td>
                                                        </tr>

                                                        <tr>
                                                            <td colspan="6">
                                                                <div class="d-flex justify-content-between">
                                                                    <a href="{{ url()->previous() }}" class="btn btn-sm btn-table-cart" title="Continue shopping">
                                                                        <i class="fas fa-arrow-left"></i>&nbsp; Continue shopping
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- PAYMENT METHOD --}}
                                @include($templatePath . '.cart.includes.payment-method')
                            </form>

                            {{-- CUSTOMER INFO --}}
                            <div class="create-ac-content bg-light-gray">

                                @include($templatePath . '.cart.includes.customer-info', ['showPayment' => false])

                                <div class="msg-error mb-3" style="display: none;"></div>
                                <div class="cart-btn">
                                    {{-- <button class="btn btn-custom d-block w-100 mt-3 submit-checkout" value="Place order" type="button" {{ auth()->check() ? '' : 'disabled' }}>@lang('CHECKOUT')</button> --}}
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
                                    <button class="btn btn-custom d-block w-100 mt-3 submit-checkout" value="Place order" type="button" {{ auth()->check() ? '' : 'disabled' }}>@lang('CHECKOUT')</button>
                                </div>
                            </div>
                        </div>
                        {{-- END SIDEBAR --}}
                    </div>
                @else
                    <div class="alert alert-danger text-uppercase mt-5" role="alert">
                        <i class="ci-loudspeaker"></i>
                        &nbsp;
                        {{-- @lang('Cart is empty!') --}}
                        Item is not exist
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection



@push('after-footer')
    <script type="application/javascript" src = "https://checkout.stripe.com/checkout.js" > </script>
    <script src="{{ asset('/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset($templateFile . '/js/cart.js?ver=' . time()) }}"></script>
    <script>
        var strip_key = '{{ config('services.stripe')['key'] }}';
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

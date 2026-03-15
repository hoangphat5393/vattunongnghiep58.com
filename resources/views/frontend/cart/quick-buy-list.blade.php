@php
    $states = \App\Models\Province::orderBy('name', 'ASC')->get();
    $fullname = $fullname ?? ($cart_info['ship_name'] ?? '');
    $email = $email ?? ($cart_info['ship_email'] ?? '');
    $phone = $phone ?? ($cart_info['ship_phone'] ?? '');
    $address = $address ?? ($cart_info['address_line1'] ?? '');
    $province = $province ?? ($cart_info['state_province'] ?? '');
    $cart_note = $cart_note ?? ($cart_info['cart_note'] ?? '');

    if (Auth::check()) {
        $user = Auth::user();
        $avatar = public_path('img/users/avatar/') . $user->avatar;
    }
    // dd($option);

    // $promotion = 0;
    // if (!empty($list_promotion)) {
    //     foreach ($list_promotion as $v) {
    //         if ($data['qty'] >= $v['qty_to_promotion']) {
    //             $promotion = $v['promotion'];
    //         }
    //     }
    // }
@endphp

<div class="container pt-4 pb-3 py-sm-4">
    <div class="breadcrumbs mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item">Quick Buy</li>
        </ol>
    </div>

    @if ($product)
        <form class="needs-validation" action="{{ route('quick_buy.checkout.confirm') }}" method="post" id="form-checkout">
            @csrf()
            <input type="hidden" name="shipping_cost" value="0">
            <input type="hidden" name="cart_total" value="{{ $total }}" data-origin="{{ $total }}">
            <input type="hidden" name="res_token" id="res_token" value="">
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <div class="row">
                <div class="col-12 col-lg-8 pt-sm-3">

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
                                                <th scope="col">Quantity</th>
                                                <th scope="col">Price</th>
                                                <th scope="col">Promotion</th>
                                                <th scope="col"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="cart-items cart__row_item">
                                                <td class="cart-thumb">
                                                    @if (!empty($product->image))
                                                        <a class="flex-shrink-0" href="{{ route('shop.detail', $product->slug) }}" title="{{ $product->name }}">
                                                            <img class="img-fluid item-image d-block mx-auto" src="{{ asset($product->image) }}" alt="{{ $product->name }}">
                                                        </a>
                                                    @endif
                                                </td>
                                                <td style="width: 250px">
                                                    <a class="flex-shrink-0 item-name" href="{{ route('shop.detail', $product->slug) }}" title="{{ $product->name }}">{{ $product->name }}</a>
                                                </td>
                                                <td align="center">
                                                    {{-- <div class="mx-auto mx-sm-0 text-center text-sm-start" style="max-width: 9rem;">
                                                        <input data-rowid="" class="form-control form-control-sm cart__qty-input quantity1" type="number" name="updates[]" value="{{ $option['qty'] }}" min="{{ $product->min_quantity }}" max="{{ $product->stock }}">
                                                        <b>{{ $option['qty'] }}</b>
                                                    </div> --}}
                                                    <b>{{ $option['qty'] }}</b>
                                                </td>
                                                <td>
                                                    ${!! number_format($product->price, 2, '.', ',') !!}
                                                </td>
                                                <td>
                                                    <b>${!! number_format($option['options']['promotion'], 2, '.', ',') !!}</b>
                                                </td>
                                                <td style="width: 80px">
                                                    {{-- <button class="btn btn-link w-100 cart__remove" type="button" data="$cart->rowId">
                                                        <img class="img-fluid d-block mx-auto" src="{{ asset($templateFile . '/images/remove-icon.png') }}" alt="">
                                                    </button> --}}
                                                </td>
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
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="block-content bdr">
                                <div class="title-block-p2">
                                    PAYMENT METHOD
                                </div>
                                <div class="body">
                                    <h4 class="custom_h4">Popular Payment</h4>
                                    <div class="row g-2">
                                        <div class="col-6 col-lg-3">
                                            <div class="form-check d-flex align-items-center payment-card">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="paypal">
                                                <label class="form-check-label" for="inlineRadio1">
                                                    <img src="{{ asset($templateFile . '/images/icon-paypal.png') }}" alt="">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6 col-lg-3">
                                            <div class="form-check d-flex align-items-center payment-card">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                                                <label class="form-check-label" for="inlineRadio2">
                                                    <img src="{{ asset($templateFile . '/images/icon-maestro.png') }}" alt="">
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-6 col-lg-3">
                                            <div class="form-check d-flex align-items-center payment-card">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio3" value="master">
                                                <label class="form-check-label" for="inlineRadio3">
                                                    <img src="{{ asset($templateFile . '/images/icon-master-card.png') }}" alt="">
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-6 col-lg-3">
                                            <div class="form-check d-flex align-items-center payment-card">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio4" value="payment">
                                                <label class="form-check-label" for="inlineRadio4">
                                                    <img src="{{ asset($templateFile . '/images/icon-payment.png') }}" alt="">
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <h4 class="custom_h4 mt-4">Other Payment</h4>
                                    <div class="row g-2">
                                        <div class="col-6 col-lg-3">
                                            <div class="form-check d-flex align-items-center payment-card">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio5" value="paypal">
                                                <label class="form-check-label" for="inlineRadio5">
                                                    <img src="{{ asset($templateFile . '/images/icon-paypal.png') }}" alt="">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6 col-lg-3">
                                            <div class="form-check d-flex align-items-center payment-card">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio6" value="option2">
                                                <label class="form-check-label" for="inlineRadio6">
                                                    <img src="{{ asset($templateFile . '/images/icon-maestro.png') }}" alt="">
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-6 col-lg-3">
                                            <div class="form-check d-flex align-items-center payment-card">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio7" value="master">
                                                <label class="form-check-label" for="inlineRadio7">
                                                    <img src="{{ asset($templateFile . '/images/icon-master-card.png') }}" alt="">
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-6 col-lg-3">
                                            <div class="form-check d-flex align-items-center payment-card">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio8" value="payment">
                                                <label class="form-check-label" for="inlineRadio8">
                                                    <img src="{{ asset($templateFile . '/images/icon-payment.png') }}" alt="">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (!isset($user))
                        {{-- CUSTOMER INFORMATION --}}
                        <div id="customer-form" class="row">
                            <div class="col-md-12">
                                <div class="block-content bdr">

                                    <div class="title-block-p2">
                                        CUSTOMER INFORMATION
                                    </div>
                                    <div class="body py-4">

                                        <div class="errorTxt mb-3"></div>

                                        <div class="row align-items-center mb-3">
                                            <div class="col-sm-9">
                                                <div class="form-floating form-floating-custom icon">
                                                    <img class="form-icon" src="{{ asset($templateFile . '/images/email-icon.png') }}" alt="Email">
                                                    <input type="text" class="form-control form-control-custom" id="ship_email" name="ship_email" placeholder="Your Email">
                                                    <label for="ship_mail" class="float-label-custom">Email</label>
                                                </div>
                                                {{-- <img src="{{ asset($templateFile . '/images/info-icon.png') }}" alt="Info"> You are already member <a href="#">Sign In</a> --}}
                                            </div>
                                        </div>

                                        <div class="row align-items-center mb-3">
                                            <div class="col-sm-9">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <select name="state_province" id="state_province" class="form-control state_province select-custom2">
                                                            <option value=""> --- Choose Province / City --- </option>
                                                            @foreach ($states as $state)
                                                                <option value="{{ $state->name }}" {{ $province == $state->id || $province == $state->name ? 'selected' : '' }}>{{ $state->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-floating form-floating-custom icon">
                                                            <img class="form-icon" src="{{ asset($templateFile . '/images/lock-icon.png') }}" alt="Phone">
                                                            <input type="text" class="form-control form-control-custom" id="ship_phone" name="ship_phone" placeholder="Your Phone">
                                                            <label for="ship_phone" class="float-label-custom">Phone</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row align-items-center mb-3">
                                            <div class="col-sm-9">
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                                    <label class="form-check-label" for="flexCheckDefault">
                                                        Register instantly to become a Member, and enjoy 1% - 5% Discount (About VIP)
                                                    </label>
                                                </div>
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
                                                    <label class="form-check-label" for="flexCheckChecked">
                                                        Just send me the password in email
                                                    </label>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked2">
                                                            <label class="form-check-label" for="flexCheckChecked2">
                                                                By continuing you agree to CNL’s Terms of Service and Privacy Policy
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <button class="btn btn-checkout">Cont Checkout</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-md-12">
                                <div class="block-content bdr">
                                    <div class="title-block-p2">
                                        CUSTOMER INFORMATION
                                    </div>
                                    <div class="body py-4">

                                        <div class="row align-items-center mb-3">
                                            <div class="col-sm-10">

                                                <div class="row">
                                                    <p class="block-content__welcome mb-4">
                                                        Hello {{ $user->fullname }}, well come back!
                                                        <br><br>
                                                        You can continue to check out since we have trust from each other
                                                    </p>

                                                    <div class="col-md-9">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked3">
                                                            <label class="form-check-label" for="flexCheckChecked3">
                                                                By continuing you agree to CNL’s <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <button class="btn btn-checkout">Cont Checkout</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
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
                                        $ {!! number_format($total, 2, '.', ',') !!}
                                    </p>
                                </div>
                            </div>
                            {{-- <a class="btn btn-checkout d-block w-100 mb-2 text-uppercase" href="{{ route('cart.checkout') }}"><i class="ci-card fs-lg me-2"></i>paynow</a> --}}
                            <button class="btn btn-primary btn-checkout d-block w-100 mt-3 submit-confirm" value="Place order" type="button"><i class="ci-card fs-lg me-2"></i> CHECKOUT</button>
                        </div>
                    </div>
                </div>
                {{-- END SIDEBAR --}}
            </div>
        </form>
    @else
        <div class="alert alert-danger text-uppercase mt-5" role="alert">
            <i class="ci-loudspeaker"></i>
            &nbsp;
            {{-- @lang('Cart is empty!') --}}
            Item is not exist
        </div>
    @endif

</div>


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

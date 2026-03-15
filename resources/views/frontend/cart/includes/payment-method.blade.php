@php
    $method = $payment_method ?? ($cart_info['payment_method'] ?? '');
@endphp
<div class="block-content bdr mb-4 payment-method">
    <div class="title-block-p2">PAYMENT METHOD</div>
    <div class="body">
        <h4 class="custom_h4">Popular Payment</h4>
        <div class="payment-info">
            <ul>
                <li class="stripe__card {{ $method == '' || $method == 'stripe__card' ? 'active' : '' }}">
                    <input type="radio" name="payment_method" id="stripe__card" value="stripe__card" class="radio" {{ $method == '' || $method == 'stripe__card' ? 'checked' : '' }}>
                    <label for="stripe__card"></label>
                    <img src="{{ asset('assets/images/payment/visa-mastercard.png') }}">
                </li>
                <li class="stripe__alipay {{ $method == 'stripe__alipay' ? 'active' : '' }}">
                    <input type="radio" name="payment_method" id="stripe__alipay" value="stripe__alipay" class="radio" {{ $method == 'stripe__alipay' ? 'checked' : '' }}>
                    <label for="stripe__alipay"></label>
                    <img src="{{ asset('assets/images/payment/alipaycn.png') }}">
                </li>
                {{--
                <li class="stripe__paypal {{ $method == 'stripe__paypal' ? 'active' : '' }}">
                    <input type="hidden" name="payment_method" value="stripe__paypal" class="radio">
                    <label></label>
                    <img src="{{ asset('assets/images/payment/paypal.png') }}">
                </li>
                <li class="googleapple {{ $method == '' || $method == 'googleapple' ? 'active' : '' }}">
                    <input type="hidden" name="payment_method" value="googleapple" class="radio">
                    <label></label>
                    <img src="{{ asset('assets/images/payment/googleapple.webp') }}">
                </li>
                --}}

            </ul>
        </div>
    </div>
</div>

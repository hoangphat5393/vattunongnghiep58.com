<div class="container mt-4">
    <div class="row justify-content-end">
        {{--
        <div class="col-12 col-sm-12 col-md-4 col-lg-8 mb-4">
            <h5>Discount Codes</h5>
            <form action="#" method="post">
                <div class="mb-3">
                    <label for="address_zip">Enter your coupon code if you have one.</label>
                    <input type="text" name="coupon">
                </div>
                <div class="actionRow">
                    <div><input type="button" class="btn btn-secondary btn--small" value="Apply Coupon"></div>
                </div>
            </form>
        </div>
        --}}

        <div class="col-12 col-sm-12 col-md-4 col-lg-4 cart__footer">
            <div class="solid-border">
                <div class="row border-bottom pb-2">
                    <span class="col-12 col-sm-6 cart__subtotal-title">Subtotal</span>
                    <span class="col-12 col-sm-6 text-end"><span class="money">{{ render_price(Cart::total(2)) }}</span></span>
                </div>
                <a class="btn btn--small-wide checkout w-100 mt-3" href="{{ route('cart.checkout') }}">@lang('Checkout')</a>
            </div>

        </div>
    </div>
</div>
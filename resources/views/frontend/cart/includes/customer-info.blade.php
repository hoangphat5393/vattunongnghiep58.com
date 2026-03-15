@php
    // $states = \App\Models\Frontend\Province::orderBy('name', 'ASC')->get();
    $fullname = $fullname ?? ($cart_info['ship_name'] ?? '');
    $firstname = $firstname ?? ($cart_info['firstname'] ?? '');
    $lastname = $lastname ?? ($cart_info['lastname'] ?? '');
    $email = $email ?? ($cart_info['ship_email'] ?? '');
    $phone = $phone ?? ($cart_info['ship_phone'] ?? '');
    $address = $address ?? ($cart_info['address_line1'] ?? '');
    $province = $province ?? ($cart_info['state_province'] ?? '');
    $cart_note = $cart_note ?? ($cart_info['cart_note'] ?? '');
    $user = auth()->user();

    // $states = \App\Models\Frontend\Province::get();
    $countries = \App\Models\Frontend\Country::get();

@endphp


@push('head-script')
    {!! RecaptchaV3::initJs() !!}
@endpush

<div id="customer-form" class="mt-4">

    {{-- CUSTOMER INFORMATION --}}
    <div class="block-content bdr">
        <h3>Thông tin khách hàng</h3>
        <div class="body py-4 position-relative">

            <div class="guest_tab">
                <form method="post" action="{{ route('cart.checkout') }}" id="checkout_form">
                    @csrf
                    {!! RecaptchaV3::field('order') !!}

                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <label for="cf-name" class="col-form-label">Họ tên</label>
                            <input type="text" class="form-control" id="cf-name" name="order[name]" placeholder="Họ tên" value="{{ $cart_info['ship_phone'] ?? '' }}">
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="ship_mail" class="col-form-label">Email</label>
                            <input type="text" class="form-control" id="ship_email" name="order[email]" placeholder="Email" value="{{ $cart_info['ship_email'] ?? '' }}">
                        </div>

                        <div class="col-lg-6 mb-3">
                            <label for="phone" class="col-form-label">Số điện thoại</label>
                            <input type="text" class="form-control" id="phone" name="order[phone]" placeholder="Số điện thoại" value="{{ $cart_info['ship_phone'] ?? '' }}">
                        </div>

                        <div class="col-lg-6 mb-3">
                            <label for="phone" class="col-form-label">Địa chỉ</label>
                            <input type="text" class="form-control" id="address" name="order[address]" placeholder="Địa chỉ" value="{{ $cart_info['ship_phone'] ?? '' }}">
                        </div>

                        <div class="col-lg-12 mb-3">
                            <label for="phone" class="col-form-label">Lời nhắn</label>
                            <textarea class="form-control" rows="6" name="order[content]" placeholder="Lời nhắn"></textarea>
                        </div>

                        <button type="submit" class="btn btn-success btn-checkout d-block w-100 mt-3">
                            <i class="fa-regular fa-cart-shopping"></i> Đặt hàng
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>


@push('scripts')
    <script>
        // CONTACT
        // var lc = '{{ app()->getLocale() }}';

        var checkout_form = $("#checkout_form");

        var error_messages = {};

        error_messages = {
            "order[name]": "Vui lòng điền tên!",
            "order[email]": {
                required: "Vui lòng điền địa chỉ email!",
                email: "Vui lòng nhập địa chỉ email hợp lệ",
            },
            "order[phone]": {
                required: "Vui lòng số điện thoại hợp lệ!",
                number: "Vui lòng cung cấp số điện thoại hợp lệ!!",
                digits: "Vui lòng cung cấp số điện thoại hợp lệ!!",
                minlength: "Vui lòng cung cấp số điện thoại hợp lệ!!",
            },
            "order[address]": "Vui lòng điền địa chỉ!",
            "order[content]": "Vui lòng nhập lời nhắn!"
        }

        checkout_form.validate({
            onfocusout: false,
            onkeyup: false,
            onclick: false,
            rules: {
                "order[name]": "required",
                "order[email]": {
                    required: true,
                    email: true,
                },
                // "contact[email_confirm]": {
                //     email: true,
                //     equalTo: "#cf-email"
                // },
                "order[phone]": {
                    required: true,
                    number: true,
                    digits: true,
                    minlength: 10,
                },
                "order[address]": "required",
                "order[content]": "required",
            },
            messages: error_messages,
            errorElement: "div",
            errorLabelContainer: ".errorTxt",
            invalidHandler: function(event, validator) {
                $("html, body").animate({
                    scrollTop: checkout_form.offset().top
                }, 500);
            },
        });
    </script>
@endpush

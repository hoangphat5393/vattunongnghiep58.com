@php
    $fullname = $fullname ?? ($cart_info['ship_name'] ?? '');
    $firstname = $firstname ?? ($cart_info['firstname'] ?? '');
    $lastname = $lastname ?? ($cart_info['lastname'] ?? '');
    $email = $email ?? ($cart_info['ship_email'] ?? '');
    $phone = $phone ?? ($cart_info['ship_phone'] ?? '');
    $address = $address ?? ($cart_info['address_line1'] ?? '');
    $province = $province ?? ($cart_info['state_province'] ?? '');
    $cart_note = $cart_note ?? ($cart_info['cart_note'] ?? '');
    $user = auth()->user();
@endphp


@push('head-script')
    {!! RecaptchaV3::initJs() !!}
@endpush

<div id="customer-form" class="mt-2">

    <div class="rounded-2xl border border-leaf-100 bg-white p-5 md:p-6 shadow-sm">
        <h3 class="text-xl font-extrabold text-gray-900 mb-2 pb-4 border-b border-gray-100">
            @if (!empty($contactCheckout))
                Thông tin liên hệ
            @else
                Thông tin khách hàng
            @endif
        </h3>
        @if (!empty($contactCheckout))
            <p class="text-sm text-gray-600 mb-6 -mt-2">Sau khi gửi, nhân viên cửa hàng sẽ gọi điện hoặc nhắn tin để xác nhận đơn và thời gian giao hàng.</p>
        @endif

        <form method="post" action="{{ route('cart.checkout.submit') }}" id="checkout_form">
            @csrf
            {!! RecaptchaV3::field('order') !!}

            @if ($showPayment ?? true)
                @include('frontend.cart.includes.payment-method')
            @endif

            <div class="errorTxt text-red-600 text-sm mb-4" aria-live="polite"></div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                <div class="md:col-span-1">
                    <label for="cf-name" class="block text-sm font-semibold text-gray-700 mb-1.5">Họ tên <span class="text-red-500">*</span></label>
                    <input type="text" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder:text-gray-400 focus:border-leaf-500 focus:outline-none focus:ring-2 focus:ring-leaf-500/25 transition" id="cf-name" name="order[name]" placeholder="Nguyễn Văn A" value="{{ $cart_info['ship_name'] ?? '' }}" autocomplete="name">
                </div>
                <div class="md:col-span-1">
                    <label for="ship_email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                    <input type="email" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder:text-gray-400 focus:border-leaf-500 focus:outline-none focus:ring-2 focus:ring-leaf-500/25 transition" id="ship_email" name="order[email]" placeholder="email@example.com" value="{{ $cart_info['ship_email'] ?? '' }}" autocomplete="email">
                </div>
                <div class="md:col-span-1">
                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-1.5">Số điện thoại <span class="text-red-500">*</span></label>
                    <input type="text" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder:text-gray-400 focus:border-leaf-500 focus:outline-none focus:ring-2 focus:ring-leaf-500/25 transition" id="phone" name="order[phone]" placeholder="09xxxxxxxx" value="{{ $cart_info['ship_phone'] ?? '' }}" autocomplete="tel">
                </div>
                <div class="md:col-span-1">
                    <label for="address" class="block text-sm font-semibold text-gray-700 mb-1.5">Địa chỉ giao hàng <span class="text-red-500">*</span></label>
                    <input type="text" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder:text-gray-400 focus:border-leaf-500 focus:outline-none focus:ring-2 focus:ring-leaf-500/25 transition" id="address" name="order[address]" placeholder="Số nhà, đường, phường/xã..." value="{{ $cart_info['address_line1'] ?? '' }}" autocomplete="street-address">
                </div>
                <div class="md:col-span-2">
                    <label for="order_note" class="block text-sm font-semibold text-gray-700 mb-1.5">Lời nhắn</label>
                    <textarea class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder:text-gray-400 focus:border-leaf-500 focus:outline-none focus:ring-2 focus:ring-leaf-500/25 transition min-h-30 resize-y" rows="4" id="order_note" name="order[content]" placeholder="Ghi chú thêm về đơn hàng (không bắt buộc)">{{ old('order.content', $cart_info['cart_note'] ?? '') }}</textarea>
                </div>
            </div>

            <button type="submit" class="lg:hidden mt-8 w-full cursor-pointer flex justify-center items-center gap-2 bg-leaf-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-leaf-500/30 hover:bg-leaf-700 transition transform hover:-translate-y-1">
                @if (!empty($contactCheckout))
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    Gửi thông tin liên hệ
                @else
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Đặt hàng
                @endif
            </button>
        </form>
    </div>
</div>


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var $ = window.jQuery || window.$;
            if (!$ || !$.fn || typeof $.fn.validate !== 'function') {
                return;
            }

            var checkout_form = $("#checkout_form");
            if (!checkout_form.length) {
                return;
            }

            var error_messages = {
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
                "order[address]": "Vui lòng điền địa chỉ!"
            };

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
                    "order[phone]": {
                        required: true,
                        number: true,
                        digits: true,
                        minlength: 10,
                    },
                    "order[address]": "required",
                },
                messages: error_messages,
                errorElement: "p",
                errorClass: "text-red-600 text-sm mt-1.5",
                errorPlacement: function(error, element) {
                    error.addClass("text-red-600 text-sm mt-1.5");
                    error.insertAfter(element);
                },
                invalidHandler: function() {
                    var el = checkout_form.get(0);
                    if (!el || typeof el.scrollIntoView !== 'function') {
                        return;
                    }
                    el.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                },
            });
        });
    </script>
@endpush

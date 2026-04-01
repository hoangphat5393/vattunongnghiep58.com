@php
    $method = $payment_method ?? ($cart_info['payment_method'] ?? '');
@endphp
<div class="payment-method mb-6">
    <h3 class="font-bold text-lg text-gray-900 mb-1">Phương thức thanh toán</h3>
    <p class="text-sm text-gray-500 mb-4">Chọn hình thức bạn muốn sử dụng (có thể xác nhận lại khi liên hệ giao hàng).</p>
    <div class="grid sm:grid-cols-2 gap-3">
        <label class="flex cursor-pointer gap-4 rounded-xl border-2 border-gray-200 bg-white p-4 shadow-sm transition hover:border-leaf-300 has-[:checked]:border-leaf-500 has-[:checked]:bg-leaf-50/60">
            <input type="radio" name="payment_method" id="stripe__card" value="stripe__card" class="mt-1 h-4 w-4 shrink-0 border-gray-300 text-leaf-600 focus:ring-leaf-500" {{ $method == '' || $method == 'stripe__card' ? 'checked' : '' }}>
            <span class="min-w-0 flex-1">
                <span class="font-bold text-gray-900 block">Thẻ quốc tế</span>
                <span class="text-xs text-gray-500 block mt-0.5">Visa, Mastercard</span>
                <img src="{{ asset('assets/images/payment/visa-mastercard.png') }}" alt="" class="mt-3 h-8 w-auto max-w-full object-contain object-left" loading="lazy" onerror="this.classList.add('hidden')">
            </span>
        </label>
        <label class="flex cursor-pointer gap-4 rounded-xl border-2 border-gray-200 bg-white p-4 shadow-sm transition hover:border-leaf-300 has-[:checked]:border-leaf-500 has-[:checked]:bg-leaf-50/60">
            <input type="radio" name="payment_method" id="stripe__alipay" value="stripe__alipay" class="mt-1 h-4 w-4 shrink-0 border-gray-300 text-leaf-600 focus:ring-leaf-500" {{ $method == 'stripe__alipay' ? 'checked' : '' }}>
            <span class="min-w-0 flex-1">
                <span class="font-bold text-gray-900 block">Alipay</span>
                <span class="text-xs text-gray-500 block mt-0.5">Thanh toán Alipay</span>
                <img src="{{ asset('assets/images/payment/alipaycn.png') }}" alt="" class="mt-3 h-8 w-auto max-w-full object-contain object-left" loading="lazy" onerror="this.classList.add('hidden')">
            </span>
        </label>
    </div>
</div>

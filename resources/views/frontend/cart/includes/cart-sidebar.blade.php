<div class="lg:w-1/4 cart-sidebar-include">
    <div class="bg-white rounded-2xl shadow-sm border border-leaf-100 p-6 lg:sticky lg:top-24">
        <h3 class="font-bold text-xl text-gray-900 mb-6 border-b pb-4">Cộng giỏ hàng</h3>
        <div class="space-y-4">
            <div class="flex justify-between text-gray-700">
                <span>Tạm tính</span>
                <span class="font-bold">{!! render_price($cart_summary['subtotal'], 'VND') !!}</span>
            </div>
            <div class="flex justify-between text-gray-700">
                <span>Phí vận chuyển</span>
                <span class="font-bold text-leaf-600">Miễn phí</span>
            </div>
            <div class="pt-4 border-t">
                <div class="flex justify-between items-center">
                    <span class="font-bold text-lg text-gray-900">Tổng cộng</span>
                    <span class="text-2xl font-extrabold text-leaf-600">{!! render_price($cart_summary['total'], 'VND') !!}</span>
                </div>
                <p class="text-xs text-gray-500 mt-1 text-right">Đã bao gồm VAT (nếu có)</p>
            </div>
        </div>

        <a href="{{ route('cart.checkout') }}" class="cursor-pointer block w-full mt-6 bg-leaf-600 text-white text-center font-bold py-3 rounded-xl hover:bg-leaf-700 transition transform hover:-translate-y-0.5 shadow-md">
            Tiến hành đặt hàng
        </a>

        <div class="mt-6 hidden" aria-hidden="true">
            <h4 class="font-bold text-gray-800 mb-3 text-sm">Mã giảm giá</h4>
            <div class="flex gap-2">
                <input type="text" placeholder="Nhập mã khuyến mãi" class="w-full pl-4 pr-12 py-3 rounded-xl border border-gray-200 focus:border-leaf-500 focus:outline-none text-sm">
                <button type="button" class="cursor-pointer px-4 py-2 bg-leaf-100 text-leaf-700 font-bold rounded-xl hover:bg-leaf-200 text-sm whitespace-nowrap">
                    Áp dụng
                </button>
            </div>
        </div>

        <div class="mt-6 pt-4 border-t border-gray-100">
            <div class="flex items-center gap-2 text-gray-500 text-sm justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                    </path>
                </svg>
                <span>Bảo mật thanh toán 100%</span>
            </div>
        </div>
    </div>
</div>

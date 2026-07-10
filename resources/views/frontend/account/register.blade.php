@extends('frontend.layouts.master')

@section('content')
    <div class="container mx-auto px-4 py-8 md:py-12">
        <div class="auth-form-shell">
            <div class="text-center mb-6 md:mb-8">
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">Tạo tài khoản</h1>
                <p class="text-gray-600 mt-2 text-sm md:text-base">Đăng ký để theo dõi đơn hàng và nhận ưu đãi</p>
            </div>

            <div class="bg-white rounded-2xl p-6 md:p-8">
                <form method="post" action="{{ route('customer.register.submit') }}" id="page-customer-register" accept-charset="UTF-8" class="relative space-y-5">
                    @csrf

                    <div class="list-content-loading hidden absolute inset-0 z-10 flex items-center justify-center rounded-2xl bg-white/80">
                        <div class="h-10 w-10 animate-spin rounded-full border-4 border-leaf-200 border-t-leaf-600" role="status" aria-label="Đang xử lý"></div>
                    </div>

                    <div class="error-message hidden rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" aria-live="polite"></div>

                    <div>
                        <label for="CustomerName" class="block text-sm font-semibold text-gray-700 mb-1.5">Họ tên <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="CustomerName" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder:text-gray-400 focus:border-leaf-500 focus:outline-none focus:ring-2 focus:ring-leaf-500/25 transition" placeholder="Nguyễn Văn A" autocomplete="name" autofocus>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-1.5">Số điện thoại <span class="text-red-500">*</span></label>
                        <input type="text" name="phone" id="phone" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder:text-gray-400 focus:border-leaf-500 focus:outline-none focus:ring-2 focus:ring-leaf-500/25 transition" placeholder="09xxxxxxxx" autocomplete="tel">
                    </div>

                    <div>
                        <label for="CustomerEmail" class="block text-sm font-semibold text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="CustomerEmail" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder:text-gray-400 focus:border-leaf-500 focus:outline-none focus:ring-2 focus:ring-leaf-500/25 transition" placeholder="email@example.com" autocomplete="email">
                    </div>

                    <div>
                        <label for="CustomerPassword" class="block text-sm font-semibold text-gray-700 mb-1.5">Mật khẩu <span class="text-red-500">*</span></label>
                        <input type="password" name="password" id="CustomerPassword" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder:text-gray-400 focus:border-leaf-500 focus:outline-none focus:ring-2 focus:ring-leaf-500/25 transition" placeholder="Tối thiểu 6 ký tự" autocomplete="new-password">
                    </div>

                    <div>
                        <label for="CustomerPasswordConfirm" class="block text-sm font-semibold text-gray-700 mb-1.5">Nhập lại mật khẩu <span class="text-red-500">*</span></label>
                        <input type="password" name="password_confirm" id="CustomerPasswordConfirm" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder:text-gray-400 focus:border-leaf-500 focus:outline-none focus:ring-2 focus:ring-leaf-500/25 transition" placeholder="Nhập lại mật khẩu" autocomplete="new-password">
                    </div>

                    <button type="button" class="btn-register w-full cursor-pointer bg-leaf-600 text-white font-bold py-3 px-6 rounded-xl hover:bg-leaf-700 transition">
                        Đăng ký
                    </button>

                    <p class="text-center text-sm text-gray-600">
                        Đã có tài khoản?
                        <a href="{{ route('customer.login') }}" class="font-semibold text-leaf-700 hover:text-leaf-500 no-underline">
                            Đăng nhập
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof window.initCustomerRegisterForm === 'function') {
                window.initCustomerRegisterForm('#page-customer-register', '.btn-register');
            }
        });
    </script>
@endpush

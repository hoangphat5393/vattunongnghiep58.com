@extends('frontend.layouts.master')

@section('content')
    <div class="container mx-auto px-4 py-8 md:py-12">
        <div class="auth-form-shell">
            <div class="text-center mb-6 md:mb-8">
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">Đăng nhập</h1>
                <p class="text-gray-600 mt-2 text-sm md:text-base">Chào mừng bạn quay lại Vật Tư 58</p>
            </div>

            <div class="bg-white rounded-2xl p-6 md:p-8">
                <form method="post" action="{{ route('customer.login.submit') }}" id="form-login-page" accept-charset="UTF-8" class="relative space-y-5">
                    @csrf

                    <div class="list-content-loading hidden absolute inset-0 z-10 flex items-center justify-center rounded-2xl bg-white/80">
                        <div class="h-10 w-10 animate-spin rounded-full border-4 border-leaf-200 border-t-leaf-600" role="status" aria-label="Đang xử lý"></div>
                    </div>

                    <div class="error-message hidden rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" aria-live="polite"></div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                        <input type="email" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder:text-gray-400 focus:border-leaf-500 focus:outline-none focus:ring-2 focus:ring-leaf-500/25 transition" id="email" name="email" placeholder="email@example.com" value="{{ $email ?? '' }}" autocomplete="email" autofocus>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Mật khẩu <span class="text-red-500">*</span></label>
                        <input type="password" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder:text-gray-400 focus:border-leaf-500 focus:outline-none focus:ring-2 focus:ring-leaf-500/25 transition" id="password" name="password" placeholder="Nhập mật khẩu" autocomplete="current-password">
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="remember_me" id="remember_me" value="1" class="h-4 w-4 rounded border-gray-300 text-leaf-600 focus:ring-leaf-500">
                        <label for="remember_me" class="text-sm text-gray-600">Ghi nhớ đăng nhập</label>
                    </div>

                    <button type="button" class="btn-login-page w-full cursor-pointer bg-leaf-600 text-white font-bold py-3 px-6 rounded-xl hover:bg-leaf-700 transition">
                        Đăng nhập
                    </button>

                    <p class="text-center text-sm text-gray-600">
                        <a href="{{ route('customer.password.forgot') }}" id="RecoverPassword" class="font-semibold text-leaf-700 hover:text-leaf-500 no-underline">
                            Quên mật khẩu?
                        </a>
                        <span class="mx-2 text-gray-300">|</span>
                        <a href="{{ route('customer.register') }}" id="customer_register_link" class="font-semibold text-leaf-700 hover:text-leaf-500 no-underline">
                            Tạo tài khoản
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
            if (typeof window.initCustomerLoginForm === 'function') {
                window.initCustomerLoginForm('#form-login-page', '.btn-login-page');
            }
        });
    </script>
@endpush

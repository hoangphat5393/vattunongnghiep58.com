@extends('frontend.layouts.master')

@section('content')
    <div class="container mx-auto px-4 py-8 md:py-12">
        <div class="auth-form-shell">
            <div class="text-center mb-6 md:mb-8">
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">Đặt mật khẩu mới</h1>
                <p class="text-gray-600 mt-2 text-sm md:text-base">Tạo mật khẩu mới cho tài khoản của bạn</p>
            </div>

            <div class="bg-white rounded-2xl p-6 md:p-8">
                @if ($errors->any())
                    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('customer.password.reset.submit') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="new_password" class="block text-sm font-semibold text-gray-700 mb-1.5">Mật khẩu mới <span class="text-red-500">*</span></label>
                        <input type="password" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder:text-gray-400 focus:border-leaf-500 focus:outline-none focus:ring-2 focus:ring-leaf-500/25 transition" id="new_password" name="new_password" placeholder="Nhập mật khẩu mới" required autofocus>
                    </div>

                    <div>
                        <label for="confirm_new_password" class="block text-sm font-semibold text-gray-700 mb-1.5">Nhập lại mật khẩu <span class="text-red-500">*</span></label>
                        <input type="password" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder:text-gray-400 focus:border-leaf-500 focus:outline-none focus:ring-2 focus:ring-leaf-500/25 transition" id="confirm_new_password" name="confirm_new_password" placeholder="Nhập lại mật khẩu mới" required>
                    </div>

                    <button type="submit" class="w-full cursor-pointer bg-leaf-600 text-white font-bold py-3 px-6 rounded-xl hover:bg-leaf-700 transition">
                        Cập nhật mật khẩu
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

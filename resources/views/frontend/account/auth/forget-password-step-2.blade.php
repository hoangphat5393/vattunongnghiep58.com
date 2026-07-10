@extends('frontend.layouts.master')

@section('content')
    <div class="container mx-auto px-4 py-8 md:py-12">
        <div class="auth-form-shell">
            <div class="text-center mb-6 md:mb-8">
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">Xác minh mã OTP</h1>
                <p class="text-gray-600 mt-2 text-sm md:text-base">Nhập mã OTP đã gửi tới email của bạn</p>
            </div>

            <div class="bg-white rounded-2xl p-6 md:p-8">
                @if ($errors->any())
                    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('customer.password.verify.submit') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="otp_mail" class="block text-sm font-semibold text-gray-700 mb-1.5">Mã OTP <span class="text-red-500">*</span></label>
                        <input type="text" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder:text-gray-400 focus:border-leaf-500 focus:outline-none focus:ring-2 focus:ring-leaf-500/25 transition" id="otp_mail" name="otp_mail" placeholder="Nhập mã 6 số" value="{{ old('otp_mail') }}" required autofocus>
                    </div>

                    <button type="submit" class="w-full cursor-pointer bg-leaf-600 text-white font-bold py-3 px-6 rounded-xl hover:bg-leaf-700 transition">
                        Tiếp tục
                    </button>

                    <p class="text-center text-sm text-gray-600">
                        <a href="{{ route('customer.password.forgot') }}" class="font-semibold text-leaf-700 hover:text-leaf-500 no-underline">
                            Gửi lại mã OTP
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>
@endsection

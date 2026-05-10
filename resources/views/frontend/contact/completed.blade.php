@extends('frontend.layouts.master')



@section('seo')
@endsection



@section('content')
    <div id="main" class="contact-completed">
        @include('frontend.includes.menu')

        <div class="container mx-auto px-4 py-4">
            <div class="text-sm text-gray-500 flex items-center gap-2">
                <a href="{{ route('index') }}" class="hover:text-leaf-600">Trang chủ</a>
                <span>/</span>
                <span class="text-leaf-700 font-bold">Hoàn tất liên hệ</span>
            </div>
        </div>

        <div class="container mx-auto px-4 py-10">
            <div class="max-w-3xl mx-auto">
                <div class="bg-white rounded-2xl shadow-lg border border-leaf-100 p-6 md:p-10">
                    <div class="flex flex-col items-center text-center gap-4">
                        <div class="w-16 h-16 rounded-full bg-leaf-100 text-leaf-600 flex items-center justify-center">
                            <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>

                        <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">
                            Hoàn tất liên hệ
                        </h1>

                        @if (session('contact_name'))
                            <p class="text-gray-700 font-semibold">
                                Cảm ơn {{ session('contact_name') }}!
                            </p>
                        @endif

                        <p class="text-gray-600 leading-relaxed max-w-2xl">
                            Cảm ơn bạn đã gửi câu hỏi/ý kiến đóng góp đến cho chúng tôi. Chúng tôi sẽ liên hệ lại với bạn trong thời gian sớm nhất.
                            Nếu bạn chưa nhận được phản hồi, vui lòng liên hệ lại qua các kênh bên dưới.
                        </p>
                    </div>

                    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="mailto:{{ setting_option('email') }}" class="group block rounded-xl border border-gray-200 p-4 hover:border-leaf-300 hover:bg-leaf-50 transition">
                            <div class="text-sm font-bold text-gray-900 group-hover:text-leaf-700">Email</div>
                            <div class="mt-1 text-gray-600">{{ setting_option('email') }}</div>
                        </a>
                        <a href="tel:{{ setting_option('phone') }}" class="group block rounded-xl border border-gray-200 p-4 hover:border-leaf-300 hover:bg-leaf-50 transition">
                            <div class="text-sm font-bold text-gray-900 group-hover:text-leaf-700">Điện thoại</div>
                            <div class="mt-1 text-gray-600">{{ setting_option('phone') }}</div>
                        </a>
                    </div>

                    <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
                        <a href="{{ route('index') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-xl font-bold bg-leaf-600 text-white hover:bg-leaf-700 transition">
                            Về trang chủ
                        </a>
                        <a href="{{ url('contact') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-xl font-bold border-2 border-gray-200 text-gray-800 hover:border-leaf-300 hover:bg-leaf-50 transition">
                            Gửi liên hệ khác
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('frontend.layouts.master')

@section('seo')
@endsection

@section('content')
    <div id="main" class="register-success">
        @include('frontend.includes.menu')

        <div class="container mx-auto px-4 py-4">
            <div class="text-sm text-gray-500 flex items-center gap-2">
                <a href="{{ route('index') }}" class="hover:text-leaf-600">Trang chủ</a>
                <span>/</span>
                <span class="text-leaf-700 font-bold">Đăng ký thành công</span>
            </div>
        </div>

        <div class="container mx-auto px-4 py-10">
            <div class="max-w-3xl mx-auto">
                <div class="bg-white rounded-2xl shadow-lg border border-leaf-100 p-6 md:p-10">
                    @include('frontend.account.includes.register_success')
                </div>
            </div>
        </div>
    </div>
@endsection

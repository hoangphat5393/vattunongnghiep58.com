@php
    extract($data ?? []);
    $user = $user ?? auth()->user();
@endphp

@extends('frontend.layouts.master')

@section('seo')
    @include('frontend.layouts.seo', $seo ?? [])
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6 md:py-10">
        <div class="mb-6 text-sm text-gray-500 flex items-center gap-2 flex-wrap">
            <a href="{{ route('index') }}" class="hover:text-leaf-600 no-underline">Trang chủ</a>
            <span>/</span>
            <span class="text-leaf-700 font-bold">Đổi mật khẩu</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8">
            <aside class="lg:col-span-3">
                @include($templatePath . '.account.includes.account-nav')
            </aside>

            <div class="lg:col-span-9">
                <div class="auth-form-shell">
                    <div class="rounded-2xl border border-leaf-100 bg-white p-5 md:p-8 shadow-sm">
                        <h1 class="text-xl md:text-2xl font-extrabold text-gray-900 mb-6">Đổi mật khẩu</h1>

                        @if (session('success'))
                            <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        <form action="{{ route('customer.password.update') }}" method="post" id="form-change-password" novalidate="novalidate" class="space-y-5">
                            @csrf

                            <div>
                                <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-1.5">Mật khẩu hiện tại <span class="text-red-500">*</span></label>
                                <input type="password" name="current_password" id="current_password" class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-leaf-500 focus:outline-none focus:ring-2 focus:ring-leaf-500/25 transition">
                            </div>

                            <div>
                                <label for="new_password" class="block text-sm font-semibold text-gray-700 mb-1.5">Mật khẩu mới <span class="text-red-500">*</span></label>
                                <input type="password" name="new_password" id="new_password" class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-leaf-500 focus:outline-none focus:ring-2 focus:ring-leaf-500/25 transition">
                            </div>

                            <div>
                                <label for="confirm_password" class="block text-sm font-semibold text-gray-700 mb-1.5">Nhập lại mật khẩu mới <span class="text-red-500">*</span></label>
                                <input type="password" name="confirm_password" id="confirm_password" class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-leaf-500 focus:outline-none focus:ring-2 focus:ring-leaf-500/25 transition">
                            </div>

                            <button type="submit" class="btn-submit-password w-full cursor-pointer rounded-xl bg-leaf-600 px-6 py-3 font-bold text-white hover:bg-leaf-700 transition">
                                Cập nhật mật khẩu
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: "{{ session('success') }}",
                        timer: 2500
                    });
                }
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: "{{ $errors->first() }}",
                        timer: 3000
                    });
                }
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('form-change-password');
            const $ = window.jQuery || window.$;

            if ($ && $.fn && typeof $.fn.validate === 'function' && form) {
                $('#form-change-password').validate({
                    onfocusout: false,
                    onkeyup: false,
                    onclick: false,
                    rules: {
                        current_password: 'required',
                        new_password: {
                            required: true,
                            minlength: 6
                        },
                        confirm_password: {
                            required: true,
                            equalTo: '#new_password'
                        }
                    },
                    messages: {
                        current_password: 'Vui lòng nhập mật khẩu hiện tại.',
                        new_password: {
                            required: 'Vui lòng nhập mật khẩu mới.',
                            minlength: 'Mật khẩu mới tối thiểu 6 ký tự.'
                        },
                        confirm_password: {
                            required: 'Vui lòng nhập lại mật khẩu mới.',
                            equalTo: 'Mật khẩu xác nhận không khớp.'
                        }
                    },
                    errorElement: 'div',
                    errorClass: 'text-red-600 text-xs mt-1'
                });
            }
        });
    </script>
@endpush

@extends('frontend.layouts.master')

@section('seo')
    @include('frontend.layouts.seo', $seo ?? [])
@endsection

{{-- @section('body-class', 'page-template-default page page-id-1940') --}}

@php
    $lc = app()->getLocale();
@endphp


@push('head-script')
    {!! RecaptchaV3::initJs() !!}
@endpush


@section('content')
    <div id="main" class="contact">
        @include('frontend.includes.menu')

        <div class="container mx-auto px-4 py-4">
            <div class="text-sm text-gray-500 flex items-center gap-2">
                <a href="{{ route('index') }}" class="hover:text-leaf-600">Trang chủ</a>
                <span>/</span>
                <span class="text-leaf-700 font-bold">Liên hệ</span>
            </div>
        </div>

        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 mb-6">Liên hệ với chúng tôi</h1>
                    <div class="prose max-w-none text-gray-700 mb-8">
                        {!! htmlspecialchars_decode($page->content) !!}
                    </div>

                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 bg-leaf-100 rounded-full flex items-center justify-center text-leaf-600 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 text-lg">Địa chỉ</h3>
                                <p class="text-gray-600">{{ setting_option('address') }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 bg-leaf-100 rounded-full flex items-center justify-center text-leaf-600 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 text-lg">Hotline</h3>
                                <p class="text-gray-600 text-lg font-bold text-leaf-600">
                                    {{ setting_option('phone') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 bg-leaf-100 rounded-full flex items-center justify-center text-leaf-600 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 text-lg">Email</h3>
                                <p class="text-gray-600">{{ setting_option('email') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg p-8 border border-leaf-100">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Gửi tin nhắn cho chúng tôi</h2>
                    <form id="contact_form" method="post" action="{{ route('contact.submit') }}" novalidate="novalidate"
                        class="space-y-4">
                        @csrf
                        {!! RecaptchaV3::field('contact') !!}
                        <p class="font-semibold text-gray-700">
                            Nhập thông tin của bạn vào form bên dưới để nhận tư vấn từ chúng tôi.
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Họ và tên</label>
                                <input type="text" name="contact[name]"
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-leaf-500 focus:border-leaf-500 outline-none transition"
                                    placeholder="Họ và tên*" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ</label>
                                <input type="text" name="contact[address]"
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-leaf-500 focus:border-leaf-500 outline-none transition"
                                    placeholder="Địa chỉ" />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Điện thoại</label>
                                <input type="text" name="contact[phone]"
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-leaf-500 focus:border-leaf-500 outline-none transition"
                                    placeholder="Điện thoại*" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="text" name="contact[email]"
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-leaf-500 focus:border-leaf-500 outline-none transition"
                                    placeholder="Email" />
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lời nhắn</label>
                            <textarea name="contact[content]" rows="4"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-leaf-500 focus:border-leaf-500 outline-none transition"
                                placeholder="Lời nhắn"></textarea>
                        </div>
                        <div class="flex items-center gap-4">
                            <button type="reset"
                                class="px-6 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                                Nhập lại
                            </button>
                            <button type="button"
                                class="btn-contact-submit px-6 py-2 rounded-lg bg-leaf-600 text-white font-bold hover:bg-leaf-700 transition">
                                Gửi
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-16 rounded-2xl overflow-hidden shadow-lg border border-leaf-100 h-96 relative">
                <iframe src="{{ setting_option('google_map') }}" width="100%" height="100%" class="contact-map-frame"
                    allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const contact_form = document.getElementById('contact_form');
            const submitBtn = document.querySelector('.btn-contact-submit');
            const contactSuccess = typeof contact_success !== 'undefined' ? contact_success : null;
            const $ = window.jQuery || window.$;

            if ($ && $.fn && typeof $.fn.validate === 'function' && contact_form) {
                $('#contact_form').validate({
                    onfocusout: false,
                    onkeyup: false,
                    onclick: false,
                    rules: {
                        'contact[name]': 'required',
                        'contact[phone]': {
                            required: true,
                            minlength: 10
                        },
                        'contact[email]': {
                            required: true,
                            email: true
                        },
                        'contact[content]': {
                            required: true,
                            minlength: 10,
                            maxlength: 200
                        }
                    },
                    messages: {
                        'contact[name]': 'Vui lòng điền họ và tên!',
                        'contact[phone]': {
                            required: 'Vui lòng điền số điện thoại!',
                            minlength: 'Vui lòng cung cấp số điện thoại hợp lệ (tối thiểu 10 số)!'
                        },
                        'contact[email]': {
                            required: 'Vui lòng điền địa chỉ email!',
                            email: 'Vui lòng nhập địa chỉ email hợp lệ!'
                        },
                        'contact[content]': {
                            required: 'Vui lòng nhập lời nhắn!',
                            minlength: 'Vui lòng nhập tối thiểu 10 ký tự!',
                            maxlength: 'Vui lòng nhập tối đa 200 ký tự!'
                        }
                    },
                    errorElement: 'div',
                    errorClass: 'text-red-600 text-xs mt-1'
                });
            }

            const showSwalError = (message) => {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: message,
                        timer: 2500
                    });
                } else {
                    alert(message);
                }
            };

            if (submitBtn && contact_form) {
                submitBtn.addEventListener('click', function() {
                    if ($ && $.fn && typeof $.fn.validate === 'function') {
                        if (!$('#contact_form').valid()) {
                            return;
                        }
                    }

                    const fdnew = new FormData(contact_form);
                    const submitRequest = window.http ? window.http.postForm.bind(window.http) : window
                        .axios.post.bind(window.axios);

                    submitRequest(
                            contact_form.getAttribute('action'),
                            fdnew, {
                                headers: {
                                    Accept: 'application/json',
                                },
                            }
                        )
                        .then(res => {
                            if (res.data.status === 'success') {
                                const redirect = res.data.redirect || contactSuccess;
                                if (redirect) window.location.replace(redirect);
                            } else {
                                showSwalError(res.data.message ||
                                    'Đã xảy ra lỗi từ hệ thống, vui lòng thử lại!');
                            }
                        })
                        .catch((err) => {
                            const msg = err?.response?.data?.message;
                            showSwalError(msg || 'Đã xảy ra lỗi kết nối, vui lòng thử lại!');
                        });
                });
            }
        });
    </script>
@endpush

@extends('frontend.layouts.master')

@section('seo')
    @include('frontend.layouts.seo', $seo ?? [])
@endsection

{{-- @section('body-class', 'page-template-default page page-id-1940') --}}

@section('content')
    <div id="contact">

        <h1 class="text-center">Liên hệ</h1>

        <section class="block10">
            <div class="mainBanner">
                <div class="container main-menu">
                    @include('frontend.includes.menu')
                </div>
                <div class="container-fluid px-0">
                    <div class="row g-0">
                        <div class="col-lg-12 banner-left">
                            <img class="img-fluid object-fit-cover w-100" src="{{ get_image($page->image) }}" alt="{{ $page->name }}">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div id="content" class="site-content no-sidebar regular has-">
            <div class="container">
                <div id="primary" class="content-area">
                    <div id="main" class="site-main">
                        <p class="has-text-align-center">
                            Cảm ơn sự quan tâm của bạn dành cho tổ chức của chúng tôi. Nếu như có bất cứ thắc mắc nào về các hoạt động cũng như thông tin về Quỹ, hãy gửi tin nhắn cho chúng tôi. Chúng tôi luôn chào đón và hồi đáp cho sự thắc mắc của bạn.
                        </p>

                        <div class="row justify-content-center">
                            <div class="col-lg-6">
                                <form id="contact_form" action="{{ route('contact.submit') }}" method="post" novalidate="novalidate">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="col-form-label " for="cf-name">Họ tên<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="cf-name" name="contact[name]" placeholder="Họ tên">
                                    </div>
                                    <div class="mb-3">
                                        <label class="col-form-label" for="cf-email">@lang('Email')<span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="cf-email" name="contact[email]" placeholder="@lang('Email')">
                                    </div>
                                    <div class="mb-3">
                                        <label class="col-form-label" for="cf-subject">@lang('Phone')<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="cf-subject" name="contact[phone]" placeholder="@lang('Phone')">
                                    </div>
                                    <div class="mb-3">
                                        <label class="col-form-label" for="cf-content">@lang('Message')<span class="text-danger">*</span></label>
                                        <textarea name="contact[content]" class="form-control bg-transparent" id="cf-content" rows="3" placeholder="@lang('Message')"></textarea>
                                    </div>
                                    <div class="submit-btn text-end">
                                        <button type="submit" class="btn btn-submit-contact">Gửi</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // CONTACT
        var contact_form = $("#contact_form");
        // var lc = '{{ app()->getLocale() }}';
        var error_messages = {};

        error_messages = {
            "contact[name]": "Vui lòng điền tên!",
            "contact[email]": {
                required: "Vui lòng điền địa chỉ email!",
                email: "Vui lòng nhập địa chỉ email hợp lệ",
            },
            "contact[email_confirm]": {
                email: "Vui lòng nhập địa chỉ email hợp lệ",
                equalTo: "Email không khớp"
            },
            "contact[phone]": {
                required: "Vui lòng số điện thoại hợp lệ",
                number: "Vui lòng cung cấp số điện thoại hợp lệ!!",
                digits: "Vui lòng cung cấp số điện thoại hợp lệ!!",
                minlength: "Vui lòng cung cấp số điện thoại hợp lệ!!",
            },
            "contact[subject]": "Vui lòng điền tiêu đề",
            "contact[content]": "Vui lòng nhập lời nhắn!"
        }

        contact_form.validate({
            onfocusout: false,
            onkeyup: false,
            onclick: false,
            rules: {
                "contact[name]": "required",
                "contact[email]": {
                    required: true,
                    email: true,
                },
                // "contact[email_confirm]": {
                //     email: true,
                //     equalTo: "#cf-email"
                // },
                "contact[phone]": {
                    number: true,
                    digits: true,
                    minlength: 10,
                },
                "contact[subject]": "required",
                "contact[content]": "required",
            },
            messages: error_messages,
            errorElement: "div",
            errorLabelContainer: ".errorTxt",
            invalidHandler: function(event, validator) {
                $("html, body").animate({
                        scrollTop: contact_form.offset().top
                    },
                    500
                );
            },
        });
    </script>
@endpush

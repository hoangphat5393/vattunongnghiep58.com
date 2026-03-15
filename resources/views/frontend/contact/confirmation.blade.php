@extends('frontend.layouts.master')

@section('seo')
    @include('frontend.layouts.seo', $seo ?? [])
@endsection
{{-- 
@php
    dd($data);
@endphp --}}

@section('content')
    <main class="main">
        <!-- Page Header Start -->
        <div class="container-fluid page-header py-5">
            <div class="container text-center">
                <h5 class="animated slideInDown mb-3">@lang('Contact confirmation')</h5>
                <nav aria-label="breadcrumb animated slideInDown">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">@lang('Home')</a></li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('Contact confirmation')</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header End -->

        <!-- Contact Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-7">
                        <div class="attention-info">
                            @if (app()->getLocale() == 'vi')
                                {!! htmlspecialchars_decode(setting_option('Contact_confirmation_vi')) !!}
                            @else
                                {!! htmlspecialchars_decode(setting_option('Contact_confirmation')) !!}
                            @endif
                        </div>
                        <div class="form-info">
                            <form id="contact-form" action="{{ route('contact.submit') }}" method="post" novalidate="novalidate">
                                @csrf
                                {{-- <input type="hidden" name="_token" value="{{ csrf_token() }}" /> --}}

                                {{-- {{ Form::hidden('url', URL::previous()) }} --}}
                                <div class="row mb-3">
                                    <label class="col-md-3 col-form-label text-md-end" for="cf-name">@lang('Name')</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="cf-name" name="contact[name]" value="{{ $data['name'] }}" placeholder="@lang('First and last name')(*)" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-3 col-form-label text-md-end" for="cf-email">@lang('Email')</label>
                                    <div class="col-md-8">
                                        <input type="email" class="form-control" id="cf-email" name="contact[email]" value="{{ $data['email'] }}" placeholder="@lang('Email')(*)" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-3 col-form-label text-md-end" for="cf-subject">@lang('Subject')</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="cf-subject" name="contact[subject]" value="{{ $data['subject'] }}" placeholder="@lang('Subject')" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-3 col-form-label text-md-end" for="cf-message">@lang('Inquiry contents')</label>
                                    <div class="col-md-8">
                                        <textarea name="contact[message]" class="form-control" id="cf-message" rows="3" readonly>{{ $data['message'] }}</textarea>
                                    </div>
                                </div>
                                <div class="submit-btn">
                                    <a href="{{ URL::previous() }}" class="btn btn-contact btn-submit-contact">@lang('Back')</a>
                                    <button type="submit" class="btn btn-contact btn-submit-contact">@lang('Confirm')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Contact End -->
    </main>
@endsection


@push('scripts')
    <script>
        // CONTACT
        var contact_form = $("#contact-form");
        var lc = '{{ app()->getLocale() }}';
        var error_messages = {};
        if (lc == 'en') {
            error_messages = {
                "contact[name]": "Please fill in you name!",
                "contact[email]": {
                    required: "Please fill in your email address!",
                    email: "Please enter valid email address",
                },
                "contact[email_confirm]": {
                    email: "Please enter valid email address",
                    equalTo: "Email does not match"
                },
                "contact[phone]": {
                    required: "Please enter valid phone number",
                    number: "Please provide valid phone number!!",
                    digits: "Please provide valid phone number!!",
                    minlength: "Please provide valid phone number!!",
                },
                "contact[subject]": "Please fill in the box",
                "contact[message]": "Please fill in message!"
            }
        } else {
            error_messages = {
                "contact[name]": "Vui lòng điền tên!",
                "contact[email]": {
                    required: "Vui lòng điền địa chỉ email!",
                    email: "Vui lòng nhập địa chỉ email hợp lệ",
                },
                "contact[phone]": {
                    required: "Vui lòng số điện thoại hợp lệ",
                    number: "Vui lòng cung cấp số điện thoại hợp lệ!!",
                    digits: "Vui lòng cung cấp số điện thoại hợp lệ!!",
                    minlength: "Vui lòng cung cấp số điện thoại hợp lệ!!",
                },
                "contact[subject]": "Vui lòng điền tiêu đề",
                "contact[message]": "Vui lòng nhập lời nhắn!"
            }
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
                "contact[phone]": {
                    number: true,
                    digits: true,
                    minlength: 10,
                },
                "contact[subject]": "required",
                "contact[message]": "required",
            },
            messages: error_messages,
            errorElement: "div",
            errorLabelContainer: ".errorTxt",
            invalidHandler: function(event, validator) {
                $("html, body").animate({
                        scrollTop: 0,
                    },
                    500
                );
            },
        });

        // $(".btn-submit-contact").on("click", function() {
        //     $(".list-content-loading").show();

        //     var action = $(this).closest("form").attr("action"),
        //         form = document.getElementById("contact-form"),
        //         fdnew = new FormData(form);
        //     axios({
        //             method: "post",
        //             url: action,
        //             data: fdnew,
        //         })
        //         .then((res) => {
        //             $(".list-content-loading").hide();
        //             if (res.data.error == 0) {
        //                 $("#modalContact").modal("hide");
        //                 $("footer").find("#notifyModal").remove();
        //                 $("footer").append(res.data.view);
        //                 $("#notifyModal").modal("show");
        //             } else {
        //                 $("#contact-form .text-error").text(res.data.message);
        //             }
        //         })
        //         .catch((e) => console.log(e));
        // });
    </script>
@endpush

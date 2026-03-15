@extends('frontend.layouts.master')

@section('content')
    <div id="page-content" class="page-template mt-5">
        <!--Page Title-->
        <div class="page section-header text-center">
            <div class="page-title">
                <div class="wrapper">
                    <h1 class="page-width">Login</h1>
                </div>
            </div>
        </div>
        <!--End Page Title-->

        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 main-col offset-md-3">
                    <div class="mb-4">
                        <form method="post" action="{{ route('loginCustomerAction') }}" id="form-login-page" accept-charset="UTF-8">
                            <div class="list-content-loading">
                                <div class="half-circle-spinner">
                                    <div class="circle circle-1"></div>
                                    <div class="circle circle-2"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-floating form-floating-custom icon">
                                        <img class="form-icon" src="{{ asset($templateFile . '/images/email-icon.png') }}" alt="Email">
                                        <input type="text" class="form-control form-control-custom" id="email" name="email" placeholder="Your Email" value="{{ $email ?? '' }}">
                                        <label for="email" class="float-label-custom">Email</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-floating form-floating-custom icon">
                                        <img class="form-icon" src="{{ asset($templateFile . '/images/lock-icon.png') }}" alt="Phone">
                                        <input type="password" class="form-control form-control-custom" id="password" name="password" placeholder="Password">
                                        <label for="ship_mail" class="float-label-custom">Password</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="error-message"></div>
                                </div>
                                <div class="text-center col-12 col-sm-12 col-md-12 col-lg-12">
                                    <button type="button" class="btn mb-3 btn-custom btn-login-page">@lang('Sign In')</button>
                                    <p class="mb-4">
                                        <a href="{{ route('forgetPassword') }}" id="RecoverPassword">Forgot your
                                            password?</a> &nbsp; | &nbsp;
                                        <a href="{{ route('registerCustomer') }}" id="customer_register_link">Create
                                            account</a>
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!--================================= Modal login -->
    <div class="modal login fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="list-content-loading">
                    <div class="half-circle-spinner">
                        <div class="circle circle-1"></div>
                        <div class="circle circle-2"></div>
                    </div>
                </div>

                <div class="modal-header border-0">
                    <h5 class="modal-title" id="loginModalLabel">Đăng nhập</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>
    </div>
@endsection

@push('after-footer')
    <script>
        jQuery(document).ready(function($) {
            var login_page = $('#form-login-page');
            login_page.validate({
                onfocusout: false,
                onkeyup: false,
                onclick: false,
                rules: {
                    email: "required",
                    password: "required",
                },
                messages: {
                    email: "Nhập địa chỉ E-mail",
                    password: "Nhập mật khẩu",
                },
                errorElement: 'div',
                errorLabelContainer: '.errorTxt',
                invalidHandler: function(event, validator) {
                    $('html, body').animate({
                        scrollTop: 0
                    }, 500);
                }
            });

            $('.btn-login-page').click(function(event) {
                if (login_page.valid()) {
                    var form = document.getElementById('form-login-page');
                    var fdnew = new FormData(form);
                    login_page.find('.list-content-loading').show();
                    axios({
                        method: 'POST',
                        url: '/customer/login',
                        data: fdnew,

                    }).then(res => {
                        console.log(res.data);
                        if (res.data.error == 0) {
                            $('#loginModal').find('.modal-body').html(res.data.view);
                            $('#loginModal').find('.modal-footer').remove();
                            $('#loginModal').modal('show');

                            $('#loginModal').on('hidden.bs.modal', function(e) {
                                window.location.href = "/";
                            })
                        } else {
                            login_page.find('.list-content-loading').hide();
                            login_page.find('.error-message').html(res.data.msg);
                        }
                        // login_page.find('.list-content-loading').hide();
                    }).catch(e => console.log(e));
                }
            });
        });
    </script>
@endpush

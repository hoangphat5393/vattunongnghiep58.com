@extends('theme.layout.index')

@section('seo')
<title>{{ 'Đăng ký thành viên | '.setting_option('seo-title-add') }}</title>
<link rel="canonical" href="{{ url('/') }}" />
<meta name="robots" content="index, follow">
<meta name="description" content="{{ 'Đăng ký thành viên | '.setting_option('seo-description-add') }}">
<meta property="og:title" content="{{ setting_option('og-title') }}" />
<meta property="og:description" content="{{ setting_option('og-description') }}" />
<meta property="og:image" content="{{ url(setting_option('og-image')) }}" />
<meta property="og:url" content="{{ url()->current() }}" />
<meta property="og:site_name" content="{{ setting_option('og-site-name') }}" />
@endsection
@section('body_class', 'user-page')


@section('content')
<section class="space-ptb" id="page-register">
    <div class="container page-register-content position-relative">
        <div class="section-title">
          <h2 class="text-center">@lang('Đăng ký thành viên')</h2>
        </div>

        
            <div class="list-content-loading" style="top: 0;">
                <div class="half-circle-spinner" style="top: 50%;">
                    <div class="circle circle-1"></div>
                    <div class="circle circle-2"></div>
                </div>
            </div>
            <form id="page-customer-register" class="form-customer-register" role="form" method="POST" action="{{route('postRegisterCustomer')}}">
                {{ csrf_field() }}
                <div class="row mt-2 mb-5 align-items-center">
                    <div class="mb-3 col-sm-12">
                        <label for="name" class="control-label">Họ tên<span class="required">*</span></label>
                        <input id="name" type="text" class="form-control" placeholder="Họ tên" name="name" value="{{ old('name') }}">
                    </div>
                    <div class="mb-3 col-sm-12">
                        <label for="phone" class="control-label">Số điện thoại<span class="required">*</span></label>
                        <input id="phone" type="text" class="form-control" placeholder="Số điện thoại" name="phone" value="{{ old('phone') }}">
                    </div>
                    <div class="mb-3 col-sm-12">
                        <label for="email" class="control-label">Email<span class="required">*</span></label>
                        <input id="email" type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}">
                    </div>
                    <div class="mb-3 col-sm-12">
                        <label for="password" class="control-label">Mật khẩu<span class="required">*</span></label>
                        <input id="password" type="password" placeholder="Mật khẩu" class="form-control" name="password">
                    </div>
                    <div class="mb-3 col-sm-12">
                        <label for="password-confirm" class="control-label">Xác nhận mật khẩu<span class="required">*</span></label>
                        <input id="password-confirm" type="password" placeholder="Xác nhận mật khẩu" class="form-control" name="password_confirmation">
                    </div>
                    <div class="mb-3 col-sm-12">
                        <div class="error-message"></div>
                    </div>
                    <div class="col-sm-6 d-grid">
                        <button type="button" class="btn btn-primary btn-register">Đăng ký</button>
                    </div>
                    <div class="col-sm-6">
                        <ul class="list-unstyled d-flex mb-1 mt-sm-0 mt-3">
                            <li class="me-1">
                                <a data-bs-toggle="modal" data-bs-target="#loginModal" href="#" data-bs-dismiss="modal"><b>Bạn đã có tài khoản, Click vào đây để đăng nhập</b></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </form>
    </div>
</section>
@endsection

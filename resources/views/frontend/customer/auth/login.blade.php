@php
    extract($data);
    if ($lc == 'en') {
        $lk = '';
    } else {
        $lk = $lc;
    }
@endphp

@extends('theme.layout.index')

@section('seo')
    @include('theme.layout.seo')
@endsection
@section('body_class', 'user-page')
@section('content')
    <!--=================================
    Login -->
    <section class="space-ptb login">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-sm-10">
                    <div class="section-title">
                        <h2 class="text-center">@lang('Đăng nhập')</h2>
                    </div>
                    @if (count($errors) > 0)
                        @foreach ($errors->all() as $error)
                            <div class="text-danger"> {{ $error }}</div>
                        @endforeach
                    @endif
                    @if (session('status'))
                        <div class="text-danger"> {{ session('status') }}</div>
                    @endif
                    <div class="list-content-loading">
                        <div class="half-circle-spinner">
                            <div class="circle circle-1"></div>
                            <div class="circle circle-2"></div>
                        </div>
                    </div>
                    <form id="form-login-page" class="form-horizontal login row align-items-center" method="POST" action="{{ route('customer.login.submit') }}">
                        <div class="error-message"></div>
                        {{ csrf_field() }}
                        <div class="mb-3 col-sm-12">
                            <label>Email <span class="required">*</span></label>
                            <input type="text" class="form-control" name="email" value="" />
                        </div>
                        <div class="mb-3 col-sm-12">
                            <label>Mật khẩu <span class="required">*</span></label>
                            <input class="form-control" type="password" name="password" />
                        </div>
                        <div class="mb-3 col-sm-12">
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" name="remember_me" id="remember_me">
                                <label class="custom-control-label" for="remember_me">@lang('Ghi nhớ')</label>
                            </div>
                        </div>
                        <div class="col-sm-6 d-grid">
                            <button type="button" class="btn btn-primary btn-login-page">@lang('Đăng nhập')</button>
                        </div>
                        <div class="col-sm-6">
                            <ul class="list-unstyled d-flex mb-1 mt-sm-0 mt-3">
                                <li class="me-1">
                                    <a data-bs-toggle="modal" data-bs-target="#registerModal" href="#">
                                        <b>Bạn chưa có tài khoản? Click vào đây để đăng ký</b>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12 mt-3">
                            <ul class="list-unstyled d-flex mb-1 mt-sm-0 mt-3">
                                <li class="me-1">
                                    <a href="{{ route('customer.password.forgot') }}"><b>Quên mật khẩu?</b></a>
                                </li>
                            </ul>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>
    <!--=================================
    Login -->

@endsection

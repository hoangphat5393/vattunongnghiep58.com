@extends('frontend.layouts.master')
@section('seo')
    @include('frontend.layouts.seo', $seo ?? [])
@endsection

@section('body_class', 'user-page')

{{-- <div class="section-title">
        <h2 class="text-center">@lang('Quên mật khẩu - Bước 2')</h2>
    </div> --}}


@section('content')
    <div class="space-ptb login py-5 forget-pass">
        <div class="container">
            <div class="breadcrumbs">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item">Forget Password</li>
                    <li class="breadcrumb-item">Verify Code</li>
                </ol>
            </div>

            <div class="row justify-content-center mt-4">
                <div class="col-lg-6">
                    <div class="form-block">

                        <h1>VERIFY CODE</h1>
                        <p>Please enter your verify code was sent in your email</p>

                        @if (count($errors) > 0)
                            @foreach ($errors->all() as $error)
                                <div class="text-danger">
                                    <p>{{ $error }}</p>
                                </div>
                            @endforeach
                        @endif
                        @if (session('status'))
                            <div class="text-danger"> {{ session('status') }}</div>
                        @endif

                        <form class="form-horizontal" method="POST" action="{{ route('actionForgetPassword_step2') }}">
                            {{ csrf_field() }}

                            <div class="form-floating form-floating-custom icon mb-3">
                                <img class="form-icon" src="{{ asset($templatePath . '/images/user-icon.png') }}" alt="Email">
                                <input type="text" class="form-control form-control-custom" id="si-email" name="otp_mail" placeholder="OTP in email" value="{{ old('email') }}" required autofocus>
                                <label for="si-email" class="float-label-custom">OTP</label>
                                @if ($errors->has('otp_mail'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('otp_mail') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="mb-3 text-center mb-0">
                                <button type="submit" class="btn btn-custom w-100">Change password</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

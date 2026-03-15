@extends('frontend.layouts.master')
@section('seo')
    @include('frontend.layouts.seo', $seo ?? [])
@endsection

@section('body_class', 'user-page')

@section('content')

    <section class="space-ptb login py-5 forget-pass">
        <div class="container">

            <div class="breadcrumbs">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item">Forget Password</li>
                    <li class="breadcrumb-item">Update Password</li>
                </ol>
            </div>

            <div class="row justify-content-center mt-4">

                <div class="col-lg-6">
                    <div class="form-block">

                        <h1>UPDATE PASSWORD</h1>
                        <p>set new password for your account</p>

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

                        <form class="form-horizontal" method="POST" action="{{ route('actionForgetPassword_step3') }}">
                            {{ csrf_field() }}

                            <div class="mb-3">
                                <div class="form-floating form-floating-custom icon password-toggle">
                                    <img class="form-icon" src="{{ asset($templatePath . '/images/lock-icon.png') }}" alt="password">
                                    <input type="password" class="form-control form-control-custom" id="new_password" name="new_password" placeholder="Password" required autofocus>
                                    <label for="new_password" class="float-label-custom">Password</label>
                                </div>
                                {{-- @if ($errors->has('new_password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('new_password') }}</strong>
                                    </span>
                                @endif --}}
                            </div>

                            <div class="mb-3">
                                <div class="form-floating form-floating-custom icon password-toggle">
                                    <img class="form-icon" src="{{ asset($templatePath . '/images/lock-icon.png') }}" alt="Re-type password">
                                    <input type="password" class="form-control form-control-custom" id="confirm_new_password" name="confirm_new_password" placeholder="Re-type password" required>
                                    <label for="confirm_new_password" class="float-label-custom">Re-type password</label>
                                </div>
                                {{-- @if ($errors->has('confirm_new_password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('confirm_new_password') }}</strong>
                                    </span>
                                @endif --}}
                            </div>

                            {{-- <div class="input-group form-group mb-3">
                                <input type="password" name="new_password" class="form-control" placeholder="Mật khẩu mới" required autofocus>
                                @if ($errors->has('new_password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('new_password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="input-group form-group mb-3">
                                <input type="password" class="form-control" placeholder="Xác nhận mật khẩu mới" name="confirm_new_password" required>
                                @if ($errors->has('confirm_new_password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('confirm_new_password') }}</strong>
                                    </span>
                                @endif
                            </div> --}}
                            <div class="mb-3 text-center mb-0">
                                <button type="submit" class="btn btn-custom w-100">Update New Password</button>
                            </div>
                        </form>

                    </div>

                </div>
            </div>
        </div>
    </section>

@endsection

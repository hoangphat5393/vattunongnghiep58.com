@extends('frontend.layouts.master')
@section('seo')
    @include('frontend.layouts.seo', $seo ?? [])
@endsection

@section('body_class', 'user-page')

@section('content')
    <div id="page-content" class="page-template py-5 forget-pass">

        <div class="container">
            <div class="breadcrumbs">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item">Forget Password</li>
                </ol>
            </div>

            <div class="row justify-content-center mt-4">
                <div class="col-lg-6">
                    <div class="form-block">

                        <h1>@lang('Forget password')</h1>
                        <p>Please enter your registered email-address or phone number and we’ll send you reset password method</p>

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

                        <form class="form-horizontal" method="POST" action="{{ route('actionForgetPassword') }}">
                            {{ csrf_field() }}

                            <div class="form-floating form-floating-custom icon mb-3">
                                <img class="form-icon" src="{{ asset($templatePath . '/images/user-icon.png') }}" alt="Email">
                                <input type="text" class="form-control form-control-custom" id="email" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus>
                                <label for="email" class="float-label-custom">Email </label>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="mb-3 text-center mb-0">
                                <button type="submit" class="btn btn-custom w-100">Retrived Link or Code</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

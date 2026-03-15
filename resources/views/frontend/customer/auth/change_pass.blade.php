@php
    extract($data);

    $avatar = url('theme/images/user-none.png');
    if (auth()->user()->avatar) {
        // $avatar = url('img/users/avatar/' . $user->avatar);
        $avatar = url($user->avatar);
    }
@endphp

@extends('frontend.layouts.master')
@section('seo')
    @include('frontend.layouts.seo', $seo ?? [])
@endsection

@section('content')
    <section id="user-change-pass" class="py-5 my-post position-relative customer">

        <div class="container">
            <div class="breadcrumbs">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active">User Profile</li>
                </ol>
            </div>

            <h1 class="text-white">CHANGE PASSWORD</h1>
            @include($templatePath . '.customer.includes.header-customer')

            <div class="row justify-content-end">

                <div class="col-lg-3 col-12 mb-4">
                    @include($templatePath . '.customer.includes.sidebar-customer')
                </div>

                <div class="col-lg-9 col-12">
                    <div class="block-content p-4">
                        <h2 class="mb-3 text-center text-lg-start">Change Password</h2>
                        <form action="{{ route('customer.post.ChangePassword') }}" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                            @csrf
                            <div class="row justify-content-center justify-content-lg-start">
                                <div class="col-md-6 col-lg-5">
                                    @if (count($errors) > 0)
                                        @foreach ($errors->all() as $error)
                                            <div class="text-danger mb-3"> {{ $error }}</div>
                                        @endforeach
                                    @endif

                                    @if (session('status'))
                                        <div class="text-danger mb-3"> {{ session('status') }}</div>
                                    @endif

                                    {{-- <div class="form-groupmb-3">
                                        <label class="mb-2">Mật khẩu hiện tại</label>
                                        <input type="password" class="form-control" name="current_password">
                                    </div> --}}

                                    <div class="form-floating form-floating-custom icon mb-3">
                                        <img class="form-icon" src="{{ asset($templateFile . '/images/lock-icon.png') }}" alt="Current Password">
                                        <input type="password" class="form-control form-control-custom" id="current_password" name="current_password" placeholder="Current Password">
                                        <label for="current_password" class="float-label-custom">Current Password</label>
                                    </div>

                                    {{-- <div class="form-group mb-3">
                                        <label class="mb-2">Mật khẩu mới</label>
                                        <input type="password" class="form-control" name="new_password" value="">
                                    </div> --}}

                                    <div class="form-floating form-floating-custom icon mb-3">
                                        <img class="form-icon" src="{{ asset($templateFile . '/images/lock-icon.png') }}" alt="New Password">
                                        <input type="password" class="form-control form-control-custom" id="new_password" name="new_password" placeholder="New Password">
                                        <label for="new_password" class="float-label-custom">New Password</label>
                                    </div>

                                    {{-- <div class="form-group  mb-3">
                                        <label class="mb-2">Nhập lại mật khẩu mới</label>
                                        <input type="password" class="form-control" name="confirm_password" value="">
                                    </div> --}}

                                    <div class="form-floating form-floating-custom icon mb-3">
                                        <img class="form-icon" src="{{ asset($templateFile . '/images/lock-icon.png') }}" alt="Re-type password">
                                        <input type="password" class="form-control form-control-custom" id="confirm_password" name="confirm_password" placeholder="Re-type password">
                                        <label for="confirm_password" class="float-label-custom">Re-type password</label>
                                    </div>
                                </div>
                                    <div class="mb-3 col-lg-12 mb-3 text-center text-lg-start">
                                    <button type="submit" class="btn btn-custom ">CHANGE</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>


    @push('after-footer')
        <script src="{{ asset('theme/js/customer.js') }}"></script>
    @endpush
@endsection

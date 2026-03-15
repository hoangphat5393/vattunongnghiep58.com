@php
    $states = \App\Models\Province::get();

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
    <section id="user-profile" class="py-5 my-post position-relative customer">

        <div class="container">
            <div class="breadcrumbs">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active">User Profile</li>
                </ol>
            </div>

            <h1 class="text-white">PROFILE SUMMARY</h1>
            @include($templatePath . '.customer.includes.header-customer')

            <div class="row justify-content-end">
                <div class="col-lg-3 col-12 mb-4">
                    @include($templatePath . '.customer.includes.sidebar-customer')
                </div>
                <div class="col-lg-9 col-12">
                    <div class="block-content p-4">
                        <h2 class="mb-3">My Profile</h2>
                        <p class="fw-bold">Basic Information</p>
                        <p class="text-note px-3 py-1">
                            <img src="{{ asset($templateFile . '/images/info-icon.png') }}" alt="info"> You may use any of registered Email, Phone Number or Username to access user profile
                        </p>
                        <form action="{{ route('customer.updateprofile') }}" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                            @csrf
                            <div class="row align-items-center mb-3">
                                <div class="col">
                                    <label class="form-label d-flex align-items-center">
                                        <img src="{{ $avatar }}" class="icon_menu img-fluid d-block rounded-circle avatar" width="60">
                                        <div class="ms-3">Avatar</div>
                                    </label>
                                </div>
                                <div class="col">
                                    <small>Please choose a image about the size 500px x 500px</small>
                                    <div class="d-flex">
                                        <input type="file" name="avatar_upload" class="form-control" id="customFile" style="visibility:hidden;">
                                        <label class="input-group-text label-upload-custom" for="customFile">Select file</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label d-flex align-items-center">
                                        <img src="/upload/images/general/user-circle.png" class="icon_menu" width="60">
                                        <div class="ms-3">
                                            Username<br>
                                            <span class="text-white">{{ auth()->user()->username }}</span>
                                        </div>
                                    </label>
                                </div>
                                {{-- <div class="col">
                                    <div class="form-floating form-floating-custom">
                                        <input type="text" class="col-9 form-control form-control-custom" id="floatingInput" name="username" placeholder="Modify your Username">
                                        <label for="floatingInput" class="float-label-custom">Username</label>
                                    </div>
                                </div> --}}
                            </div>

                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label d-flex align-items-center">
                                        <img src="/upload/images/general/user-circle.png" class="icon_menu" width="60">
                                        <div class="ms-3">
                                            Fullname<br>
                                            <span class="text-white">{{ $user->fullname }}</span>
                                        </div>
                                    </label>
                                </div>
                                <div class="col">
                                    <div class="form-floating form-floating-custom">
                                        <input type="text" class="col-9 form-control form-control-custom" id="fullname" name="fullname" value="{{ $user->fullname }}" placeholder="Modify your fullname">
                                        <label for="fullname" class="float-label-custom">Fullname</label>
                                    </div>
                                </div>
                            </div>


                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label d-flex align-items-center">
                                        <img src="/upload/images/general/mail-circle.png" class="icon_menu" width="60">
                                        <div class="ms-3">
                                            Email<br>
                                            <span class="text-muted">{{ $user->email }}</span>
                                        </div>
                                    </label>
                                </div>
                                {{-- <div class="col">
                                    <div class="form-floating form-floating-custom">
                                        <input type="text" class="col-9 form-control form-control-custom" id="floatingInput" placeholder="Modify your Email" readonly>
                                        <label for="floatingInput" class="float-label-custom">Email</label>
                                    </div>
                                </div> --}}
                            </div>

                            <div class="row">
                                <div class="col">
                                    <label class="form-label d-flex align-items-center">
                                        <img src="/upload/images/general/phone-circle.png" class="icon_menu" width="60">
                                        <div class="ms-3">
                                            Mobile<br>
                                            <span class="text-muted">{{ $user->phone }}</span>
                                        </div>
                                    </label>
                                </div>
                                <div class="col">
                                    <div class="input-group-custom input-group">
                                        <input type="tel" class="form-control" id="phone_number" name="phone" value="{{ $user->full_phone }}">
                                    </div>
                                    <!-- <div class="form-floating form-floating-custom">
                                                    <input type="text" class="col-9 form-control form-control-custom" id="phone" name="phone" value="{{ $user->phone }}" placeholder="Modify your mobile">
                                                    <label for="phone" class="float-label-custom">Mobile</label>
                                                </div> -->
                                </div>
                            </div>

                            {{-- <hr> --}}

                            {{-- <h6 class="mt-2">Address</h6>
                            <div class="row">
                                <div class="form-group col-md-6 mb-3">
                                    <label class="form-label mb-2">Address</label>
                                    <input type="text" class="form-control form-control-sm" name="address" value="{{ $user->address }}">
                                </div>
                                <div class="form-group col-md-6 col-lg-4 col-xl-4 required">
                                    <label class="form-label" for="state_province">District/City <span class="required-f">*</span></label>
                                    <select name="state" id="state_province" class="form-control form-control-sm">
                                        <option value=""> --- Select District/City --- </option>
                                        @foreach ($states as $state)
                                            <option value="{{ $state->id }}" {{ $state->id == $user->province ? 'selected' : '' }}>{{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> --}}
                            <div class="row">
                                <div class="mb-3 col-md-12 mb-3 text-center">
                                    <button type="submit" class="btn btn-custom">Save</button>
                                </div>
                            </div>
                        </form>

                        {{-- <h2 class="mb-3">Social Profile</h2> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('after-footer')
    <script>
        jQuery(document).ready(function($) {
            var phone_number = document.querySelector("#phone_number");
            window.intlTelInput(phone_number, {
                hiddenInput: "full_phone",
                preferredCountries: ["vn", "us", "gb", "co", "de"],
                utilsScript: "{{ asset('js/utils.js') }}"
            });
        });
    </script>
@endpush

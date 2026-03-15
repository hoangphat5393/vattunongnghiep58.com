@php
$states = \App\Models\Province::get();

$avatar = url('theme/images/user-none.png');
// url('img/users/avatar/' . $user->avatar)
if (auth()->user()->avatar) {
$avatar = url('img/users/avatar/' . $user->avatar);
}
@endphp

@extends('frontend.layouts.master')
@section('seo')
@include($templatePath . '.layouts.seo', $seo ?? [])
@endsection


@section('content')
<section id="customer" class="py-5 my-post position-relative">

    <div class="container">
        <div class="breadcrumbs">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">User Profile</li>
            </ol>
        </div>

        <h1>PROFILE SUMMARY</h1>
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="customer-intro">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <img style="max-width:180px" class="img-fluid d-block mx-auto rounded-circle avatar" src="{{ $avatar }}" alt="path to image">
                        </div>

                        <div class="col-md-5">
                            <dl class="row">
                                <dt class="col-sm-3">Username:</dt>
                                <dd class="col-sm-9">{{ auth()->user()->fullname }}</dd>
                            </dl>
                            <dl class="row">
                                <dt class="col-sm-3">Spent:</dt>
                                <dd class="col-sm-9">$ 100.00</dd>
                            </dl>
                            <dl class="row">
                                <dt class="col-sm-3">Orders:</dt>
                                <dd class="col-sm-9">20</dd>
                            </dl>
                        </div>
                        <div class="col-md-4 greeting">
                            <p>Welcome</p>
                            <p>{{ auth()->user()->fullname }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-end">
            <div class="col-lg-3 col-12 mb-4">
                @include($templatePath . '.customer.includes.sidebar-customer')
            </div>
            <div class="col-lg-9 col-12">
                <div class="block-content p-4">
                    <h2 class="mb-3">My Profile</h2>
                    <form action="{{ route('customer.updateprofile') }}" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-12 mt-3 mb-3">
                                <h6>Cập nhật ảnh đại diện</h6>
                                <p>Vui lòng chọn ảnh hình vuông, kích thước khoảng 500px x 500px</p>
                                <div class="input-group input-group-sm file-upload">
                                    <input type="file" name="avatar_upload" class="form-control" id="customFile">
                                    <label class="input-group-text" for="customFile">Chọn file</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6 mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control form-control-sm" name="fullname" value="{{ $user->fullname }}">
                            </div>
                            <div class="form-group col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="text" class="form-control form-control-sm" readonly value="{{ $user->email }}">
                            </div>
                            <div class="form-group col-md-6 mb-3">
                                <label class="form-label">Mobile</label>
                                <input type="text" class="form-control form-control-sm" name="phone" value="{{ $user->phone }}">
                            </div>
                        </div>
                        <hr>
                        <h6 class="mt-2">Address</h6>
                        <div class="row">
                            <div class="form-group col-md-6 mb-3">
                                <label class="form-label mb-2">Address</label>
                                <input type="text" class="form-control form-control-sm" name="address" value="{{ $user->address }}">
                            </div>
                            <div class="form-group col-md-6 col-lg-4 col-xl-4 required">
                                <label class="form-label" for="state_province">Select District/City <span class="required-f">*</span></label>
                                <select name="state" id="state_province" class="form-control form-control-sm">
                                    <option value=""> --- Select District/City --- </option>
                                    @foreach ($states as $state)
                                    <option value="{{ $state->id }}" {{ $state->id == $user->province ? 'selected' : '' }}>{{ $state->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12 mb-3 text-center">
                                <button type="submit" class="btn btn-custom">Save</button>
                            </div>
                        </div>
                    </form>

                    <h2 class="mb-3">Social Profile</h2>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('after-footer')
<script src="{{ asset('theme/js/customer.js') }}"></script>
@endpush
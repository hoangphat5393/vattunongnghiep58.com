@extends('backend.layouts.master')

@section('seo')
    @php
        $title_head = __('admin.profile');
        $seo = [
            'title' => $title_head,
            'keywords' => '',
            'description' => '',
            'og_title' => $title_head,
            'og_description' => '',
            'og_url' => Request::url(),
            'og_img' => asset('assets/images/logo_seo.png'),
            'current_url' => Request::url(),
            'current_url_amp' => '',
        ];
    @endphp
    @include('backend.partials.seo')
@endsection

<style>
    .wrap-pass {
        display: none
    }

    .avtive-wpap-pass {
        display: block
    }
</style>

@section('content')
    {{-- begin::App Content Header --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0">{{ $title_head }}</h1>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb" class="float-sm-end">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('admin.home')</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $title_head }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    {{-- end::App Content Header --}}


    {{-- begin::App Content --}}
    <div class="app-content">
        <div class="container-fluid">

            <div class="row">

                <div class="col-md-3">
                    <div class="card card-primary card-outline mb-3">
                        <div class="card-body box-profile">
                            <div class="text-center mb-3">
                                <img class="profile-user-img img-fluid img-circle" src="/assets/admin/assets/img/avatar5.png" alt="User profile picture">
                            </div>

                            <h3 class="profile-username text-center h5 fw-bold">{{ Auth::guard('admin')->user()->name ?? 'Administrator' }}</h3>

                            <p class="text-muted text-center small mb-0">{{ Auth::guard('admin')->user()->email ?? 'admin@local' }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    {{-- card --}}
                    <div class="card card-primary card-outline mb-4">

                        {{-- header --}}
                        <div class="card-header">
                            <h3 class="card-title">{{ $title_head }}</h3>
                        </div>

                        <div class="card-body">
                            <form id="frm-updateinfo-useradmin" action="{{ route('admin.postChangePassword') }}" method="POST">
                                @csrf
                                @foreach ($errors->all() as $error)
                                    <div class="text-error small mb-1">{{ $error }}</div>
                                @endforeach
                                <div class="js-validation-messages mb-2 small" role="alert"></div>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label for="post_title" class="form-label">@lang('admin.email')</label>
                                        <input type="text" class="form-control" id="post_title" name="email" placeholder="@lang('admin.email')" value="{{ Auth::guard('admin')->user()->email }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">@lang('admin.username')</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="@lang('admin.username')" value="{{ Auth::guard('admin')->user()->name }}">
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" name="check_pass" id="check_pass">
                                            <label class="form-check-label fw-bold" for="check_pass">
                                                @lang('admin.change password')
                                            </label>
                                        </div>
                                        <input type="hidden" id="check_pass_value" name="check_pass_value" value="off">
                                    </div>
                                </div>

                                {{-- wrap pass --}}
                                <div class="wrap-pass">
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-12">
                                            <label for="current_password" class="form-label">@lang('admin.current password')</label>
                                            <input type="password" class="form-control" name="current_password" placeholder="@lang('admin.current password')" id="current_password" disabled>
                                            <small class="text-error d-block mt-1" id="current-password-ajax-feedback" role="status"></small>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="new_password" class="form-label">@lang('admin.new password')</label>
                                            <input type="password" class="form-control" name="new_password" placeholder="@lang('admin.new password')" id="new_password" disabled>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="confirm_password" class="form-label">@lang('admin.confirm password')</label>
                                            <input type="password" class="form-control" name="confirm_password" placeholder="@lang('admin.confirm password')" id="confirm_password" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">@lang('admin.phone')</label>
                                        <input type="text" class="form-control" id="phone" name="phone" placeholder="@lang('admin.phone')" value="{{ Auth::guard('admin')->user()->phone }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="address" class="form-label">@lang('admin.address')</label>
                                        <input type="text" class="form-control" id="address" name="address" placeholder="@lang('admin.address')" value="{{ Auth::guard('admin')->user()->address }}">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer">
                            <input type="submit" form="frm-updateinfo-useradmin" class="btn btn-primary" value="@lang('admin.update')">
                        </div>
                    </div>
                    {{-- end::card --}}
                </div>
            </div>

        </div>
    </div>
    {{-- end::App Content --}}
@endsection


@push('scripts')
    <script>
        $(function() {
            $('input[name="check_pass"]').on('change click', function() {
                let check_pass_length = $('#check_pass:checked').length;

                if (check_pass_length == 1) {
                    //show pass
                    $('#current_password, #new_password, #confirm_password').removeAttr('disabled');
                    $('#check_pass_value').val('on');
                    $('.wrap-pass').stop(true, true).slideDown(350);
                } else {
                    //hide pass
                    $('#current_password, #new_password, #confirm_password').attr('disabled', 'true');
                    $('#check_pass_value').val('off');
                    $('.wrap-pass').stop(true, true).slideUp(300);
                }

                //check password equal
                $('#current_password').on('change', function() {
                    var current_password = $(this).val();
                    axios.get(admin_url + "/check-password", {
                            params: {
                                current_password: current_password
                            }
                        })
                        .then(function(response) {
                            $('#current-password-ajax-feedback').html(response.data);
                        })
                        .catch(function(e) {
                            console.error(e);
                        });
                });

                //validate
                $("#frm-updateinfo-useradmin").validate({
                    errorLabelContainer: '#frm-updateinfo-useradmin .js-validation-messages',
                    rules: {
                        email: "required",
                        name: "required",
                        current_password: "required",
                        new_password: "required",
                        repassword: {
                            equalTo: "#password"
                        },
                    },
                    messages: {
                        email: "Nhập email/tên đăng nhập",
                        name: "Nhập tên nhân viên",
                        current_password: "Nhập mật khẩu hiện tại",
                        new_password: "Nhập mật khẩu mới",
                        confirm_password: "Mật khẩu không chính xác",
                    },

                    invalidHandler: function(event, validator) {
                        $('html, body').animate({
                            scrollTop: 0
                        }, 500);
                    }
                });
                //end validate
            });
        });
    </script>
@endpush

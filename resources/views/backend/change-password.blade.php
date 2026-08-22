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

@push('style')
    <style>
        .wrap-pass {
            display: none;
        }

        .password-collapse-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0 !important;
            border-radius: 12px;
            padding: 1.25rem;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.02);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .wrap-pass.is-open {
            animation: passSlideFadeIn 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes passSlideFadeIn {
            0% {
                opacity: 0;
                transform: translateY(-8px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .toggle-password-visibility {
            cursor: pointer;
            border-left: 0;
        }

        .toggle-password-visibility:hover {
            background-color: #f1f5f9;
        }

        .form-check-switch-custom .form-check-input {
            cursor: pointer;
            width: 2.75em;
            height: 1.4em;
            margin-right: 0.5rem;
        }
    </style>
@endpush

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
                                    <div class="alert alert-danger small py-2 mb-3">{{ $error }}</div>
                                @endforeach
                                <div class="js-validation-messages mb-2 small" role="alert"></div>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label for="post_title" class="form-label fw-semibold">@lang('admin.email') <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="post_title" name="email" placeholder="@lang('admin.email')" value="{{ Auth::guard('admin')->user()->email }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="name" class="form-label fw-semibold">@lang('admin.username') <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="@lang('admin.username')" value="{{ Auth::guard('admin')->user()->name }}" required>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-check form-switch form-check-switch-custom d-flex align-items-center mt-2">
                                            <input class="form-check-input" type="checkbox" role="switch" value="" name="check_pass" id="check_pass">
                                            <label class="form-check-label fw-bold user-select-none" for="check_pass">
                                                <i class="fa-solid fa-key text-primary me-1"></i> @lang('admin.change password')
                                            </label>
                                        </div>
                                        <input type="hidden" id="check_pass_value" name="check_pass_value" value="off">
                                    </div>
                                </div>

                                {{-- wrap pass --}}
                                <div class="wrap-pass mb-3">
                                    <div class="password-collapse-card">
                                        <div class="d-flex align-items-center mb-3 text-muted small">
                                            <i class="fa-solid fa-shield-halved me-2 text-warning fs-6"></i>
                                            <span>Vui lòng nhập mật khẩu hiện tại để xác thực và thiết lập mật khẩu mới</span>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <label for="current_password" class="form-label fw-semibold">@lang('admin.current password') <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-white"><i class="fa-solid fa-lock text-muted"></i></span>
                                                    <input type="password" class="form-control" name="current_password" placeholder="@lang('admin.current password')" id="current_password" autocomplete="current-password" disabled>
                                                    <button class="btn btn-outline-secondary toggle-password-visibility" type="button" tabindex="-1" title="Hiện/ẩn mật khẩu">
                                                        <i class="fa-regular fa-eye"></i>
                                                    </button>
                                                </div>
                                                <small class="text-error d-block mt-1" id="current-password-ajax-feedback" role="status"></small>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="new_password" class="form-label fw-semibold">@lang('admin.new password') <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-white"><i class="fa-solid fa-key text-muted"></i></span>
                                                    <input type="password" class="form-control" name="new_password" placeholder="@lang('admin.new password')" id="new_password" autocomplete="new-password" disabled>
                                                    <button class="btn btn-outline-secondary toggle-password-visibility" type="button" tabindex="-1" title="Hiện/ẩn mật khẩu">
                                                        <i class="fa-regular fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="confirm_password" class="form-label fw-semibold">@lang('admin.confirm password') <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-white"><i class="fa-solid fa-check-double text-muted"></i></span>
                                                    <input type="password" class="form-control" name="confirm_password" placeholder="@lang('admin.confirm password')" id="confirm_password" autocomplete="new-password" disabled>
                                                    <button class="btn btn-outline-secondary toggle-password-visibility" type="button" tabindex="-1" title="Hiện/ẩn mật khẩu">
                                                        <i class="fa-regular fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label fw-semibold">@lang('admin.phone')</label>
                                        <input type="text" class="form-control" id="phone" name="phone" placeholder="@lang('admin.phone')" value="{{ Auth::guard('admin')->user()->phone }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="address" class="form-label fw-semibold">@lang('admin.address')</label>
                                        <input type="text" class="form-control" id="address" name="address" placeholder="@lang('admin.address')" value="{{ Auth::guard('admin')->user()->address }}">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer bg-transparent">
                            <button type="submit" form="frm-updateinfo-useradmin" class="btn btn-primary px-4">
                                <i class="fa-solid fa-floppy-disk me-1"></i> @lang('admin.update')
                            </button>
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
            // Checkbox switch change event with smooth slide & fade animation
            $('#check_pass').on('change', function() {
                var isChecked = this.checked;
                var $wrapPass = $('.wrap-pass');
                var $passInputs = $('#current_password, #new_password, #confirm_password');

                if (isChecked) {
                    $passInputs.prop('disabled', false);
                    $('#check_pass_value').val('on');
                    $wrapPass.stop(true, true).addClass('is-open').slideDown({
                        duration: 350,
                        easing: 'swing',
                        complete: function() {
                            $('#current_password').focus();
                        }
                    });
                } else {
                    $passInputs.prop('disabled', true).val('');
                    $('#check_pass_value').val('off');
                    $('#current-password-ajax-feedback').empty();
                    $wrapPass.stop(true, true).removeClass('is-open').slideUp({
                        duration: 250,
                        easing: 'swing'
                    });
                }
            });

            // Toggle password visibility (Eye icon)
            $(document).on('click', '.toggle-password-visibility', function(e) {
                e.preventDefault();
                var $input = $(this).closest('.input-group').find('input');
                var $icon = $(this).find('i');
                if ($input.attr('type') === 'password') {
                    $input.attr('type', 'text');
                    $icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    $input.attr('type', 'password');
                    $icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });

            // Check current password via AJAX
            $('#current_password').on('blur change', function() {
                var current_password = $(this).val();
                if (!current_password) return;

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

            // Form validation
            $("#frm-updateinfo-useradmin").validate({
                errorElement: 'div',
                errorClass: 'text-danger small mt-1',
                errorPlacement: function(error, element) {
                    if (element.closest('.input-group').length) {
                        error.insertAfter(element.closest('.input-group'));
                    } else {
                        error.insertAfter(element);
                    }
                },
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    name: "required",
                    current_password: {
                        required: function() {
                            return $('#check_pass').is(':checked');
                        }
                    },
                    new_password: {
                        required: function() {
                            return $('#check_pass').is(':checked');
                        },
                        minlength: 6
                    },
                    confirm_password: {
                        required: function() {
                            return $('#check_pass').is(':checked');
                        },
                        equalTo: "#new_password"
                    }
                },
                messages: {
                    email: {
                        required: "Vui lòng nhập email",
                        email: "Email không đúng định dạng"
                    },
                    name: "Vui lòng nhập tên tài khoản",
                    current_password: "Vui lòng nhập mật khẩu hiện tại",
                    new_password: {
                        required: "Vui lòng nhập mật khẩu mới",
                        minlength: "Mật khẩu mới tối thiểu 6 ký tự"
                    },
                    confirm_password: {
                        required: "Vui lòng xác nhận mật khẩu mới",
                        equalTo: "Mật khẩu xác nhận không khớp"
                    }
                }
            });
        });
    </script>
@endpush

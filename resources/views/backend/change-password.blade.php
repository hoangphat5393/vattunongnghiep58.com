@extends('backend.layouts.master')

@section('seo')
    @php
        $title_head = __('profile');
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
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
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
                            <div class="text-center">
                                <img class="profile-user-img img-fluid img-circle" src="/assets/admin/assets/img/avatar5.png" alt="User profile picture">
                            </div>

                            <h3 class="profile-username text-center">Nina Mcintire</h3>

                            <p class="text-muted text-center">Software Engineer</p>

                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Followers</b> <a class="float-end">1,322</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Following</b> <a class="float-end">543</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Friends</b> <a class="float-end">13,287</a>
                                </li>
                            </ul>

                            <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>
                        </div>
                        <!-- /.card-body -->
                    </div>

                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">About Me</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <strong><i class="fas fa-book mr-1"></i> Education</strong>

                            <p class="text-muted">
                                B.S. in Computer Science from the University of Tennessee at Knoxville
                            </p>

                            <hr>

                            <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>

                            <p class="text-muted">Malibu, California</p>

                            <hr>

                            <strong><i class="fas fa-pencil-alt mr-1"></i> Skills</strong>

                            <p class="text-muted">
                                <span class="tag tag-danger">UI Design</span>
                                <span class="tag tag-success">Coding</span>
                                <span class="tag tag-info">Javascript</span>
                                <span class="tag tag-warning">PHP</span>
                                <span class="tag tag-primary">Node.js</span>
                            </p>

                            <hr>

                            <strong><i class="far fa-file-alt mr-1"></i> Notes</strong>

                            <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam fermentum enim neque.</p>
                        </div>
                        <!-- /.card-body -->
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
                                        <input type="text" class="form-control title_slugify" id="post_title" name="email" placeholder="Email/Username" value="{{ Auth::guard('admin')->user()->email }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">@lang('admin.username')</label>
                                        <input type="text" class="form-control slug_slugify" id="name" name="name" placeholder="Username" value="{{ Auth::guard('admin')->user()->name }}">
                                    </div>
                                    <div class="col-md-12">
                                        <label for="check_pass" class="form-label">@lang('admin.change password')</label>
                                        <input type="checkbox" value="" name="check_pass" id="check_pass">
                                        <input type="hidden" id="check_pass_value" name="check_pass_value" value="off">
                                    </div>
                                </div>

                                {{-- wrap pass --}}
                                <div class="wrap-pass">
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-12">
                                            <label for="current_password" class="form-label">@lang('admin.current password')</label>
                                            <input type="password" class="form-control" name="current_password" palceholder="@lang('admin.current password')" id="current_password" class="form-control" disabled>
                                            <small class="text-error d-block mt-1" id="current-password-ajax-feedback" role="status"></small>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="new_password" class="form-label">@lang('admin.new password')</label>
                                            <input type="password" class="form-control" name="new_password" palceholder="@lang('admin.current password')" id="new_password" class="form-control" disabled>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="confirm_password" class="form-label">@lang('admin.confirm password')</label>
                                            <input type="password" class="form-control" name="confirm_password" id="confirm_password" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">@lang('admin.phone')</label>
                                        <input type="text" class="form-control slug_slugify" id="phone" name="phone" placeholder="@lang('admin.phone')" value="{{ Auth::guard('admin')->user()->phone }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">@lang('admin.address')</label>
                                        <input type="text" class="form-control slug_slugify" id="address" name="address" placeholder="@lang('admin.address')" value="{{ Auth::guard('admin')->user()->address }}">
                                    </div>
                                </div>
                                {{-- <div class="form-group">
                                    <label for="name">Ảnh đại diện</label>
                                    <input type="file" class="form-control slug_slugify" id="avatar" name="avatar" placeholder="" value="">
                                </div> --}}
                            </form>
                        </div>
                        <div class="card-footer">
                            <input type="submit" class="btn btn-primary" value="@lang('admin.update')">
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
            $('input[name="check_pass"]').on('click', function() {
                let check_pass_length = $('#check_pass:checked').length;

                if (check_pass_length == 1) {
                    //show pass
                    $('#current_password').removeAttr('disabled');
                    $('#new_password').removeAttr('disabled');
                    $('#confirm_password').removeAttr('disabled');
                    $('#check_pass_value').val('on');
                } else {
                    //hide pass
                    $('#current_password').attr('disabled', 'true');
                    $('#new_password').attr('disabled', 'true');
                    $('#confirm_password').attr('disabled', 'true');
                    $('#check_pass_value').val('off')
                }
                $('.wrap-pass').toggleClass('avtive-wpap-pass');

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

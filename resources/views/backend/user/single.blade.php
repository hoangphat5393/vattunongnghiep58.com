@extends('backend.layouts.master')
@php
    // dd($user);
    $lc = app()->getLocale();
    if (isset($user)) {
        extract($user->getAttributes());
        // if ($gallery) {
        //     $gallery = unserialize($gallery);
        // }
    }

    $title_head = isset($name) && $name !== '' ? $name : __('admin.add_user');
    $id = $id ?? 0;

    if (request()->route()->named('admin.user.create')) {
        $form_action = route('admin.user.store'); // Create news
    } else {
        $form_action = route('admin.user.update', $id); // Update news
    }
@endphp

@section('seo')
    @php
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

@section('content')
    {{-- begin::App Content Header --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0">{{ $title_head }}</h1>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb" class="float-sm-end"><ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $title_head }}</li>
                    </ol></nav>
                </div>
            </div>
        </div>
    </div>
    {{-- end::App Content Header --}}

    {{-- begin::App Content --}}
    <div class="app-content">
        <div class="container-fluid">

            <form id="formEdit" action="{{ $form_action }}" method="POST" enctype="multipart/form-data">
                @if (!request()->route()->named('admin.user.create'))
                    @method('PUT')
                @endif
                @csrf
                <input type="hidden" name="id" value="{{ $id }}">

                <div class="row">
                    <div class="col-md-9">

                        <div class="card card-primary card-outline mb-4">

                            {{-- header --}}
                            <div class="card-header">
                                <h3 class="card-title">{{ $title_head }}</h3>
                            </div>

                            <div class="card-body">

                                {{-- show error form --}}
                                <div class="js-validation-messages mb-2 small" role="alert"></div>

                                @if (count($errors) > 0)
                                    <div class="alert-tb alert alert-danger">
                                        @foreach ($errors->all() as $err)
                                            <i class="fa fa-exclamation-circle"></i> {{ $err }}<br />
                                        @endforeach
                                    </div>
                                @endif


                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="vi" role="tabpanel" aria-labelledby="vi-tab">

                                        <div class="row g-3 mb-3">
                                            <div class="col-md-6">
                                                <label for="name" class="form-label">@lang('admin.name')</label>
                                                <input type="text" class="form-control" id="name" name="name" placeholder="@lang('admin.name')" value="{{ old('name', $name ?? '') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="name" class="form-label">@lang('admin.username')</label>
                                                <input type="text" class="form-control" id="name" name="name" placeholder="@lang('admin.username')" value="{{ $username ?? '' }}" readonly>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="post_title" class="form-label">@lang('admin.email')</label>
                                            <input type="text" class="form-control" id="post_title" name="email" placeholder="@lang('admin.email')" value="{{ old('email', $email ?? '') }}">
                                        </div>

                                        @if ($id)
                                            <div class="mb-3">
                                                <label for="check_pass" class="form-label">@lang('admin.change password')</label>
                                                <input type="checkbox" name="check_pass" id="check_pass" value="1">
                                            </div>
                                        @endif
                                        <div class="wrap-pass" {{ $id == 0 ? 'style=display:block' : '' }}>
                                            <div class="mb-3">
                                                <label for="password" class="form-label">@lang('admin.password')</label>
                                                <input type="password" class="form-control" id="password" name="password" placeholder="@lang('admin.password')" autocomplete="off">
                                            </div>
                                            <div class="mb-3">
                                                <label for="repassword" class="form-label">@lang('admin.confirm password')</label>
                                                <input type="password" class="form-control" id="repassword" name="password_confirmation" placeholder="@lang('admin.confirm password')" autocomplete="off" value="">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            @php
                                                $listRoles = [];
                                                if (isset($user_roles) && is_array($user_roles)) {
                                                    foreach ($user_roles as $value) {
                                                        $listRoles[] = (int) $value;
                                                    }
                                                }
                                            @endphp
                                            <label for="post_description" class="form-label">@lang('admin.roles')</label>
                                            <select name="roles[]" multiple class="form-control select2">
                                                {{-- <option value=""></option> --}}
                                                @if (isset($all_roles) && is_array($all_roles))
                                                    @foreach ($all_roles as $k => $v)
                                                        <option value="{{ $k }}" {{ count($listRoles) && in_array($k, $listRoles) ? 'selected' : '' }}>{{ $v }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>

                                        <input type="hidden" class="form-control title_slugify" id="admin_level" name="admin_level" placeholder="" value="{{ old('admin_level', $admin_level ?? 1) }}">
                                    </div>
                                </div>
                            </div> <!-- /.card-body -->
                        </div><!-- /.card -->
                    </div> <!-- /.col-9 -->

                    <div class="col-md-3">
                        @include('backend.partials.action_button')
                    </div> <!-- /.col-9 -->
                </div> <!-- /.row -->
            </form>
        </div> <!-- /.container-fluid -->
    </div>

@endsection
@push('styles')
    <style>
        .wrap-pass {
            display: none;
        }

        .avtive-wpap-pass {
            display: block;
        }
    </style>
@endpush

@push('scripts')
    <script type="text/javascript">
        $(function() {
            //xử lý validate
            $("#formEdit").validate({
                errorLabelContainer: '#formEdit .js-validation-messages',
                rules: {
                    email: {
                        required: true,
                        // email: true
                    },
                    name: "required",
                    password: "required",
                    repassword: {
                        equalTo: "#password"
                    },
                },
                messages: {
                    email: {
                        required: "Vui lòng nhập Email",
                    },
                    name: "Vui lòng nhập tên",
                    password: "Vui lòng nhập mật khẩu",
                    repassword: "Mật khẩu không chính xác",
                },

                invalidHandler: function(event, validator) {
                    $('html, body').animate({
                        scrollTop: 0
                    }, 500);
                }
            });

            //check change pass
            $('input[name="check_pass"]').click(function() {
                $('.wrap-pass').toggleClass('avtive-wpap-pass');
            });
        });
    </script>
@endpush

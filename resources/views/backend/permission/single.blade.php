@extends('backend.layouts.master')

@php
    if (isset($data_admin)) {
        extract($data_admin->toArray());
    }

    $title_head = __('admin.Permissions');
    $date_update = $updated_at ?? date('Y-m-d H:i:s');

    if (request()->route()->named('admin.permission.create')) {
        $form_action = route('admin.permission.store');
    } else {
        $form_action = route('admin.permission.update', $id ?? 0);
    }
@endphp

@section('seo')
    @php
        $seo = [
            'title' => $title_head . ' | ' . setting_option('seo-title-add'),
            'keywords' => setting_option('seo-keywords-add'),
            'description' => setting_option('seo-description-add'),
            'og_title' => 'List Category Product | ' . setting_option('seo-title-add'),
            'og_description' => setting_option('seo-description-add'),
            'og_url' => Request::url(),
            'og_img' => asset('assets/images/logo_seo.png'),
            'current_url' => Request::url(),
            'current_url_amp' => '',
        ];
    @endphp
    @include('backend.partials.seo')
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0">{{ $title_head }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb" class="float-sm-end"><ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('admin.home')</a></li>
                        <li class="breadcrumb-item active">{{ $title_head }}</li>
                    </ol></nav>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <div class="app-content">
        <div class="container-fluid">
            <form action="{{ $form_action }}" method="POST" id="frm-create-useradmin" enctype="multipart/form-data">
                @if (!request()->route()->named('admin.permission.create'))
                    @method('PUT')
                @endif
                @csrf
                <input type="hidden" name="id" value="{{ $id ?? 0 }}">
                <div class="row">
                    <div class="col-md-9">
                        <div class="mb-4 card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">{{ $title_head }}</h3>
                            </div> <!-- /.card-header -->
                            <div class="card-body">
                                <!-- show error form -->
                                <div class="js-validation-messages mb-2 small" role="alert"></div>
                                @if ($errors->has('name'))
                                    <span class="form-text">
                                        <i class="fa fa-info-circle"></i> {{ $errors->first('name') }}
                                    </span>
                                @endif

                                <ul class="nav nav-tabs hidden" id="tabLang" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="vi-tab" data-toggle="tab" href="#vi" role="tab" aria-controls="vi" aria-selected="true">@lang('admin.Vietnamese')</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="en-tab" data-toggle="tab" href="#en" role="tab" aria-controls="en" aria-selected="false">@lang('admin.English')</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="vi" role="tabpanel" aria-labelledby="vi-tab">
                                        <div class="mb-3 form-group">
                                            <label for="name" class="form-label">@lang('admin.name')</label>
                                            <input type="text" class="form-control title_slugify" id="name" name="name" value="{{ $name ?? '' }}">
                                        </div>

                                    </div>
                                    <div class="tab-pane fade" id="en" role="tabpanel" aria-labelledby="en-tab">
                                        <div class="mb-3 form-group">
                                            <label for="name_en" class="form-label">@lang('admin.name')</label>
                                            <input type="text" class="form-control title_slugify" id="name_en" name="name_en" value="{{ $name_en ?? '' }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 form-group">
                                    <label for="slug" class="form-label">Slug</label>
                                    <input type="text" class="form-control" id="slug" name="slug" value="{{ $slug ?? '' }}">
                                </div>
                                <div class="mb-3 form-group">
                                    @php
                                        $old_http_uri = isset($http_uri) && $http_uri != '' ? explode(',', $http_uri) : [];
                                    @endphp

                                    <label for="post_description" class="form-label">HTTP PATH</label>
                                    <select name="http_uri[]" id="admin_level" class="form-control select2" multiple="multiple" onautocomplete="off">
                                        <option value=""></option>
                                        @foreach ($routeAdmin as $route)
                                            {{-- @php --}}
                                            <option value="{{ $route['uri'] }}" {{ in_array($route['uri'], $old_http_uri) ? 'selected' : '' }}>{{ $route['name'] ? $route['method'] . '::' . $route['name'] : $route['uri'] }}</option>
                                        @endforeach
                                    </select>
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

@push('scripts')
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            //xử lý validate
            $("#frm-create-useradmin").validate({
                errorLabelContainer: '#frm-create-useradmin .js-validation-messages',
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
                        // email: "Email không hợp lệ"
                    },
                    name: "Nhập tên nhân viên",
                    password: "Nhập mật khẩu",
                    repassword: "Mật khẩu không chính xác",
                },

                invalidHandler: function(event, validator) {
                    $('html, body').animate({
                        scrollTop: 0
                    }, 500);
                }
            });

            //check change pass
            // $('#check_change_pass').click(function() {
            //     $('.wrap-password').css();
            // });
        });
    </script>

    {{-- <script type="text/javascript">
        CKEDITOR.replace('post_content', {
            width: '100%',
            resize_maxWidth: '100%',
            resize_minWidth: '100%',
            height: '300',
            filebrowserBrowseUrl: '{{ route('ckfinder_browser') }}',
        });
        CKEDITOR.instances['post_content'];

        CKEDITOR.replace('post_content_en', {
            width: '100%',
            resize_maxWidth: '100%',
            resize_minWidth: '100%',
            height: '300',
            filebrowserBrowseUrl: '{{ route('ckfinder_browser') }}',
        });
        CKEDITOR.instances['post_content_en'];
    </script> --}}
@endpush

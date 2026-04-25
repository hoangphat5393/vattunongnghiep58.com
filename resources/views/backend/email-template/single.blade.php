@extends('backend.layouts.master')

@php
    $lc = app()->getLocale();
    if (isset($emailTemplate)) {
        extract($emailTemplate->getAttributes());
    } else {
        $title_head = 'Add new email template';
    }

    $title_head = $name ?? '';

    $date_update = $updated_at ?? date('Y-m-d H:i:s');

    if (request()->route()->named('admin.email-template.create')) {
        $form_action = route('admin.email-template.store'); // Create news
    } else {
        $form_action = route('admin.email-template.update', $id); // Update news
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
    <!-- Content Header (Page header) -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">{{ $title_head }}</h3>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">{{ $title_head }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="app-content">
        <div class="container-fluid">
            <form id="formEdit" action="{{ $form_action }}" method="POST" enctype="multipart/form-data">
                @if (!request()->route()->named('admin.email-template.create'))
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

                                {{-- <ul class="nav nav-tabs mb-3" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="en-tab" data-toggle="pill" data-target="#pills-en" type="button" role="tab" aria-controls="pills-home" aria-selected="true">{{ trans('admin.English') }}</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="vi-tab" data-toggle="pill" data-target="#pills-vi" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">{{ trans('admin.Vietnamese') }}</button>
                                    </li>
                                </ul> --}}

                                <div class="tab-content">

                                    <div class="tab-pane fade active show" id="pills-vi" role="tabpanel" aria-labelledby="vi-tab">
                                        <div class="mb-3 form-group">
                                            <label for="name" class="form-label">Tiêu đề</label>
                                            <input type="text" class="form-control title_slugify" id="name" name="name" placeholder="Tiêu đề" value="{{ $name ?? '' }}">
                                        </div>
                                        @php
                                            $content_arr = ['id' => 'text', 'label' => 'Nội dung mail', 'name' => 'text', 'content' => $text ?? ''];
                                        @endphp
                                        @include('backend.partials.content', $content_arr)
                                    </div>

                                    {{-- <div class="tab-pane fade show " id="pills-en" role="tabpanel" aria-labelledby="en-tab">
                                        <div class="form-group">
                                            <label for="name_en">Title</label>
                                            <input type="text" class="form-control title_slugify" id="name_en" name="name_en" placeholder="Title" value="{{ $name_en ?? '' }}">
                                        </div>
                                        @php
                                            $content_arr = ['id' => 'text_en', 'label' => 'Email content', 'name' => 'text_en', 'content' => $text_en ?? ''];
                                        @endphp
                                        @include('admin.partials.content', $content_arr)
                                    </div> --}}
                                </div>

                                <div class="row">
                                    <div class="form-group col-lg-12">
                                        <select class="form-control" style="width: 100%;" name="group">
                                            <option value="">Select group</option>
                                            @foreach ($arrayGroup as $k => $v)
                                                <option value="{{ $k }}" {{ isset($group) && $group == $k ? 'selected' : '' }}>{{ $v }}</option>
                                            @endforeach
                                        </select>
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
    <script type="text/javascript"></script>
@endsection


@push('scripts')
    <script>
        // editor('text_en');
        editor('text');

        $(function() {
            //xử lý validate
            $("#formEdit").validate({
                errorLabelContainer: '#formEdit .js-validation-messages',
                ignore: [],
                rules: {
                    name_en: "required",
                    name: "required",
                    group: "required",
                },
                messages: {
                    name_en: "Enter mail title (EN)",
                    name: "Enter mail title (VN)",
                    group: "Select group"
                },
                errorElement: 'div',
                invalidHandler: function(event, validator) {
                    $('html, body').animate({
                        scrollTop: 0
                    }, 500);
                }
            });
        });
    </script>
@endpush

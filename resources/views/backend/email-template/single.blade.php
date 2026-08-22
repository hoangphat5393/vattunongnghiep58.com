@extends('backend.layouts.master')

@php
    $lc = app()->getLocale();
    if (isset($emailTemplate)) {
        extract($emailTemplate->getAttributes());
        $title_head = $name ?? '';
    } else {
        $title_head = __('admin.add_email_template');
    }

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
                    <h1 class="mb-0">{{ $title_head }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb" class="float-sm-end">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('admin.home')</a></li>
                            <li class="breadcrumb-item active">{{ $title_head }}</li>
                        </ol>
                    </nav>
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
                                <div class="js-validation-messages mb-2 small" role="alert">
                                    @if ($errors->any())
                                        @foreach ($errors->all() as $error)
                                            <div class="text-danger">{{ $error }}</div>
                                        @endforeach
                                    @endif
                                </div>

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
                                            <label for="name" class="form-label">Tiêu đề (subject email)</label>
                                            <input type="text" class="form-control title_slugify @error('name') is-invalid @enderror" id="name" name="name" placeholder="Tiêu đề hiển thị khi gửi mail" value="{{ old('name', $name ?? '') }}">
                                            @error('name')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        @php
                                            $content_arr = ['id' => 'text', 'label' => 'Nội dung mail', 'name' => 'text', 'content' => old('text', $text ?? '')];
                                        @endphp
                                        @include('backend.partials.content', $content_arr)
                                        @error('text')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
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
                                        <label for="email-template-code" class="form-label">Mã code <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control font-monospace @error('code') is-invalid @enderror" id="email-template-code" name="code" placeholder="vd: new_register" value="{{ old('code', $code ?? '') }}" autocomplete="off" spellcheck="false">
                                        @error('code')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">
                                            Code do dev dùng trong code PHP để gọi đúng template. Chỉ dùng chữ thường, số và <code>_</code>.
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-warning mt-3 mb-0 small" id="email-template-draft-hint" style="display: none;">
                                    Template đang ở trạng thái <strong>Bản nháp</strong> — hệ thống sẽ <strong>không gửi</strong> mail cho đến khi chuyển sang <strong>Công khai</strong>.
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
        $(function() {
            editor('text');

            function toggleDraftHint() {
                var isDraft = $('#radioDraft').is(':checked');
                $('#email-template-draft-hint').toggle(isDraft);
            }

            $('input[name="status"]').on('change', toggleDraftHint);
            toggleDraftHint();

            $("#formEdit").validate({
                errorLabelContainer: '#formEdit .js-validation-messages',
                ignore: [],
                onfocusout: false,
                onkeyup: false,
                onclick: false,
                rules: {
                    name: 'required',
                    code: {
                        required: true,
                        pattern: /^[a-z][a-z0-9_]*$/,
                    },
                    text: {
                        ckeditor_required: true,
                    },
                },
                messages: {
                    name: 'Nhập tiêu đề mail (subject)',
                    code: {
                        required: 'Nhập mã code template',
                        pattern: 'Code chỉ gồm chữ thường, số và dấu _',
                    },
                    text: {
                        ckeditor_required: 'Nhập nội dung mail',
                    },
                },
                errorElement: 'div',
                invalidHandler: function(event, validator) {
                    $('html, body').animate({
                        scrollTop: 0
                    }, 500);
                },
                submitHandler: function(form) {
                    syncAllCkEditors();
                    form.submit();
                },
            });
        });
    </script>
@endpush

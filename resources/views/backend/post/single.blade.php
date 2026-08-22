@extends('backend.layouts.master')
@php
    $lc = app()->getLocale();

    // Giống backend/page/single: map bản ghi Page (tin) sang biến dùng trong form
    if (!empty($edit_data)) {
        extract($edit_data->getAttributes());
        $title = $edit_data->name ?? '';
        $title_head = $title !== '' ? $title : __('admin.edit_news');
        $slug = $slug ?? '';
        $description = $edit_data->description != '' ? htmlspecialchars_decode($edit_data->description) : '';
        $content = $edit_data->content != '' ? htmlspecialchars_decode($edit_data->content) : '';
        $description_en = ($edit_data->description_en ?? '') !== '' ? htmlspecialchars_decode($edit_data->description_en) : '';
        $content_en = ($edit_data->content_en ?? '') !== '' ? htmlspecialchars_decode($edit_data->content_en) : '';
        $name_en = $edit_data->name_en ?? '';
        $sort = $sort ?? 0;
        $image = $image ?? '';
        $seo_title = $seo_title ?? '';
        $seo_keyword = $seo_keyword ?? '';
        $seo_description = $seo_description ?? '';
        $id = (int) ($id ?? ($edit_data->id ?? 0));
    } else {
        $title_head = isset($title) && $title !== '' ? $title : __('admin.add_news');
        $id = (int) ($id ?? 0);
    }

    if (request()->route()->named('admin.post.create')) {
        $form_action = route('admin.post.store');
    } else {
        $form_action = route('admin.post.update', $id);
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
        // $seo = WebService::getSEO($data_seo);
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
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="app-content">
        <div class="container-fluid">
            <form action="{{ $form_action }}" method="POST" id="frm-create-post" enctype="multipart/form-data">
                @if (!request()->route()->named('admin.post.create'))
                    @method('PUT')
                @endif
                @csrf
                <input type="hidden" name="id" value="{{ $id ?? 0 }}">
                <div class="row">
                    <div class="col-md-9">
                        <div class="mb-4 card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">{{ $title_head }}</h3>
                            </div>
                            <div class="card-body">
                                {{-- show error form --}}
                                <div class="js-validation-messages mb-2 small" role="alert"></div>
                                <div class="mb-3 form-group">
                                    <label for="slug" class="form-label">Slug</label>
                                    <input type="text" class="form-control slug_slugify" id="slug" name="slug" placeholder="Slug" value="{{ $slug ?? '' }}">
                                    @if ($id > 0 && ($slug ?? '') !== '')
                                        <p><b style="color: #0000cc;">Link:</b>
                                            <u><i><a style="color: #F00;" href="{{ route('news.detail', [$slug, $id]) }}" target="_blank">{{ route('news.detail', [$slug, $id]) }}</a></i></u>
                                        </p>
                                    @endif
                                </div>
                                {{-- <ul class="nav nav-tabs hidden" id="tabLang" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="vi-tab" data-toggle="tab" href="#vi" role="tab" aria-controls="vi" aria-selected="true">@lang('admin.Vietnamese')</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="en-tab" data-toggle="tab" href="#en" role="tab" aria-controls="en" aria-selected="false">@lang('admin.English')</a>
                                    </li>
                                </ul> --}}
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="vi" role="tabpanel" aria-labelledby="vi-tab">
                                        <div class="mb-3 form-group">
                                            <label for="name" class="form-label">@lang('admin.Title')</label>
                                            <input type="text" class="form-control title_slugify" id="name" name="title" placeholder="@lang('admin.Title')" value="{{ $title ?? '' }}">
                                        </div>
                                        <div class="mb-3 form-group">
                                            <label for="description" class="form-label">@lang('admin.Description')</label>
                                            <textarea id="description" name="description">{!! $description ?? '' !!}</textarea>
                                        </div>
                                        <div class="mb-3 form-group">
                                            <label for="content" class="form-label">@lang('admin.Content')</label>
                                            <textarea id="content" name="content">{!! $content ?? '' !!}</textarea>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="en" role="tabpanel" aria-labelledby="en-tab">
                                        <div class="mb-3 form-group">
                                            <label for="name_en" class="form-label">@lang('admin.Title')</label>
                                            <input type="text" class="form-control" id="name_en" name="name_en" placeholder="Title" value="{{ $name_en ?? '' }}">
                                        </div>
                                        <div class="mb-3 form-group">
                                            <label for="description_en" class="form-label">@lang('admin.Description')</label>
                                            <textarea id="description_en" name="description_en">{!! $description_en ?? '' !!}</textarea>
                                        </div>
                                        <div class="mb-3 form-group">
                                            <label for="content_en" class="form-label">@lang('admin.Content')</label>
                                            <textarea id="content_en" name="content_en">{!! $content_en ?? '' !!}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- /.card-body -->
                        </div><!-- /.card -->

                        <div class="mb-4 card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">@lang('admin.information')</h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-3 form-group">
                                    <label for="sort" class="form-label col-form-label text-lg-right">@lang('admin.sort')</label>
                                    <input type="text" class="form-control" id="sort" name="sort" value="{{ $sort ?? 0 }}">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-3">
                        @include('backend.partials.action_button')

                        {{-- SELECT CATEGORY --}}
                        {{-- <div class="card widget-category">
                            <div class="card-header">
                                <h4>@lang('admin.Category')</h4>
                            </div>
                            <div class="card-body max-vh-75">
                                <div class="inside clearfix">
                                    @php
                                        $array_checked = isset($edit_data) ? $edit_data->categories->pluck('id')->toArray() : [];
                                        $category_type = 'post';
                                    @endphp
                                    @include('backend.partials.category-item')
                                </div>
                            </div>
                        </div> --}}
                        {{-- END SELECT CATEGORY --}}

                        @include('backend.partials.image', ['title' => __('admin.Thumbnail'), 'id' => 'img', 'name' => 'image', 'image' => $image ?? ''])
                        {{-- @include('backend.partials.image', ['title' => 'Hình ảnh Banner', 'id' => 'cover-img', 'name' => 'cover', 'image' => $cover ?? '']) --}}
                    </div>
                </div> <!-- /.row -->

                {{-- SEO --}}
                <div class="row">
                    <div class="col-12 col-md-9">
                        @include('backend.partials.form-seo')
                    </div>
                </div>
                {{-- END SEO --}}
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(function() {
            // $('.slug_slugify').slugify('.title_slugify');

            editorQuote('description');
            editorQuote('description_en');
            editor('content');
            editor('content_en');

            $('#thumbnail_file').change(function(evt) {
                $("#thumbnail_file_link").val($(this).val());
                $("#thumbnail_file_link").attr("value", $(this).val());
            });

            //xử lý validate
            $("#frm-create-post").validate({
                errorLabelContainer: '#frm-create-post .js-validation-messages',
                rules: {
                    title: "required",
                    // 'category[]': {
                    //     required: true,
                    //     minlength: 1
                    // }
                },
                messages: {
                    title: "Nhập tiêu đề tin",
                    'category[]': "Chọn thể loại tin",
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

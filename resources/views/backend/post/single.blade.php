@extends('backend.layouts.master')
@php
    $lc = app()->getLocale();
    if (isset($edit_data)) {
        extract($edit_data->getAttributes());
        if ($gallery) {
            $gallery = unserialize($gallery);
        }
    }
    $title_head = $title ?? __('Add news');
    $id = $id ?? 0;

    if (request()->route()->named('admin.post.create')) {
        $form_action = route('admin.post.store'); // Create news
    } else {
        $form_action = route('admin.post.update', $id); // Update news
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
            <div class="row mb-2">
                <div class="col-sm-6">
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">{{ $title_head }}</li>
                    </ol>
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
                        <div class="card">
                            <div class="card-header">
                                <h3 class="text-capitalize">{{ $title_head }}</h3>
                            </div>
                            <div class="card-body">
                                {{-- show error form --}}
                                <div class="errorTxt"></div>
                                <div class="form-group">
                                    <label for="slug">Slug</label>
                                    <input type="text" class="form-control slug_slugify" id="slug" name="slug" placeholder="Slug" value="{{ $slug ?? '' }}">
                                    @if ($id > 0)
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
                                        <div class="form-group">
                                            <label for="name">@lang('admin.Title')</label>
                                            <input type="text" class="form-control title_slugify" id="name" name="title" placeholder="@lang('admin.Title')" value="{{ $title ?? '' }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="description">@lang('admin.Description')</label>
                                            <textarea id="description" name="description">{!! $description ?? '' !!}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="content">@lang('admin.Content')</label>
                                            <textarea id="content" name="content">{!! $content ?? '' !!}</textarea>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="en" role="tabpanel" aria-labelledby="en-tab">
                                        <div class="form-group">
                                            <label for="name_en">@lang('admin.Title')</label>
                                            <input type="text" class="form-control" id="name_en" name="name_en" placeholder="Title" value="{{ $name_en ?? '' }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="description_en">@lang('admin.Description')</label>
                                            <textarea id="description_en" name="description_en">{!! $description_en ?? '' !!}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="content_en">@lang('admin.Content')</label>
                                            <textarea id="content_en" name="content_en">{!! $content_en ?? '' !!}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- /.card-body -->
                        </div><!-- /.card -->

                        <div class="card">
                            <div class="card-header">
                                <h5>@lang('Infomation')</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="sort" class="col-form-label text-lg-right">@lang('Sort')</label>
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
                                <div class="inside clear">
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
                rules: {
                    name: "required",
                    // 'category[]': {
                    //     required: true,
                    //     minlength: 1
                    // }
                },
                messages: {
                    name: "Nhập tiêu đề tin",
                    'category[]': "Chọn thể loại tin",
                },
                errorElement: 'div',
                errorLabelContainer: '.errorTxt',
                invalidHandler: function(event, validator) {
                    $('html, body').animate({
                        scrollTop: 0
                    }, 500);
                }
            });
        });
    </script>
@endpush

@extends('backend.layouts.master')

@php
    $title = 'Danh mục tin tức';
    if (isset($category)) {
        extract($category->getAttributes());
    }
    $id = $id ?? 0;
    $hot = $hot ?? 0;
@endphp

@section('seo')
    @php
        $title_head = $title;
        $seo = [
            'title' => $title_head . ' | ' . setting_option('seo-title-add'),
            'keywords' => setting_option('seo-keywords-add'),
            'description' => setting_option('seo-description-add'),
            'og_title' => $title_head,
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
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0">{{ $title }}</h1>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb" class="float-sm-end">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('admin.home')</a></li>
                            <li class="breadcrumb-item active">{{ $title }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <form action="{{ $id ? route('admin.post-category.update', $id) : route('admin.post-category.store') }}" method="POST" id="frm-create-category" enctype="multipart/form-data">
                @if ($id)
                    @method('PUT')
                @endif
                @csrf
                <input type="hidden" name="id" value="{{ $id ?? 0 }}">
                <div class="row">
                    <div class="col-md-9">
                        <div class="mb-4 card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">{{ $title }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="js-validation-messages mb-2 small" role="alert"></div>
                                <div class="mb-3 form-group">
                                    <label for="slug" class="form-label">Slug</label>
                                    <input type="text" class="form-control slug_slugify" id="slug" name="slug" placeholder="Slug" value="{{ $slug ?? '' }}">
                                </div>
                                <div class="mb-3 form-group">
                                    <label for="name" class="form-label">Tên chuyên mục</label>
                                    <input type="text" class="form-control title_slugify" id="name" name="name" placeholder="Tiêu đề" value="{{ $name ?? '' }}">
                                </div>
                                <div class="mb-3 form-group">
                                    <label for="description" class="form-label">Trích dẫn</label>
                                    <textarea id="description" name="description">{!! $description ?? '' !!}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Thông tin</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="parent" class="form-label col-form-label">Chọn thể loại Cha</label>
                                    @include('backend.product-category.includes.select-category', [
                                        'parent' => $parent ?? 0,
                                        'childrenMap' => $childrenMap ?? collect(),
                                    ])
                                </div>
                                <div class="form-group">
                                    <label for="sort" class="form-label col-form-label">Sắp xếp</label>
                                    <input type="text" class="form-control" id="sort" name="sort" value="{{ $sort ?? 0 }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="row">
                            <div class="col-md-12 order-last order-md-first">
                                @include('backend.partials.action_button')
                            </div>
                            <div class="col-md-12">
                                @include('backend.partials.image', ['title' => 'Hình ảnh', 'id' => 'img', 'name' => 'image', 'image' => $image ?? ''])
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-9">
                        @include('backend.partials.form-seo')
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(function() {
            if ($('#description').length) {
                editorQuote('description');
            }

            $("#frm-create-category").validate({
                errorLabelContainer: '#frm-create-category .js-validation-messages',
                rules: {
                    name: "required",
                },
                messages: {
                    name: "Nhập tiêu đề thể loại tin",
                },
                errorElement: 'div',
                invalidHandler: function() {
                    $('html, body').animate({
                        scrollTop: 0
                    }, 500);
                }
            });
        });
    </script>
@endpush

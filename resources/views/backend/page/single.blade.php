@extends('backend.layouts.master')
@php
    $lc = app()->getLocale();
    if (isset($page)) {
        extract($page->getAttributes());
        $title_head = $name ?? '';
    } else {
        $title_head = __('admin.add_page');
    }
    $template = $template ?? 'page';

    $id = $id ?? 0;

    if (request()->route()->named('admin.page.create')) {
        $form_action = route('admin.page.store'); // Create news
    } else {
        $form_action = route('admin.page.update', $id); // Update news
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
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('admin.home')</a></li>
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
                @if (!request()->route()->named('admin.page.create'))
                    @method('PUT')
                @endif
                @csrf
                <input type="hidden" name="id" value="{{ $id ?? 0 }}">

                <div class="row">
                    <div class="col-md-9">

                        {{-- card --}}
                        <div class="mb-4 card card-primary card-outline">

                            {{-- header --}}
                            <div class="card-header">
                                <h3 class="card-title">{{ $title_head }}</h3>
                            </div>

                            {{-- body --}}
                            <div class="card-body">

                                {{-- show error form --}}
                                <div class="mb-2 js-validation-messages small" role="alert"></div>

                                <div class="mb-3">
                                    <label for="slug" class="form-label">@lang('admin.slug')</label>
                                    <input type="text" class="form-control" id="slug" name="slug" placeholder="slug" value="{{ $slug ?? '' }}">
                                    {{-- <div id="nameHelp" class="form-text">
                                        We'll never share your email with anyone else.
                                    </div> --}}

                                    @if ($id > 0)
                                        <p class="my-2">
                                            <strong class="text-primary">Link Vi:</strong>
                                            <u><i><a href="{{ route('page', $slug) }}" target="_blank" class="text-red">{{ route('page', $slug) }}</a></i></u>
                                        </p>
                                    @endif
                                </div>

                                <ul class="nav nav-tabs d-none" id="tabLang" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="vi-tab" data-bs-toggle="tab" href="#vi" role="tab" aria-controls="vi" aria-selected="true">@lang('admin.Vietnamese')</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="en-tab" data-bs-toggle="tab" href="#en" role="tab" aria-controls="en" aria-selected="false">@lang('admin.English')</a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="vi" role="tabpanel" aria-labelledby="vi-tab">

                                        <div class="mb-3">
                                            <label for="name" class="form-label">@lang('admin.title')</label>
                                            <input type="text" class="form-control" id="name" name="name" placeholder="Name VI" value="{{ $name ?? '' }}">
                                        </div>

                                        @php
                                            $quote_arr = ['id' => 'description', 'label' => 'Description', 'name' => 'description', 'description' => $description ?? ''];
                                            $content_arr = ['id' => 'content', 'label' => __('admin.content'), 'name' => 'content', 'content' => $content ?? ''];
                                        @endphp
                                        @include('backend.partials.quote', $quote_arr)
                                        @include('backend.partials.content', $content_arr)
                                    </div>

                                    {{-- <div class="tab-pane fade" id="en" role="tabpanel" aria-labelledby="en-tab">

                                        <div class="mb-3">
                                            <label for="name_en" class="form-label">@lang('admin.Title')</label>
                                            <input type="text" class="form-control" id="name_en" name="name_en" placeholder="Name EN" value="{{ $name_en ?? '' }}">
                                        </div>
                                        @php
                                            $quote_arr = ['id' => 'description_en', 'label' => 'Description', 'name' => 'description_en', 'description' => $description_en ?? ''];
                                            $content_arr = ['id' => 'content_en', 'label' => __('admin.content'), 'name' => 'content_en', 'content' => $content_en ?? ''];
                                        @endphp
                                        @include('backend.partials.quote', $quote_arr)
                                        @include('backend.partials.content', $content_arr)
                                    </div> --}}
                                </div>

                                <div class="mb-3 form-group">
                                    <label for="show_promotion" class="form-label">Template</label>
                                    <select name="template" class="form-control">
                                        <option value="none" {{ $template == 'none' ? 'selected' : '' }}>@lang('admin.None')</option>
                                        <option value="page" {{ $template == 'page' ? 'selected' : '' }}>@lang('admin.Page')</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                        {{-- end::card --}}
                    </div>

                    <div class="col-md-3">
                        @include('backend.partials.action_button')
                        @include('backend.partials.image', ['title' => 'Banner', 'id' => 'img', 'name' => 'image', 'image' => $image ?? ''])
                        {{-- @include('backend.partials.image', ['title' => 'Hình ảnh Banner', 'id' => 'cover-img', 'name' => 'cover', 'image' => $cover]) --}}
                    </div>
                </div>
                {{-- end::row --}}

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
    {{-- end::App Content --}}
@endsection

@push('scripts')
    <script type="text/javascript">
        editorQuote('description');
        editorQuote('description_en');

        editor('content');
        editor('content_en');

        document.addEventListener('DOMContentLoaded', function() {
            var form = document.getElementById('formEdit');
            if (!form) return;

            form.addEventListener('submit', function(event) {
                var errorContainer = document.querySelector('#formEdit .js-validation-messages');
                if (errorContainer) {
                    errorContainer.innerHTML = '';
                }

                var errors = [];
                var nameInput = form.querySelector('[name=\"name\"]');

                if (nameInput && !nameInput.value.trim()) {
                    errors.push('Nhập tiêu đề trang');
                }

                if (errors.length) {
                    event.preventDefault();
                    if (errorContainer) {
                        var list = document.createElement('ul');
                        list.className = 'error list-unstyled mb-0 small';
                        errors.forEach(function(message) {
                            var item = document.createElement('li');
                            item.textContent = message;
                            list.appendChild(item);
                        });
                        errorContainer.appendChild(list);
                    }
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                    if (nameInput && typeof nameInput.focus === 'function') {
                        nameInput.focus();
                    }
                }
            });
        });
    </script>
@endpush

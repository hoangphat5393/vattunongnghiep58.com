@extends('backend.layouts.master')

@php
    $lc = app()->getLocale();
    if (isset($post)) {
        extract($post->getAttributes());
    }
    $title_head = $name ?? __('Edit CSS');
    $id = $id ?? 0;
    $form_action = route('admin.css.update'); // update css file
@endphp

@section('seo')
    @php
        $title_head = 'Theme CSS';
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
    <link rel="stylesheet" href="{{ asset('assets/plugin/codemirror@6.65/codemirror.min.css') }}">
@endpush

@section('content')
    {{-- begin::App Content Header --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">{{ $title_head }}</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $title_head }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    {{-- end::App Content Header --}}

    {{-- begin::App Content --}}
    <div class="app-content">
        <div class="container-fluid">

            <form id="formEdit" action="{{ $form_action }}" method="POST">
                @method('PUT')
                @csrf
                <div class="row">
                    <div class="col-md-12">

                        {{-- card --}}
                        <div class="card card-primary card-outline mb-4">

                            {{-- header --}}
                            <div class="card-header">
                                <h3 class="card-title">{{ $title_head }}</h3>
                            </div>

                            <div class="card-body">
                                {{-- show error form --}}
                                <div class="errorTxt"></div>

                                <div class="form-group">
                                    <label for="description_en">CSS Content</label>
                                    <textarea id="CSSTextarea" class="form-control" name="css_content">{!! $scssContent ?? '' !!}</textarea>
                                </div>

                                <div class="posts_tbl_setting clearfix text-center">
                                    <button id="submit_setting" class="btn btn-primary pull-left" name="submit" type="submit">Save Changes</button>
                                </div>
                            </div>

                        </div>
                        {{-- end::card --}}
                    </div>
                </div>

            </form>
        </div>
    </div>
    {{-- end::App Content --}}
@endsection


@push('scripts')
    <script src="{{ asset('assets/plugin/codemirror@6.65/codemirror.min.js') }}"></script>
    <script>
        var myTextarea = document.getElementById('CSSTextarea');
        var css_editor = CodeMirror.fromTextArea(myTextarea, {
            lineNumbers: true,
            // mode: 'javascript',
            mode: 'text/css',
        });
        // css_editor.setSize('100%', 500);
        css_editor.setSize(null, 500);
    </script>
@endpush

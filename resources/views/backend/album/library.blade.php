@extends('backend.layouts.master')

@section('seo')
    @php
        $title_head = __('Library');
        $seo = [
            'title' => $title_head . ' | ' . setting_option('seo-title-add'),
            'keywords' => setting_option('seo-keywords-add'),
            'description' => setting_option('seo-description-add'),
            'og_title' => 'List Category Product | ' . setting_option('seo-title-add'),
            'og_description' => setting_option('seo-description-add'),
            'og_url' => Request::url(),
            'og_img' => setting_option('logo'),
            'current_url' => Request::url(),
            'current_url_amp' => '',
        ];
    @endphp
    @include('backend.partials.seo')
@endsection

@push('style')
    {{-- <link rel="stylesheet" href="{{ asset('plugin/DataTables/datatables.min.css') }}"> --}}
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
            <div class="row">
                <div class="col">
                    {{-- card --}}
                    <div class="card border-top border-primary card-outline mb-4">

                        {{-- header --}}
                        <div class="card-header">
                            <h3 class="card-title">{{ $title_head }}</h3>
                        </div>

                        <div class="card-body">
                            <div class="row justify-content-center mb-3">
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="image" id="image_link" placeholder="image link">
                                </div>
                            </div>
                            <div id="ckfinder-widget" class="ckfinder-widget"></div>
                        </div>
                    </div>
                    {{-- end::card --}}
                </div>
            </div>
        </div>
        {{-- end::App Content --}}
    </div>
@endsection

@push('scripts')
    <script>
        // CKFinder.widget('ckfinder-widget', {
        //     width: '100%',
        //     height: 700
        // })

        CKFinder.widget('ckfinder-widget', {
            chooseFiles: true,
            width: '100%',
            height: 700,
            onInit: function(finder) {
                finder.on('files:choose', function(evt) {

                    var file = evt.data.files.first();
                    // var output = document.getElementById('#image_link');

                    $('#image_link').val(file.getUrl());
                    // Update new image to hidden input
                    // output.value = file.getUrl();
                    // if (view_img != '') $('.' + view_img).attr('src', file.getUrl());
                });

            },
        });
    </script>
@endpush

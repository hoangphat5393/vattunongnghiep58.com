@extends('backend.layouts.master')

@section('seo')
    @php
        $title_head = __('admin.library');
        $seo = [
            'title' => $title_head . ' | ' . setting_option('seo-title-add'),
            'keywords' => setting_option('seo-keywords-add'),
            'description' => setting_option('seo-description-add'),
            'og_title' => $title_head . ' | ' . setting_option('seo-title-add'),
            'og_description' => setting_option('seo-description-add'),
            'og_url' => Request::url(),
            'og_img' => setting_option('logo'),
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
            <div class="row">
                <div class="col">
                    {{-- card --}}
                    <div class="card border-top border-primary card-outline mb-4">
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
    </div>
    {{-- end::App Content --}}
@endsection

@push('scripts')
    <script>
        CKFinder.widget('ckfinder-widget', {
            chooseFiles: true,
            width: '100%',
            height: 700,
            onInit: function(finder) {
                var initialSelected = false;

                finder.on('files:choose', function(evt) {
                    var file = evt.data.files.first();
                    $('#image_link').val(file.getUrl());
                });

                finder.on('folder:getFiles:after', function(evt) {
                    var folder = evt.data.folder;
                    if (!initialSelected && folder && folder.get('name') === 'Images') {
                        initialSelected = true;
                        finder.request('folder:select', { folder: folder });
                        if (folder.get('hasChildren')) {
                            finder.request('folder:expand', { folder: folder });
                        }
                    }
                });
            },
        });
    </script>
@endpush

@extends('backend.layouts.master')
@section('seo')
    @php
        $title_head = 'Setting Menu';
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
    <link rel="stylesheet" href="{{ asset('assets/laravel-menu/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/laravel-menu/plugins/css/fontawesome-iconpicker.min.css') }}">
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
            {{-- begin::Row --}}
            <div class="row">
                <div class="col-md-12">
                    {{-- card --}}
                    <div class="card mb-4">

                        {{-- card-header --}}
                        <div class="card-header">
                            <h3 class="card-title">{{ $title_head }}</h3>
                        </div>

                        {{-- card-body --}}
                        <div class="card-body">
                            @include('backend.setting.menu-html')
                        </div>
                    </div>
                    {{-- end::card --}}
                </div>
            </div>
            {{-- end::Row --}}
        </div>
    </div>
    {{-- end::App Content --}}
@endsection


@push('scripts')
    <script>
        var menus = {
            "oneThemeLocationNoMenus": "",
            "moveUp": "Move up",
            "moveDown": "Mover down",
            "moveToTop": "Move top",
            "moveUnder": "Move under of %s",
            "moveOutFrom": "Out from under  %s",
            "under": "Under %s",
            "outFrom": "Out from %s",
            "menuFocus": "%1$s. Element menu %2$d of %3$d.",
            "subMenuFocus": "%1$s. Menu of subelement %2$d of %3$s."
        };

        var arraydata = [];
        var menuwr = "{{ url()->current() }}";
    </script>

    <script type="text/javascript" src="{{ asset('assets/laravel-menu/scripts.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/laravel-menu/scripts2.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/laravel-menu/menu.js') }}"></script>

    <script type="text/javascript">
        // $(function() {
        //     $('.icp-dd').iconpicker();
        //     $('.icp').on('iconpickerSelected', function(e) {
        //         $(this).parent().find('input').val(e.iconpickerValue);
        //         $(this).parent().find('.dropdown-menu').removeClass('show');
        //     });
        // });

        // $(function() {
        //     $(document).on('click', '.btn-images', function() {
        //         var id = $(this).attr('data');
        //         window.open('/file-manager/fm-button?' + id, 'fm', 'width=1200,height=600');
        //     });

        //     $('.remove-icon').click(function(event) {
        //         var img = $(this).data('img');
        //         $(this).parent().find('img').attr('src', img);
        //         $(this).parent().find('input[type="hidden"]').val('');
        //         $(this).hide();
        //     });
        // });

        // set file link
        // function fmSetLink($url, id = "preview_image") {
        //     const myArr = $url.split("storage/");
        //     document.getElementById(id).src = $url;
        //     document.querySelector('.' + id).value = myArr[1];
        // }
    </script>
@endpush

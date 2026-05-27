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
    <style>
        #hwpwrap.admin-menu-builder,
        #hwpwrap.admin-menu-builder *,
        #hwpwrap.admin-menu-builder *::before,
        #hwpwrap.admin-menu-builder *::after {
            box-sizing: border-box;
        }

        #hwpwrap.admin-menu-builder {
            max-width: 100%;
            overflow-x: hidden;
        }

        #hwpwrap.admin-menu-builder #wpwrap,
        #hwpwrap.admin-menu-builder #wpcontent,
        #hwpwrap.admin-menu-builder #wpbody,
        #hwpwrap.admin-menu-builder #wpbody-content {
            float: none !important;
            min-height: 0 !important;
            min-width: 0 !important;
            overflow: visible;
            padding-bottom: 0 !important;
            width: 100% !important;
        }

        #hwpwrap.admin-menu-builder .wrap,
        #hwpwrap.admin-menu-builder #nav-menus-frame,
        #hwpwrap.admin-menu-builder #menu-management,
        #hwpwrap.admin-menu-builder #menu-management .menu-edit,
        #hwpwrap.admin-menu-builder #post-body,
        #hwpwrap.admin-menu-builder #post-body-content {
            float: none !important;
            margin: 0 !important;
            max-width: 100% !important;
            min-width: 0 !important;
            width: 100% !important;
        }

        #hwpwrap.admin-menu-builder .manage-menus {
            background: var(--bs-tertiary-bg);
            border: 1px solid var(--bs-border-color);
            border-radius: var(--bs-border-radius);
            box-shadow: none;
            max-width: 100%;
            margin-bottom: 1rem;
            padding: 1rem;
        }

        #hwpwrap.admin-menu-builder .menu-builder-frame.has-selected-menu {
            align-items: start;
            display: grid !important;
            gap: 1rem;
            grid-template-columns: minmax(260px, 34%) minmax(0, 1fr);
            max-width: 100%;
            overflow: hidden;
        }

        #hwpwrap.admin-menu-builder .menu-builder-frame.is-create-menu {
            display: block;
        }

        #hwpwrap.admin-menu-builder #wpbody-content #menu-settings-column,
        #hwpwrap.admin-menu-builder #menu-management-liquid {
            float: none !important;
            margin: 0 !important;
            max-width: 100% !important;
            min-width: 0 !important;
            overflow: hidden;
            width: 100% !important;
        }

        #hwpwrap.admin-menu-builder #menu-settings-column {
            grid-column: 1;
            grid-row: 1;
        }

        #hwpwrap.admin-menu-builder #menu-management-liquid {
            grid-column: 2;
            grid-row: 1;
        }

        #hwpwrap.admin-menu-builder #menu-settings-column .accordion-container,
        #hwpwrap.admin-menu-builder #menu-management .menu-edit,
        #hwpwrap.admin-menu-builder .menu-item-handle,
        #hwpwrap.admin-menu-builder .menu-item-settings {
            border-color: var(--bs-border-color);
            box-shadow: none;
        }

        #hwpwrap.admin-menu-builder #menu-settings-column .accordion-container,
        #hwpwrap.admin-menu-builder #menu-management .menu-edit {
            background: var(--bs-body-bg);
            border-radius: var(--bs-border-radius);
            overflow: hidden;
        }

        #hwpwrap.admin-menu-builder .outer-border {
            margin: 0;
            padding: 0;
        }

        #hwpwrap.admin-menu-builder .accordion-section-title {
            background: var(--bs-tertiary-bg);
            border-bottom: 1px solid var(--bs-border-color);
            color: var(--bs-body-color);
            font-size: .95rem;
            font-weight: 600;
        }

        #hwpwrap.admin-menu-builder .accordion-section.open .accordion-section-title {
            background: var(--bs-primary-bg-subtle);
            color: var(--bs-primary-text-emphasis);
        }

        #hwpwrap.admin-menu-builder .accordion-section-content {
            background: var(--bs-body-bg);
        }

        #hwpwrap.admin-menu-builder .menu-edit-toolbar,
        #hwpwrap.admin-menu-builder #nav-menu-footer {
            background: var(--bs-tertiary-bg);
            border-bottom: 1px solid var(--bs-border-color);
            padding: 1rem;
        }

        #hwpwrap.admin-menu-builder #nav-menu-footer {
            border-bottom: 0;
            border-top: 1px solid var(--bs-border-color);
        }

        #hwpwrap.admin-menu-builder #post-body {
            padding: 1rem;
        }

        #hwpwrap.admin-menu-builder .menu-item-bar {
            max-width: 100%;
        }

        #hwpwrap.admin-menu-builder .menu-item-bar .menu-item-handle {
            background: var(--bs-body-bg);
            border-radius: var(--bs-border-radius);
            max-width: 100%;
            min-height: 48px;
            width: 100% !important;
        }

        #hwpwrap.admin-menu-builder .menu-item-settings {
            background: var(--bs-tertiary-bg);
            border-radius: 0 0 var(--bs-border-radius) var(--bs-border-radius);
            max-width: 100%;
            padding: 1rem;
            width: 100% !important;
        }

        #hwpwrap.admin-menu-builder #menu-to-edit {
            max-width: 640px;
            overflow: visible;
            padding-right: 0;
        }

        #hwpwrap.admin-menu-builder .menu-item-textbox,
        #hwpwrap.admin-menu-builder #menu-name {
            max-width: 100%;
            width: 100%;
        }

        #hwpwrap.admin-menu-builder input[type="text"],
        #hwpwrap.admin-menu-builder input[type="url"],
        #hwpwrap.admin-menu-builder select,
        #hwpwrap.admin-menu-builder textarea {
            background-clip: padding-box;
            background-color: var(--bs-body-bg);
            border: var(--bs-border-width) solid var(--bs-border-color);
            border-radius: var(--bs-border-radius);
            color: var(--bs-body-color);
            display: block;
            font-size: 1rem;
            line-height: 1.5;
            max-width: 100%;
            min-height: calc(1.5em + .75rem + calc(var(--bs-border-width) * 2));
            padding: .375rem .75rem;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
            width: 100%;
        }

        #hwpwrap.admin-menu-builder input[type="text"]:focus,
        #hwpwrap.admin-menu-builder input[type="url"]:focus,
        #hwpwrap.admin-menu-builder select:focus,
        #hwpwrap.admin-menu-builder textarea:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 .25rem rgba(13, 110, 253, .25);
            outline: 0;
        }

        #hwpwrap.admin-menu-builder .customlinkdiv .howto {
            display: block;
            margin-bottom: .75rem;
        }

        #hwpwrap.admin-menu-builder .menu-source-tools {
            border-bottom: 1px solid var(--bs-border-color);
            margin: -.25rem 0 .5rem;
            padding: 0 0 .75rem;
        }

        #hwpwrap.admin-menu-builder .menu-source-filter {
            font-size: .875rem;
            min-height: calc(1.5em + .5rem + calc(var(--bs-border-width) * 2));
            padding: .25rem .5rem;
        }

        #hwpwrap.admin-menu-builder .menu-source-list {
            max-height: 360px;
            overflow-x: hidden;
            overflow-y: auto;
            padding-right: .25rem;
        }

        #hwpwrap.admin-menu-builder .menu-source-item {
            margin: 0;
        }

        #hwpwrap.admin-menu-builder .menu-source-item[hidden] {
            display: none !important;
        }

        #hwpwrap.admin-menu-builder .menu-source-label {
            align-items: center;
            border-radius: var(--bs-border-radius-sm);
            cursor: pointer;
            display: flex;
            gap: .5rem;
            line-height: 1.4;
            margin: 0;
            min-width: 0;
            padding: .4rem .35rem .4rem calc(.35rem + (var(--menu-source-depth, 0) * 1rem));
            width: 100%;
        }

        #hwpwrap.admin-menu-builder .menu-source-label:hover {
            background: var(--bs-tertiary-bg);
        }

        #hwpwrap.admin-menu-builder .menu-source-checkbox {
            flex: 0 0 auto;
            height: 1rem;
            margin: 0;
            width: 1rem;
        }

        #hwpwrap.admin-menu-builder .menu-source-title {
            display: block;
            flex: 1 1 auto;
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        #hwpwrap.admin-menu-builder .nav-menus-php .howto span {
            display: block;
            float: none;
            margin: 0 0 .25rem;
        }

        #hwpwrap.admin-menu-builder .wp-core-ui .btn {
            border-radius: var(--bs-border-radius);
            box-shadow: none;
            height: auto;
            line-height: 1.5;
            padding: .375rem .75rem;
            text-shadow: none;
        }

        #hwpwrap.admin-menu-builder .wp-core-ui .btn-sm {
            border-radius: var(--bs-border-radius-sm);
            font-size: .875rem;
            padding: .25rem .5rem;
        }

        #hwpwrap.admin-menu-builder .wp-core-ui .btn-primary.button-primary {
            background: var(--bs-primary);
            border-color: var(--bs-primary);
            color: var(--bs-white);
        }

        #hwpwrap.admin-menu-builder .wp-core-ui .btn-outline-primary.button-secondary {
            background: transparent;
            border-color: var(--bs-primary);
            color: var(--bs-primary);
        }

        #hwpwrap.admin-menu-builder .wp-core-ui .btn-outline-secondary.button-primary {
            background: transparent;
            border-color: var(--bs-secondary);
            color: var(--bs-secondary);
        }

        #hwpwrap.admin-menu-builder .alert p {
            margin-bottom: 0;
            overflow-wrap: anywhere;
        }

        #hwpwrap.admin-menu-builder .button-controls {
            align-items: center;
            display: flex;
            justify-content: flex-end;
            margin: 1rem 0 0;
        }

        #hwpwrap.admin-menu-builder .delete-action {
            float: none;
            margin: 0;
        }

        @media (max-width: 991.98px) {
            #hwpwrap.admin-menu-builder .menu-builder-frame.has-selected-menu {
                grid-template-columns: 1fr;
            }

            #hwpwrap.admin-menu-builder #menu-settings-column,
            #hwpwrap.admin-menu-builder #menu-management-liquid {
                grid-column: 1;
            }

            #hwpwrap.admin-menu-builder #menu-settings-column {
                grid-row: 1;
            }

            #hwpwrap.admin-menu-builder #menu-management-liquid {
                grid-row: 2;
            }
        }

        @media (max-width: 575.98px) {
            #hwpwrap.admin-menu-builder .manage-menus,
            #hwpwrap.admin-menu-builder .menu-edit-toolbar,
            #hwpwrap.admin-menu-builder #nav-menu-footer,
            #hwpwrap.admin-menu-builder #post-body {
                padding: .75rem;
            }

            #hwpwrap.admin-menu-builder .card-body {
                overflow-x: hidden;
            }

            #hwpwrap.admin-menu-builder .menu-item {
                margin-left: 0 !important;
            }

            #hwpwrap.admin-menu-builder #menu-to-edit,
            #hwpwrap.admin-menu-builder .menu-item-bar .menu-item-handle,
            #hwpwrap.admin-menu-builder .menu-item-settings {
                max-width: none;
                width: 100%;
            }

            #hwpwrap.admin-menu-builder .menu-item-handle .item-title {
                margin-right: 6rem;
            }

            #hwpwrap.admin-menu-builder .item-controls {
                right: .75rem;
            }

            #hwpwrap.admin-menu-builder .menu-source-list {
                max-height: 280px;
            }
        }
    </style>
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
                    <div class="card card-primary card-outline mb-4">

                        {{-- card-header --}}
                        <div class="card-header">
                            <h3 class="card-title mb-0">
                                <i class="fa-solid fa-bars me-2" aria-hidden="true"></i>{{ $title_head }}
                            </h3>
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
        document.addEventListener('input', function(event) {
            if (!event.target.matches('.menu-source-filter')) {
                return;
            }

            const query = event.target.value.trim().toLowerCase();
            const container = event.target.closest('.inside');
            if (!container) {
                return;
            }

            container.querySelectorAll('.menu-source-item').forEach(function(item) {
                const label = item.querySelector('.menu-source-title');
                const text = label ? label.textContent.trim().toLowerCase() : item.textContent.trim().toLowerCase();
                item.hidden = query !== '' && !text.includes(query);
            });
        });

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

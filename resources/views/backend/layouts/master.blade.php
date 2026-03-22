<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    {{-- begin::Primary Meta Tags --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />

    {{-- Match new-admin-ui (AdminLTE v4): theme + accessibility hints --}}
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
    <meta name="supported-color-schemes" content="light dark" />

    <meta name="title" content="{{ setting_option('webtitle') }}" />

    <meta name="author" content="OneHealth Foundation" />

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Favicon --}}
    {{-- <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon"> --}}

    <link rel="icon" type="image/png" sizes="16x16" href="{{ get_image(setting_option('favicon_16')) }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ get_image(setting_option('favicon_32')) }}">
    <link rel="icon" type="image/png" sizes="48x48" href="{{ get_image(setting_option('favicon_48')) }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ get_image(setting_option('favicon_96')) }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ setting_option('favicon_180') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ get_image(setting_option('favicon_192')) }}">

    @yield('seo')

    {{-- begin::Fonts --}}
    <link rel="stylesheet" href="{{ asset('assets/admin/css/index.css') }}" />

    {{-- begin::Third Party Plugin(OverlayScrollbars) — aligned with new-admin-ui @2.11.0 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" crossorigin="anonymous" />

    {{-- Font Awesome 6.4.2 --}}
    <link rel="stylesheet" href="{{ asset('assets/fontawesome_pro/css/all.min.css') }}">

    {{-- Bootstrap Icons (sidebar toggle, dashboard widgets, etc.) — new-admin-ui uses 1.13.1 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />

    {{-- AdminLTE v4 CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/admin/css/adminlte.min.css') }}?ver={{ time() }}">

    {{-- Datetime Picker --}}
    <link rel="stylesheet" href="{{ asset('assets/plugin/flatpickr/flatpickr.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/plugin/icheck-bootstrap/icheck-bootstrap.min.css') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap5-toggle@5.0.4/css/bootstrap5-toggle.min.css" rel="stylesheet">

    {{-- Jquery UI --}}
    <link rel="stylesheet" href="{{ asset('assets/plugin/jquery-ui/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugin/jquery-confirm-v3.3.4/jquery-confirm.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugin/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugin/datetimepicker/jquery.datetimepicker.min.css') }}">

    {{-- Chỉ override CMS: không trùng reset với AdminLTE/Bootstrap; xem public/assets/css/style_admin.css --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style_admin.css') }}?ver={{ time() }}">

    @stack('style')

    @stack('head-script')

</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">

    {{-- begin::App Wrapper --}}
    <div class="app-wrapper">

        {{-- begin::Header --}}
        @include('backend.layouts.nav')
        {{-- end::Header --}}

        {{-- Main Sidebar Container --}}
        @include('backend.layouts.sidebar')

        {{-- begin::App Main --}}
        <main class="app-main">
            @yield('content')
        </main>
        {{-- end::App Main --}}

        {{-- begin::Footer --}}
        @include('backend.layouts.footer')
        {{-- end::Footer --}}
    </div>
    {{-- end::App Wrapper --}}

    {{-- Main js --}}

    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}?ver={{ time() }}"></script>
    {{-- <script src="{{ asset('plugin/jquery-ui/jquery-ui.min.js') }}"></script> --}}

    <script src="{{ asset('assets/plugin/axios.min.js') }}"></script>

    {{-- begin::Third Party Plugin(OverlayScrollbars) --}}
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
    {{-- end::Third Party Plugin(OverlayScrollbars) --}}

    {{-- begin::Required Plugin(popperjs for Bootstrap 5) --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script> --}}
    {{-- end::Required Plugin(popperjs for Bootstrap 5) --}}

    {{-- begin::Required Plugin(Bootstrap 5) --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script> --}}
    {{-- end::Required Plugin(Bootstrap 5) --}}

    {{-- Boostrap 5.3 + Popperjs --}}
    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}?ver={{ time() }}"></script>

    {{-- AdminLTE v4 JS --}}
    <script src="{{ asset('assets/admin/js/adminlte.min.js') }}?ver={{ time() }}"></script>

    {{-- OPTIONAL SCRIPTS --}}

    {{-- Sortable :: Latest (https://www.jsdelivr.com/package/npm/sortablejs) --}}
    <script src="{{ asset('assets/plugin/Sortable/Sortable.min.js') }}"></script>


    {{-- apexcharts --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js" integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8=" crossorigin="anonymous"></script>
    {{-- <script src="{{ asset('assets/plugin/apexcharts/apexcharts.min.js') }}"></script> --}}


    {{-- jsvectormap --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js" integrity="sha256-/t1nN2956BT869E6H4V1dnt0X5pAQHPytli+1nTZm2Y=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js" integrity="sha256-XPpPaZlU8S/HWf7FZLAncLg2SAkP8ScUTII89x9D3lY=" crossorigin="anonymous"></script> --}}
    <script src="{{ asset('assets/plugin/jsvectormap/jsvectormap.js') }}"></script>
    <script src="{{ asset('assets/plugin/jsvectormap/world.js') }}"></script>

    {{-- <script src="{{ asset('assets/plugin/moment.min.js') }}"></script> --}}
    <script src="{{ asset('assets/plugin/datetimepicker/jquery.datetimepicker.full.min.js') }}"></script>

    {{-- Datetime Picker --}}
    <script src="{{ asset('assets/plugin/flatpickr/flatpickr.js') }}"></script>

    <script src="{{ asset('assets/plugin/jquery-validation/jquery.validate.min.js') }}"></script>

    {{-- <script src="{{ asset('assets/plugin/jquery-confirm-v3.3.4/jquery-confirm.min.js') }}"></script> --}}
    <script src="{{ asset('assets/plugin/sweetalert2@11/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/select2/js/select2.full.min.js') }}"></script>

    {{-- https://gitbrent.github.io/bootstrap4-toggle/ (Bootstrap 5 compatible) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap5-toggle@5.0.4/js/bootstrap5-toggle.jquery.min.js"></script>

    <script src="{{ asset('assets/plugin/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/plugin/ckfinder/ckfinder.js') }}"></script>

    {{-- Custom JS --}}
    <script src="{{ asset('assets/js/js_admin.js') }}?ver={{ time() }}"></script>

    {{-- URL --}}
    <script type="text/javascript">
        var admin_url = '{{ route('admin.dashboard') }}';
    </script>

    <script>
        CKEDITOR.editorConfig = function(config) {
            config.pasteFromWordPromptCleanup = true;
            config.pasteFromWordRemoveFontStyles = false;
            config.pasteFromWordRemoveStyles = false;
        };

        CKFinder.config({
            connectorPath: '/ckfinder/connector',
        });
    </script>

    {{-- begin::OverlayScrollbars Configure (same as new-admin-ui: skip on mobile to avoid touch issues) --}}
    <script>
        const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
        const Default = {
            scrollbarTheme: 'os-theme-light',
            scrollbarAutoHide: 'leave',
            scrollbarClickScroll: true,
        };
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
            const isMobile = window.innerWidth <= 992;
            if (
                sidebarWrapper &&
                typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined' &&
                !isMobile
            ) {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: {
                        theme: Default.scrollbarTheme,
                        autoHide: Default.scrollbarAutoHide,
                        clickScroll: Default.scrollbarClickScroll,
                    },
                });
            }
        });
    </script>
    {{-- end::OverlayScrollbars Configure --}}

    @stack('scripts')

    @stack('scripts-footer')

</body>

</html>

<!DOCTYPE html>

<html lang="vi">



<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">



    <meta name="csrf-token" content="{{ csrf_token() }}">



    {{-- Favicon --}}

    <link rel="shortcut icon" href="{{ get_image(setting_option('favicon')) }}" />



    {{-- SEO meta --}}

    @yield('seo')



    {{-- Main Style CSS --}}

    @vite(['resources/css/app.css', 'resources/scss/style.scss', 'resources/js/app.js'])



    {!! htmlspecialchars_decode(setting_option('header')) !!}



    @stack('head-style')



    @stack('head-script')

</head>



<body class="bg-leaf-50 font-sans text-gray-800">



    @include('frontend.layouts.header')



    {{-- Single document landmark: inner pages use <div id="main"> (or similar), not nested <main>. --}}
    <main id="app" class="">

        @yield('content')

    </main>



    {{-- <a href="https://zalo.me/{{ setting_option('zalo') }}" target="_blank" class="call-zalo" title="{{ setting_option('webtitle') }}">

        <img src="{{ asset('assets/images/icon/icon-zalo.webp') }}" class="img-fluid" alt="zalo" />

    </a>



    <a href="tel:{{ setting_option('phone') }}" class="call-now" rel="nofollow" title="{{ setting_option('webtitle') }}">

        <div class="mh-contact">

            <div class="animated infinite zoomIn mh-alo-ph-circle"></div>

            <div class="animated infinite pulse mh-alo-ph-circle-fill"></div>

            <div class="animated infinite tada mh-img-circle">

                <i class="fa-solid fa-phone"></i>

            </div>

        </div>

    </a> --}}



    @include('frontend.layouts.footer')

    @include('frontend.layouts.app-routes')

    {{-- AI Chatbot Assistant Widget --}}
    @include('frontend.components.ai-chat-widget')

    @stack('scripts')



</body>



</html>

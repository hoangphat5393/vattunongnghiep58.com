@extends('frontend.layouts.master')
{{-- @section('seo')
    <title>{{ setting_option('seo-title-add') }}</title>
    <link rel="canonical" href="{{ url('/') }}" />
    <meta name="robots" content="index, follow">
    <meta name="description" content="{{ setting_option('seo-description-add') }}">
    <meta property="og:title" content="{{ setting_option('og-title') }}" />
    <meta property="og:description" content="{{ setting_option('og-description') }}" />
    <meta property="og:image" content="{{ setting_option('og-image') ? url(setting_option('og-image')) : '' }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:site_name" content="{{ setting_option('og-site-name') }}" />
@endsection --}}
@section('content')
    {{-- error --}}
    <section class="space-ptb bg-holder my-5">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-md-6">
                    <div class="error-404 text-center">
                        <h1>404</h1>
                        <strong>Trang bạn tìm kiếm không tồn tại</strong>
                        <span>Quay về <a href="{{ route('index') }}"> Trang chủ </a></span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- error --}}
@endsection

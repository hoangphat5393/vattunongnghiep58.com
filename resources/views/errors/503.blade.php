@extends('frontend.layouts.master')

@section('seo')
    <title>{{ setting_option('seo-title-add') }}</title>
    <link rel="canonical" href="{{ url('/') }}" />
    <meta name="robots" content="index, follow">
    <meta name="description" content="{{ setting_option('seo-description-add') }}">
    <meta property="og:title" content="{{ setting_option('og-title') }}" />
    <meta property="og:description" content="{{ setting_option('og-description') }}" />
    <meta property="og:image" content="{{ url(setting_option('og-image')) }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:site_name" content="{{ setting_option('og-site-name') }}" />
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col text-center y-wrapper-60">
                <hr class="w-50 mx-auto mt-4">
                <h2>Under maintenance</h2>
                <p>Please come back latter</p>
                <div class="tbl_back clear">
                </div>
            </div>
        </div>
    </div>
@endsection

@php extract($data); @endphp

@extends('frontend.layouts.master')

@section('seo')
@include($templatePath . '.layouts.seo', $seo ?? [])
@endsection

@section('content')
<main id="about">
    <section class="block10">
        <div class="mainBanner">
            <div class="container main-menu">
                @include('frontend.includes.menu')
            </div>
            <div class="container-fluid px-0">
                <div class="row g-0">
                    <div class="col-lg-12 banner-left">
                        <img class="img-fluid object-fit-cover w-100" src="{{ get_image($page->image) }}" alt="{{ $page->name }}">
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Page content --}}
    <section class="block8">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="category-title">{{ $page->title }}</h1>
                </div>
            </div>
        </div>
        <div class="container">
            {!! htmlspecialchars_decode($page->content) !!}
        </div>
    </section>
    {{-- About content End --}}

    {{-- Subscribe --}}
    @include('frontend.includes.subscribe')
</main>
@endsection


@push('scripts')
{{-- <script>
        var main_splide = new Splide('.main-splide', {
            arrows: false,
            gap: '1.25rem',
            pagination: true,
            arrows: false
        });
        main_splide.mount();
    </script> --}}
@endpush
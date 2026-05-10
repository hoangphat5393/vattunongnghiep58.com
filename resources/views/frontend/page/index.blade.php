@php extract($data); @endphp

@extends('frontend.layouts.master')

@section('seo')
    @include('frontend.layouts.seo', $seo ?? [])
@endsection

@section('content')
    <div id="about">
        {{-- Page content --}}
        {{-- <section class="block8">
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
        </section> --}}

        {!! htmlspecialchars_decode($page->content) !!}
    </div>
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

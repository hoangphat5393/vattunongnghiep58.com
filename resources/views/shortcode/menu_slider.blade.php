@php
    $slider_main = \App\Models\Slider::where('status', 0)->where('slider_id', 70)->orderBy('sort', 'asc')->get();
@endphp

@empty(!$slider_main)

    {{-- Slider main container --}}
    <div class="swiper mainSlider">

        <div class="container main-menu">
            @include('theme.includes.menu')
        </div>

        {{-- Additional required wrapper --}}
        <div class="swiper-wrapper">
            {{-- Slides --}}
            @foreach ($slider_main as $item)
                <div class="swiper-slide">
                    <img class="w-100 h-100" src="{{ get_image($item->image) }}" alt="{{ $item->name }}">
                </div>
            @endforeach
        </div>

        <!-- If we need pagination -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-pagination"></div>

        <!-- If we need navigation buttons -->
        {{-- <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div> --}}
        <!-- If we need scrollbar -->
        {{-- <div class="swiper-scrollbar"></div> --}}
    </div>
@endempty

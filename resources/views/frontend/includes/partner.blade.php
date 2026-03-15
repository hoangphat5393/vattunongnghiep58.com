@php
    $partner = \App\Models\Slider::where('status', 0)->where('slider_id', 80)->orderBy('sort', 'asc')->get();
@endphp
<section class="block5">
    <div class="container">
        <p class="fw-bold fs-4 mb-4 text-uppercase">@lang('Main partner')</p>
        <div class="swiper partnerSlider">
            <div class="swiper-wrapper">
                @foreach ($partner as $item)
                    <div class="swiper-slide">
                        <a href="{{ $item->link }}" target="{{ $item->target }}">
                            <img class="img-fluid mx-auto" src="{{ get_image($item->image) }}" alt="{{ $item->name }}">
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>

    </div>
</section>


@push('scripts')
    <!-- Initialize Swiper -->
    <script type="module">
        var partnerSlider = new Swiper(".partnerSlider", {
            slidesPerView: 6,
            spaceBetween: 30,
            grabCursor: true,
            a11y: false,
            freeMode: true,
            speed: 10000,
            loop: true,
            autoplay: {
                delay: 0.5,
                disableOnInteraction: false,
            },
            breakpoints: {
                768: {
                    slidesPerView: 3,
                },
                992: {
                    slidesPerView: 4,
                },
                1200: {
                    slidesPerView: 6,
                },
            },
            // pagination: {
            //     el: ".swiper-pagination",
            //     clickable: true,
            // },
        });
    </script>
@endpush

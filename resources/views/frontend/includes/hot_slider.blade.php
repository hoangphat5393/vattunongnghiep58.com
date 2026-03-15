@php
    $hot_product = \App\Models\Product::where(['status' => 1, 'hot' => 1])
        ->orderBy('sort', 'asc')
        ->get();
@endphp


<section class="block5">
    <div class="container-fluid px-0">
        <div class="hot-slider">

            @if ($hot_product->count() > 0)

                <div class="swiper hotProduct">
                    <div class="swiper-wrapper">
                        @foreach ($hot_product as $item)
                            <div class="swiper-slide">
                                <div class="item product-item">
                                    <figure class="text-center">
                                        <a href="{{ route('product.detail', [$item->slug, $item->id]) }}">
                                            <img class="w-100" src="{{ get_image($item->image) }}" alt="{{ $item->name }}">
                                        </a>
                                        <figcaption><a href="{{ route('product.detail', [$item->slug, $item->id]) }}">{{ $item->name }}</a></figcaption>
                                        Giá:
                                        <span class="price">
                                            {{ number_format($item->price, 0, ',', '.') }} đ
                                        </span>
                                    </figure>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            @endif
        </div>
    </div>
</section>


@push('scripts')
    <!-- Initialize Swiper -->
    <script type="module">
        // if ($(".hotProduct")[0]) {
        //     // Do something if class exists
        // } else {
        //     // Do something if class does not exist
        // }
        var hotProduct = new Swiper(".hotProduct", {
            slidesPerView: 2,
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

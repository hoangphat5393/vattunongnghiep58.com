<section id="main-categories">
    <div class="container my-4">
        <h2>SẢN PHẨM</h2>
        <div class="animation-line">
            <div class="line"></div>
        </div>
        @if ($categories->count() > 0)
            <div class="splide main-categories mt-5" role="group" aria-label="Splide Basic HTML Example">
                <div class="splide__track">
                    <ul class="splide__list">
                        @php $i = 0; @endphp
                        @foreach ($categories as $item)
                            <li class="splide__slide">
                                <a href="{{ route('product.detail', $item->slug) }}" @class(['active' => $i == 0])>
                                    <img src="{{ get_image($item->icon) }}" />
                                    <p>{{ $item->name }}</p>
                                </a>
                            </li>
                            @php $i++; @endphp
                        @endforeach
                    </ul>
                </div>
                <ul class="splide__pagination"></ul>
            </div>
        @endif
    </div>
</section>


@push('scripts')
    <script>
        var categories_splide = new Splide('.main-categories', {
            type: 'slide',
            gap: '1.25rem',
            arrows: false,
            perPage: 7,
            breakpoints: {
                576: {
                    perPage: 2,
                    perMove: 2,
                    arrows: false,
                },
                767: {
                    perPage: 3,
                    perMove: 3,
                },
                998: {
                    perPage: 4,
                    perMove: 4,
                    pagination: true,
                    arrows: true,
                },
            },
        });
        categories_splide.mount();
    </script>
@endpush

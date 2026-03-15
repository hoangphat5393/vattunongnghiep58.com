@inject('Category', 'App\Models\Category')
@php
    $categories = $Category::where('status', 1)->orderbyDesc('sort')->get();
@endphp
<div class="container-fluid paint-block">
    <div class="container">
        @if ($categories->count() > 0)
            <div class="splide paint-slider ">
                <div class="splide__track">
                    <div class="splide__list">
                        @foreach ($categories as $item)
                            <div class="splide__slide item-paint">
                                <a href="{{ route('page', 'product') }}">
                                    <div class="thumb">
                                        <img src="{{ get_image($item->icon) }}" />
                                        {{-- <img src="{{ get_image($item->icon) }}" /> --}}
                                        {{-- <img src="{{ asset('images/white-paint.png') }}" /> --}}
                                    </div>
                                    <div class="content">
                                        <h5 class="text-truncate">{{ $item->name }}</h5>
                                        <div class="description">
                                            {!! htmlspecialchars_decode($item->description) !!}
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>


@push('scripts')
    <script>
        var paint_slider = new Splide('.paint-slider', {
            type: 'loop',
            perPage: 5,
            perMove: 1,
            pagination: true,
            // padding: '5rem',
            // fixedWidth: 154,
            arrows: true,
            pagination: false,
            // breakpoints: {
            //     576: {
            //         perPage: 1,
            //     },
            //     767: {
            //         perPage: 2,
            //         arrows: false,
            //     },
            //     998: {
            //         perPage: 3,
            //     },
            //     1200: {
            //         perPage: 4,
            //     },

            //     1400: {
            //         perPage: 5,
            //     },
            // },
        });
        paint_slider.mount();
    </script>
@endpush

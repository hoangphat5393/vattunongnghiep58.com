@php
    extract($data);
@endphp

@extends($templatePath . '.layouts.index')


@section('content')

    <section class="space-ptb bg-light ">
        <div class="container">
            <div class="row align-items-center justify-content-center mb-4">
                <div class="col-lg-8 text-center">
                    <div class="section-title mb-0 mt-4">
                        <h3 class="d-inline">Wishlists</h3>
                    </div>
                </div>
            </div>
            <div class="row grid-products">
                @isset($wishlist)
                    @foreach ($wishlist as $product)
                        @include($templatePath . '.product.product-item', compact('product'))
                    @endforeach
                @else
                    <div class="text-center py-5">
                        <img src="{{ asset('theme/images/marking-listing-empty.svg') }}" alt="">
                    </div>
                @endisset
            </div>
        </div>
    </section>


    @push('scripts')
        <script src="{{ asset('theme/js/customer.js') }}"></script>
    @endpush
@endsection

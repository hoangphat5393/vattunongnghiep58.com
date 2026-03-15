@extends('frontend.layouts.master')

@section('seo')
    @include('frontend.layouts.seo', $seo ?? [])
@endsection

@php
    use Carbon\Carbon;
    Carbon::setLocale('vi');
@endphp

@section('content')
    <main id="main" class="product-category">
        {{-- Menu --}}
        @include('frontend.includes.menu')

        <div class="container mt-4">
            <div class="row">

                <div class="col-lg-3 order-2 order-lg-1">
                    {{-- Category --}}
                    <div class="row main-cat mb-3">
                        @include('frontend.includes.left_sidebar')
                    </div>
                </div>

                @empty(!$category)
                    <div class="col-lg-9 order-1 order-lg-2 mb-5 main-product">
                        <div class="row mb-3">
                            <div class="col-12">
                                <h3>{{ $category->name }}</h3>

                                <div class="row my-4">
                                    {{-- Product item --}}
                                    @foreach ($product as $item)
                                        <div class="col-6 col-md-3">
                                            <div class="product-item mb-3">
                                                <figure class="text-center">
                                                    <a href="{{ route('product.detail', [$item->slug, $item->id]) }}" title="{{ $item->name }}">
                                                        <img class="w-100" src="{{ $item->image }}" alt="{{ $item->name }}">
                                                    </a>
                                                </figure>
                                                <div class="product-name text-center">
                                                    <a href="{{ route('product.detail', [$item->slug, $item->id]) }}">
                                                        {{ $item->name }}
                                                    </a>
                                                </div>
                                                <p class="text-center">
                                                    Giá:
                                                    @if ($item->price_type == 'price')
                                                        <span class="price">
                                                            {{ number_format($item->price, 0, ',', '.') }} đ
                                                        </span>
                                                    @else
                                                        <span class="price">
                                                            <a href="tel:{{ setting_option('phone') }}">Liên hệ</a>
                                                        </span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="nav-pagination">
                                    {{ $product->links('frontend.pagination.custom') }}
                                </div>

                            </div>
                        </div>
                    </div>
                @endempty
            </div>
        </div>
    </main>

@endsection


@push('scripts')
@endpush

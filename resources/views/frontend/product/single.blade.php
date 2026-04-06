@extends('frontend.layouts.master')
@section('seo')
    @include('frontend.layouts.seo', $seo ?? [])
@endsection

@push('head-style')
    <style>
        .product-thumb-slider .swiper-slide-thumb-active .thumb-item {
            border-color: #4bac4d;
        }

        .product-main-slider {
            overflow: hidden;
            background: #fff;
        }

        .product-main-slider .swiper-button-next,
        .product-main-slider .swiper-button-prev {
            display: none;
            /* Hide navigation arrows as per design */
        }

        .product-main-slider:hover .swiper-button-next,
        .product-main-slider:hover .swiper-button-prev {
            display: flex;
        }

        .product-main-slider .swiper-button-next:after,
        .product-main-slider .swiper-button-prev:after {
            font-size: 20px;
            font-weight: bold;
        }

        .product-main-slider .swiper-button-next,
        .product-main-slider .swiper-button-prev {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            color: #333;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .thumb-item {
            transition: all 0.3s ease;
        }

        .thumb-item:hover,
        .swiper-slide-thumb-active .thumb-item {
            border-color: #4bac4d;
        }

        /* Swiper Thumbs Fixed Size */
        .product-thumb-slider .swiper-slide {
            width: 80px !important;
            height: 80px !important;
        }
    </style>
@endpush

@php
    // dd($gallery);
    use Carbon\Carbon;
    Carbon::setLocale('vi');

    $galleryRaw = null;
    if (isset($product)) {
        $attrs = $product->getAttributes();
        $galleryRaw = $attrs['gallery'] ?? null;
        extract($attrs);
    }

    $gallery = [];
    if (is_array($galleryRaw)) {
        $gallery = $galleryRaw;
    } elseif (is_string($galleryRaw) && $galleryRaw !== '') {
        $unserialized = @unserialize($galleryRaw);
        if ($unserialized !== false || $galleryRaw === 'b:0;') {
            $gallery = is_array($unserialized) ? $unserialized : [];
        } else {
            $decoded = json_decode($galleryRaw, true);
            $gallery = is_array($decoded) ? $decoded : [];
        }
    }

    $product_prices = collect();
    $default_price = null;
    try {
        $product_prices = $product->prices()->where('status', 1)->get();
        $default_price = $product_prices->firstWhere('is_default', true) ?? $product_prices->first();
    } catch (\Throwable $e) {
        $product_prices = collect();
        $default_price = null;
    }

    $cdt = new Carbon($product->created_at);
@endphp

@section('content')
    @include('frontend.includes.menu')

    <div class="container px-4 py-4 mx-auto">
        <div class="flex gap-2 items-center text-sm text-gray-500">
            <a href="{{ route('index') }}" class="hover:text-leaf-600">Trang chủ</a>
            <span>/</span>
            <a href="{{ route('product') }}" class="hover:text-leaf-600">Sản phẩm</a>
            <span>/</span>
            <span class="font-bold text-leaf-700 line-clamp-1">{{ $product->name }}</span>
        </div>
    </div>

    <section class="py-8 md:py-12">
        <div class="container px-4 mx-auto">
            <div class="p-6 bg-white rounded-3xl border border-gray-100 shadow-xl md:p-10">
                <div class="flex flex-col gap-10 md:flex-row">
                    <div class="relative md:w-1/2">
                        {{-- Main Slider --}}
                        <div style="--swiper-navigation-color: #000; --swiper-pagination-color: #000; aspect-ratio: 1/1;" class="overflow-hidden relative mb-4 bg-white rounded-2xl border border-gray-100 swiper product-main-slider group">

                            {{-- Hot Badge --}}
                            @if (isset($product->hot) && $product->hot == 1)
                                <div class="absolute top-4 left-4 z-10 px-3 py-1 text-xs font-bold text-white bg-red-600 rounded-full shadow-md">
                                    HOT
                                </div>
                            @endif

                            <div class="swiper-wrapper">
                                <div class="flex justify-center items-center p-4 h-full bg-white swiper-slide">
                                    <img src="{{ get_image($product->image) }}" onerror="this.onerror=null;this.src='{{ asset('assets/images/placeholder.png') }}';" alt="{{ $product->name }}" class="block object-contain w-full h-full" loading="lazy" />
                                </div>
                                @if ($gallery)
                                    @foreach ($gallery as $item)
                                        <div class="flex justify-center items-center p-4 h-full bg-white swiper-slide">
                                            <img src="{{ get_image($item) }}" onerror="this.onerror=null;this.src='{{ asset('assets/images/placeholder.png') }}';" alt="{{ $product->name }}" class="block object-contain w-full h-full" loading="lazy" />
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                        </div>

                        {{-- Thumb Slider --}}
                        @if ($gallery)
                            <div class="pb-2 swiper product-thumb-slider">
                                <div class="swiper-wrapper">
                                    <div class="cursor-pointer swiper-slide">
                                        <div class="overflow-hidden p-1 w-full h-full bg-white rounded-lg border-2 border-gray-200 thumb-item">
                                            <img src="{{ get_image($product->image) }}" onerror="this.onerror=null;this.src='{{ asset('assets/images/placeholder.png') }}';" alt="{{ $product->name }}" class="block object-cover w-full h-full rounded" loading="lazy" />
                                        </div>
                                    </div>
                                    @foreach ($gallery as $item)
                                        <div class="cursor-pointer swiper-slide">
                                            <div class="overflow-hidden p-1 w-full h-full bg-white rounded-lg border-2 border-gray-200 thumb-item">
                                                <img src="{{ get_image($item) }}" onerror="this.onerror=null;this.src='{{ asset('assets/images/placeholder.png') }}';" alt="{{ $product->name }}" class="block object-cover w-full h-full rounded" loading="lazy" />
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="md:w-1/2">
                        <div class="mb-2">
                            <span class="px-3 py-1 text-xs font-bold tracking-wide uppercase rounded-full bg-leaf-100 text-leaf-700">
                                Sản phẩm
                            </span>
                        </div>
                        <h1 class="mb-4 text-3xl font-extrabold text-gray-900 md:text-4xl">
                            {{ $product->name }}
                        </h1>

                        <div class="mb-6 text-2xl font-extrabold md:text-3xl text-leaf-600">
                            @if ($product->price_type == 'price' && ($default_price || $product->price))
                                <span id="product_price_display" data-base="{{ (int) ($default_price->price ?? ($product->price ?? 0)) }}">
                                    {{ number_format((int) ($default_price->price ?? ($product->price ?? 0)), 0, ',', '.') }} đ
                                </span>
                            @else
                                <a href="tel:{{ setting_option('phone') }}" class="cursor-pointer text-leaf-600">
                                    Liên hệ: {{ setting_option('phone') }}
                                </a>
                            @endif
                        </div>

                        <div class="mb-8 leading-relaxed text-gray-600">
                            {!! htmlspecialchars_decode($product->description) !!}
                        </div>

                        @if ($product->price_type == 'price' && ($default_price || $product->price))
                            <form id="product_form_addCart" action="{{ route('cart.addCart') }}" class="space-y-6">
                                <input type="hidden" name="product" value="{{ $product->id }}">
                                <input type="hidden" name="product_price_id" id="product_price_id" value="{{ $default_price->id ?? '' }}">

                                @if ($product_prices->count() > 0)
                                    <div class="flex flex-col gap-2">
                                        <span class="font-bold text-gray-700">Chọn mức giá:</span>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach ($product_prices as $pp)
                                                <button
                                                    type="button"
                                                    data-product-price-option
                                                    data-id="{{ $pp->id }}"
                                                    data-price="{{ (int) $pp->price }}"
                                                    data-unit="{{ $pp->unit ?? '' }}"
                                                    aria-pressed="{{ $default_price && $pp->id == $default_price->id ? 'true' : 'false' }}"
                                                    class="px-4 py-2 rounded-xl border-2 font-bold text-sm transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-leaf-400/60
                                                        @if ($default_price && $pp->id == $default_price->id)
                                                            bg-leaf-600 border-leaf-600 text-white hover:bg-leaf-700 hover:border-leaf-700
                                                        @else
                                                            bg-white border-gray-200 text-gray-800 hover:text-gray-900 hover:border-leaf-300 hover:bg-leaf-50
                                                        @endif
                                                    ">
                                                    <span>{{ $pp->label }}</span>
                                                    <span class="font-extrabold">- {{ number_format((int) $pp->price, 0, ',', '.') }} đ</span>
                                                    @if ($pp->unit)
                                                        <span class="opacity-90">/ {{ $pp->unit }}</span>
                                                    @endif
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                <div class="flex gap-4 items-center">
                    <span class="w-24 font-bold text-gray-700">Số lượng:</span>
                    <div class="flex items-center rounded-lg border-2 border-gray-200">
                        <button type="button" class="px-3 py-2 text-gray-600 rounded-l-md cursor-pointer hover:text-leaf-600 hover:bg-gray-100 quantity-btn minus">
                            -
                        </button>
                        <input type="text" id="quantity_field" class="w-12 font-bold text-center text-gray-700 border-none cursor-text focus:outline-none qtyField quantity_field" name="qty" step="1" min="1" value="1" size="8" placeholder="0" pattern="[0-9]*" inputmode="numeric">
                        <button type="button" class="px-3 py-2 text-gray-600 rounded-r-md cursor-pointer hover:text-leaf-600 hover:bg-gray-100 quantity-btn plus">
                            +
                        </button>
                    </div>
                </div>

                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('page', 'contact') }}" class="flex gap-2 items-center px-6 py-3 font-bold rounded-xl border-2 transition cursor-pointer border-leaf-600 text-leaf-700 hover:bg-leaf-50">
                        Giá sỉ - Liên hệ
                    </a>

                    <button type="button" class="flex flex-1 gap-2 justify-center items-center px-6 py-3 font-bold text-white rounded-xl shadow-lg transition transform cursor-pointer bg-leaf-600 shadow-leaf-500/30 hover:bg-leaf-700 hover:-translate-y-1 product-form__cart-add">
                        Thêm vào giỏ
                    </button>
                </div>
                </form>
                @endif
            </div>
        </div>

        <div class="mt-12">
            <div class="mb-6 border-b border-gray-200">
                <div class="flex gap-8 -mb-px" aria-label="Tabs">
                    <div class="px-1 py-4 text-lg font-bold whitespace-nowrap border-b-2 border-leaf-500 text-leaf-600">
                        Thông tin chi tiết
                    </div>
                </div>
            </div>
            <div class="py-4 space-y-4 leading-relaxed text-gray-600">
                {!! htmlspecialchars_decode($product->content) !!}
            </div>
        </div>
        </div>
        </div>
    </section>

    @if (isset($related_products) && $related_products->isNotEmpty())
        <section class="py-4 md:py-8">
            <div class="container px-4 mx-auto">
                <h2 class="mb-8 text-2xl font-bold text-gray-900">Sản phẩm liên quan</h2>
                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($related_products as $related)
                        <div class="relative p-4 bg-white rounded-3xl border border-gray-100 shadow-lg transition duration-300 hover:shadow-2xl hover:-translate-y-2 group">
                            <div class="overflow-hidden relative mb-4 h-64 rounded-2xl">
                                <a href="{{ route('product.detail', [$related->slug, $related->id]) }}" class="block w-full h-full">
                                    <img src="{{ get_image($related->image) }}" alt="{{ $related->name }}" class="object-cover w-full h-full" />
                                </a>
                            </div>
                            <h3 class="mb-1 text-lg font-bold text-gray-800 transition group-hover:text-leaf-600 line-clamp-2">
                                <a href="{{ route('product.detail', [$related->slug, $related->id]) }}">
                                    {{ $related->name }}
                                </a>
                            </h3>
                            <div class="flex justify-between items-center mt-3">
                                @if ($related->price)
                                    <span class="text-xl font-extrabold text-leaf-700">
                                        {{ number_format($related->price, 0, ',', '.') }} đ
                                    </span>
                                @else
                                    <span class="text-sm font-semibold text-leaf-700">
                                        <a href="tel:{{ setting_option('phone') }}">Liên hệ</a>
                                    </span>
                                @endif
                                <a href="{{ route('product.detail', [$related->slug, $related->id]) }}" class="p-2 rounded-full transition bg-leaf-100 text-leaf-700 hover:bg-leaf-500 hover:text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            try {
                var priceHidden = document.getElementById('product_price_id');
                var priceDisplay = document.getElementById('product_price_display');

                var priceButtons = document.querySelectorAll('[data-product-price-option]');
                if (priceButtons.length && priceHidden) {
                    priceButtons.forEach(function(btn) {
                        btn.addEventListener('click', function() {
                            var id = btn.getAttribute('data-id') || '';
                            var price = parseInt(btn.getAttribute('data-price') || '0', 10) || 0;
                            priceHidden.value = id;

                            priceButtons.forEach(function(b) {
                                b.setAttribute('aria-pressed', 'false');
                                b.classList.remove('bg-leaf-600', 'border-leaf-600', 'text-white');
                                b.classList.add('bg-white', 'border-gray-200', 'text-gray-800');
                                b.classList.remove('hover:bg-leaf-700', 'hover:border-leaf-700');
                                b.classList.add('hover:text-gray-900', 'hover:border-leaf-300', 'hover:bg-leaf-50');
                            });

                            btn.setAttribute('aria-pressed', 'true');
                            btn.classList.remove('bg-white', 'border-gray-200', 'text-gray-800');
                            btn.classList.add('bg-leaf-600', 'border-leaf-600', 'text-white');
                            btn.classList.remove('hover:text-gray-900', 'hover:border-leaf-300', 'hover:bg-leaf-50');
                            btn.classList.add('hover:bg-leaf-700', 'hover:border-leaf-700');

                            if (priceDisplay) {
                                priceDisplay.textContent = price.toLocaleString('vi-VN') + ' đ';
                            }
                        });
                    });
                }

                var qtyInput = document.getElementById('quantity_field');
                // ... (rest of the existing variables) ...
                var minusBtn = document.querySelector('.quantity-btn.minus');
                var plusBtn = document.querySelector('.quantity-btn.plus');

                var updateQty = function(delta) {
                    if (!qtyInput) return;
                    var current = parseInt(qtyInput.value || '1', 10);
                    if (isNaN(current) || current < 1) current = 1;
                    current += delta;
                    if (current < 1) current = 1;
                    qtyInput.value = current;
                };

                if (minusBtn) {
                    minusBtn.addEventListener('click', function() {
                        updateQty(-1);
                    });
                }

                if (plusBtn) {
                    plusBtn.addEventListener('click', function() {
                        updateQty(1);
                    });
                }

                // Thêm giỏ: resources/js/custom.js (res.data.error === 0). Không duplicate axios ở đây.

                // Swiper Initialization - Thumbs Gallery Pattern
                function initProductSwiper() {
                    if (typeof Swiper === 'undefined') {
                        setTimeout(initProductSwiper, 100);
                        return;
                    }

                    // 1. Initialize Thumbs Swiper first
                    var thumbSlider = new Swiper(".product-thumb-slider", {
                        spaceBetween: 10,
                        slidesPerView: 4,
                        freeMode: true,
                        watchSlidesProgress: true,
                        breakpoints: {
                            640: {
                                slidesPerView: 5,
                            },
                            1024: {
                                slidesPerView: 5,
                            }
                        }
                    });

                    // 2. Initialize Main Swiper with Thumbs linked
                    var mainSlider = new Swiper(".product-main-slider", {
                        spaceBetween: 10,
                        navigation: {
                            nextEl: ".swiper-button-next",
                            prevEl: ".swiper-button-prev",
                        },
                        thumbs: {
                            swiper: thumbSlider,
                        },
                    });
                }

                initProductSwiper();
            } catch (error) {
                console.error('Product Detail Page Script Error:', error);
            }
        });
    </script>
@endpush

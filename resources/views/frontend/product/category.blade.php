@extends('frontend.layouts.master')

@section('seo')
    @include('frontend.layouts.seo', $seo ?? [])
@endsection

@section('content')
    <div id="main" class="product-category">
        @include('frontend.includes.menu')

        <div class="container mx-auto px-4 py-4">
            <div class="flex flex-wrap items-center gap-2 text-sm text-gray-500">
                <a href="{{ route('index') }}" class="hover:text-leaf-600">Trang chủ</a>
                <span>/</span>
                <a href="{{ route('product') }}" class="hover:text-leaf-600">Sản phẩm</a>
                <span>/</span>
                <span class="font-bold text-leaf-700">{{ $category->name }}</span>
            </div>
        </div>

        <section class="container mx-auto flex flex-col gap-8 overflow-visible px-4 py-8 md:flex-row">
            <aside class="w-full shrink-0 md:sticky md:top-[var(--site-header-sticky-height)] md:z-30 md:w-1/4 md:self-start">
                @include('frontend.product.includes.sidebar-categories')
            </aside>

            <div class="md:w-3/4">
                <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 md:text-2xl">{{ $category->name }}</h1>
                        @if ($category->children && $category->children->isNotEmpty())
                            <div class="mt-3 flex flex-wrap gap-2">
                                @foreach ($category->children as $sub)
                                    <a href="{{ route('product.category', $sub->slug) }}" class="inline-flex items-center rounded-full border border-leaf-200 bg-white px-3 py-1 text-sm font-semibold text-leaf-800 shadow-sm transition hover:border-leaf-400 hover:bg-leaf-50">
                                        {{ $sub->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                @if ($product->count() > 0)
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($product as $item)
                            <div class="group overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition duration-300 hover:shadow-xl">
                                <div class="relative h-64 overflow-hidden">
                                    <a href="{{ route('product.detail', [$item->slug, $item->id]) }}" class="block h-full w-full" title="{{ $item->name }}">
                                        <img src="{{ get_image($item->image) }}" alt="{{ $item->name }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-110" loading="lazy" />
                                    </a>
                                </div>
                                <div class="p-4">
                                    <h2 class="mb-2 line-clamp-2 text-lg font-bold text-gray-900 transition group-hover:text-leaf-600">
                                        <a href="{{ route('product.detail', [$item->slug, $item->id]) }}">
                                            {{ $item->name }}
                                        </a>
                                    </h2>
                                    <div class="flex items-center justify-between">
                                        @if ($item->price_type == 'price')
                                            <span class="text-xl font-extrabold text-leaf-600">
                                                {{ number_format($item->price, 0, ',', '.') }} đ
                                            </span>
                                        @else
                                            <span class="text-sm font-semibold text-leaf-600">
                                                <a href="tel:{{ setting_option('phone') }}">Liên hệ</a>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{ $product->links('frontend.pagination.tailwind') }}
                @else
                    <div class="rounded-2xl border border-dashed border-leaf-200 bg-white/80 px-6 py-14 text-center shadow-sm">
                        <p class="text-lg font-semibold text-gray-800">Chưa có sản phẩm trong danh mục này</p>
                        <p class="mt-1 text-sm text-gray-600">Bạn có thể xem các danh mục khác hoặc quay lại trang sản phẩm.</p>
                        <a href="{{ route('product') }}" class="mt-6 inline-flex items-center justify-center rounded-xl bg-leaf-600 px-5 py-3 text-sm font-bold text-white shadow-md transition hover:bg-leaf-700">
                            Tất cả sản phẩm
                        </a>
                    </div>
                @endif
            </div>
        </section>
    </div>
@endsection

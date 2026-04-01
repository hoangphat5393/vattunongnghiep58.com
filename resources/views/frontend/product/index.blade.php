@extends('frontend.layouts.master')

@section('seo')
    @include('frontend.layouts.seo', $seo ?? [])
@endsection

@section('content')
    <main id="main">
        @include('frontend.includes.menu')

        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <a href="{{ route('index') }}" class="hover:text-leaf-600">Trang chủ</a>
                <span>/</span>
                <span class="font-bold text-leaf-700">Danh sách sản phẩm</span>
            </div>
        </div>

        <section class="container mx-auto flex flex-col gap-8 px-4 py-8 md:flex-row">
            <aside class="md:w-1/4">
                @include('frontend.product.includes.sidebar-categories')
            </aside>

            <div class="md:w-3/4">
                <div class="mb-6 flex items-center justify-between">
                    <span class="text-gray-500">Danh sách sản phẩm</span>
                </div>

                @empty(!$categories)
                    <div class="space-y-10">
                        @foreach ($categories as $item)
                            @empty(!$item['products'])
                                <div>
                                    <h2 class="mb-4 text-xl font-bold text-gray-900">{{ $item['name'] }}</h2>
                                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                                        @foreach ($item['products'] as $item2)
                                            <div class="group overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition duration-300 hover:shadow-xl">
                                                <div class="relative h-64 overflow-hidden">
                                                    <a href="{{ route('product.detail', [$item2['slug'], $item2['id']]) }}" class="block h-full w-full">
                                                        <img src="{{ get_image($item2['image']) }}" alt="{{ $item2['name'] }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-110" loading="lazy" />
                                                    </a>
                                                </div>
                                                <div class="p-4">
                                                    <h3 class="mb-2 line-clamp-2 text-lg font-bold text-gray-900 transition group-hover:text-leaf-600">
                                                        <a href="{{ route('product.detail', [$item2['slug'], $item2['id']]) }}">
                                                            {{ $item2['name'] }}
                                                        </a>
                                                    </h3>
                                                    <div class="flex items-center justify-between">
                                                        @if ($item2['has_price'])
                                                            <span class="text-xl font-extrabold text-leaf-600">
                                                                {{ number_format($item2['price'], 0, ',', '.') }} đ
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
                                </div>
                            @endempty
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">Hiện chưa có sản phẩm.</p>
                @endempty
            </div>
        </section>
    </main>
@endsection

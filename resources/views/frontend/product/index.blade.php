@extends('frontend.layouts.master')

@section('seo')
    @include('frontend.layouts.seo', $seo ?? [])
@endsection

@section('content')
    <main id="main">
        @include('frontend.includes.menu')

        <div class="container mx-auto px-4 py-4">
            <div class="text-sm text-gray-500 flex items-center gap-2">
                <a href="{{ route('index') }}" class="hover:text-leaf-600">Trang chủ</a>
                <span>/</span>
                <span class="text-leaf-700 font-bold">Danh sách sản phẩm</span>
            </div>
        </div>

        <section class="container mx-auto px-4 py-8 flex flex-col md:flex-row gap-8">
            <aside class="md:w-1/4">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-leaf-100">
                    <h3 class="font-bold text-lg mb-4 text-gray-900 border-b pb-2">Danh mục sản phẩm</h3>
                    <ul class="space-y-3 mb-8">
                        <li>
                            <a href="{{ route('product') }}" class="text-leaf-600 font-bold flex items-center gap-2">
                                <span class="w-2 h-2 bg-leaf-500 rounded-full"></span>
                                Tất cả sản phẩm
                            </a>
                        </li>
                        @empty(!$categories)
                            @foreach ($categories as $item)
                                <li>
                                    <a href="{{ route('product', ['category' => $item['slug'] ?? $item['id']]) }}" class="text-gray-600 hover:text-leaf-600 transition flex items-center gap-2">
                                        <span class="w-2 h-2 bg-gray-300 rounded-full"></span>
                                        {{ $item['name'] }}
                                    </a>
                                </li>
                            @endforeach
                        @endempty
                    </ul>
                </div>
            </aside>

            <div class="md:w-3/4">
                <div class="flex justify-between items-center mb-6">
                    <span class="text-gray-500">Danh sách sản phẩm</span>
                </div>

                @empty(!$categories)
                    <div class="space-y-10">
                        @foreach ($categories as $item)
                            @empty(!$item['products'])
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900 mb-4">{{ $item['name'] }}</h2>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                        @foreach ($item['products'] as $item2)
                                            <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition duration-300 group overflow-hidden border border-gray-100">
                                                <div class="relative h-64 overflow-hidden">
                                                    <a href="{{ route('product.detail', [$item2['slug'], $item2['id']]) }}" class="block w-full h-full">
                                                        <img src="{{ get_image($item2['image']) }}" alt="{{ $item2['name'] }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                                    </a>
                                                </div>
                                                <div class="p-4">
                                                    <h3 class="font-bold text-gray-900 text-lg mb-2 group-hover:text-leaf-600 transition line-clamp-2">
                                                        <a href="{{ route('product.detail', [$item2['slug'], $item2['id']]) }}">
                                                            {{ $item2['name'] }}
                                                        </a>
                                                    </h3>
                                                    <div class="flex justify-between items-center">
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

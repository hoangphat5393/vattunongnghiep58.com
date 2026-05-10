@extends('frontend.layouts.master')

@section('seo')
    @include('frontend.layouts.seo', $seo ?? [])
@endsection

@section('content')
    <div id="main">
        @include('frontend.includes.menu')

        <section class="relative py-12 md:py-20 overflow-hidden">
            <div class="blob bg-green-300 w-96 h-96 rounded-full top-0 left-0 -translate-x-1/2 -translate-y-1/2"></div>
            <div class="blob bg-yellow-200 w-80 h-80 rounded-full bottom-0 right-0 translate-x-1/3 translate-y-1/3"></div>

            <div class="container mx-auto px-4 flex flex-col-reverse md:flex-row items-center gap-12 relative z-10">
                <div class="md:w-1/2 text-center md:text-left">
                    <h1 class="text-4xl md:text-6xl font-extrabold text-gray-900 mb-6 leading-tight">
                        Mang màu xanh <br />
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-leaf-600 to-yellow-500">đến ngôi
                            nhà bạn</span>
                    </h1>
                    <p class="text-lg text-gray-600 mb-8">
                        Chuyên cung cấp các loại hạt giống F1 chất lượng cao, tỉ lệ nảy mầm >95%. Tư vấn kỹ thuật trồng
                        trọt miễn phí trọn đời.
                    </p>
                    <div class="flex flex-col md:flex-row gap-4 justify-center md:justify-start">
                        <a href="{{ route('product') }}" class="px-8 py-4 bg-gradient-to-r from-leaf-500 to-leaf-600 text-white font-bold rounded-full shadow-lg shadow-leaf-500/30 hover:shadow-xl hover:-translate-y-1 transition transform">
                            Khám phá ngay
                        </a>
                        <a href="#experience" class="px-8 py-4 bg-white text-leaf-700 font-bold rounded-full shadow-md hover:bg-gray-50 transition flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Xem video hướng dẫn
                        </a>
                    </div>
                </div>
                <div class="md:w-1/2">
                    <div class="relative">
                        <div class="absolute inset-0 bg-leaf-200 rounded-full transform rotate-6 scale-95 opacity-50">
                        </div>
                        <img src="{{ asset('upload/images/bang_hieu.jpg') }}" alt="Vườn rau" class="relative rounded-[2rem] shadow-2xl border-4 border-white object-cover h-[30rem] w-full transform -rotate-3 hover:rotate-0 transition duration-500" />
                        <div class="absolute -bottom-6 -left-6 bg-white p-4 rounded-2xl shadow-xl animate-bounce">
                            <div class="flex items-center gap-3">
                                <div class="bg-yellow-100 p-2 rounded-full text-yellow-600">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">Top 1</p>
                                    <p class="text-xs text-gray-500">Bán chạy nhất khu vực</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Danh mục hot: tên + ảnh từ admin (cùng nguồn với khối sản phẩm bên dưới) --}}
        @if (!empty($home_categories) && count($home_categories))
            <section class="py-12 md:py-16">
                <div class="mx-auto max-w-7xl px-4">
                    <div class="mb-10 text-center md:mb-12">
                        <h2 class="text-2xl font-extrabold tracking-tight text-leaf-900 md:text-3xl">Danh mục phổ biến</h2>
                        <p class="mt-2 text-sm text-gray-600 md:text-base">Chuyên mục đang được ghim nổi bật (hot)</p>
                    </div>
                    <div class="flex flex-wrap justify-center gap-6 sm:gap-8 md:gap-10">
                        @foreach ($home_categories as $hotCat)
                            <a href="{{ route('product.category', $hotCat['slug'] ?? $hotCat['id']) }}" class="group flex max-w-[140px] flex-col items-center sm:max-w-none">
                                <div class="mb-4 flex h-28 w-28 items-center justify-center overflow-hidden rounded-full border-2 border-leaf-100 bg-leaf-50 transition duration-300 group-hover:border-leaf-500 group-hover:shadow-md md:h-32 md:w-32">
                                    <img src="{{ get_image($hotCat['image'] ?? '') }}" alt="{{ $hotCat['name'] }}" class="h-full w-full object-cover opacity-90 transition group-hover:scale-105 group-hover:opacity-100" loading="lazy" width="128" height="128" />
                                </div>
                                <span class="text-center text-sm font-bold text-gray-800 transition group-hover:text-leaf-700 md:text-base">
                                    {{ $hotCat['name'] }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        @empty(!$home_categories)
            <section class="py-16 bg-white/50 backdrop-blur-sm rounded-t-[3rem]">
                <div class="container mx-auto px-4">
                    <div class="text-center mb-12">
                        <span class="text-leaf-600 font-bold uppercase tracking-widest text-sm">Sản phẩm mới về</span>
                        <h2 class="text-3xl font-extrabold text-gray-900 mt-2">Hạt Giống Chất Lượng</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        @foreach ($home_categories as $item)
                            @empty(!$item['products'])
                                @foreach ($item['products'] as $item2)
                                    <div class="bg-white rounded-3xl p-4 shadow-lg hover:shadow-2xl hover:-translate-y-2 transition duration-300 border border-gray-100 relative group">
                                        <div class="h-64 rounded-2xl overflow-hidden mb-4 relative">
                                            <a href="{{ route('product.detail', [$item2['slug'], $item2['id']]) }}" class="block w-full h-full">
                                                <img src="{{ get_image($item2['image']) }}" alt="{{ $item2['name'] }}" class="w-full h-full object-cover" />
                                            </a>
                                        </div>
                                        <h3 class="font-bold text-lg text-gray-800 mb-1 group-hover:text-leaf-600 transition line-clamp-2">
                                            <a href="{{ route('product.detail', [$item2['slug'], $item2['id']]) }}">
                                                {{ $item2['name'] }}
                                            </a>
                                        </h3>
                                        <div class="flex justify-between items-center mt-3">
                                            @if ($item2['has_price'])
                                                <span class="text-xl font-extrabold text-leaf-700">
                                                    {{ number_format($item2['price'], 0, ',', '.') }} đ
                                                </span>
                                            @else
                                                <span class="text-sm font-semibold text-leaf-700">
                                                    <a href="tel:{{ setting_option('phone') }}">Liên hệ</a>
                                                </span>
                                            @endif
                                            <a href="{{ route('product.detail', [$item2['slug'], $item2['id']]) }}" class="bg-leaf-100 p-2 rounded-full text-leaf-700 hover:bg-leaf-500 hover:text-white transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            @endempty
                        @endforeach
                    </div>

                    <div class="text-center mt-12">
                        <a href="{{ route('product') }}" class="inline-block px-8 py-3 border-2 border-leaf-500 text-leaf-600 font-bold rounded-full hover:bg-leaf-500 hover:text-white transition">
                            Xem tất cả sản phẩm
                        </a>
                    </div>
                </div>
            </section>
        @endempty

        @empty(!$home_news)
            <section id="experience" class="py-16">
                <div class="container mx-auto px-4">
                    <div class="flex flex-col items-center mb-12">
                        <span class="text-leaf-600 font-bold uppercase tracking-widest text-sm">Góc Chia Sẻ</span>
                        <h2 class="text-3xl font-extrabold text-gray-900 mt-2">Kinh Nghiệm Nhà Nông</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach ($home_news as $index => $item)
                            @php
                                $categoryName = $index === 0 ? 'Kỹ thuật' : 'Mẹo vặt';
                                $badgeColor = $index % 2 === 0 ? 'bg-leaf-500' : 'bg-yellow-500';
                            @endphp
                            <div class="group relative rounded-3xl overflow-hidden h-80 shadow-lg">
                                <img src="{{ get_image($item['image']) }}" alt="{{ $item['title'] }}" class="absolute inset-0 w-full h-full object-cover transition duration-700 group-hover:scale-110" />
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent">
                                </div>
                                <div class="absolute bottom-0 left-0 p-8 text-white">
                                    <span class="{{ $badgeColor }} text-xs font-bold px-3 py-1 rounded-full mb-3 inline-block">
                                        {{ $categoryName }}
                                    </span>
                                    <h3 class="text-2xl font-bold mb-2 leading-tight group-hover:text-leaf-300 transition line-clamp-2 min-h-[3.75rem]">
                                        {{ html_entity_decode($item['title']) }}
                                    </h3>
                                    <p class="text-gray-300 mb-4 line-clamp-1" style="display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;">
                                        {!! \Illuminate\Support\Str::limit(strip_tags(html_entity_decode($item['description'])), 100) !!}
                                    </p>
                                    <a href="{{ route('news.detail', [$item['slug'], $item['id']]) }}" class="inline-flex items-center gap-2 font-bold text-leaf-400 hover:text-white transition">
                                        Đọc thêm
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endempty
    </div>
@endsection

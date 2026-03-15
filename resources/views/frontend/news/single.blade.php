@extends('frontend.layouts.master')

@section('seo')
    @include('frontend.layouts.seo', $seo ?? [])
@endsection

@php
    use Carbon\Carbon;
    Carbon::setLocale('vi');
    $cdt = new Carbon($news->created_at);
@endphp

@section('content')
    {{-- Breadcrumb --}}
    <div class="bg-leaf-50 font-sans text-gray-800 flex flex-col">
        <div class="container mx-auto px-4 py-4">
            <div class="text-sm text-gray-500 flex items-center gap-2">
                <a href="{{ route('index') }}" class="hover:text-leaf-600">Trang chủ</a>
                <span>/</span>
                <a href="{{ route('news') }}" class="hover:text-leaf-600">Góc chia sẻ</a>
                <span>/</span>
                @if ($news->categories->isNotEmpty())
                    <a href="{{ route('news.category', $news->categories->first()->slug) }}" class="hover:text-leaf-600">{{ $news->categories->first()->name }}</a>
                    <span>/</span>
                @endif
                <span class="text-leaf-700 font-bold line-clamp-1">{{ $news->title }}</span>
            </div>
        </div>

        <section class="py-8">
            <div class="container mx-auto px-4">
                <div class="flex flex-col lg:flex-row gap-12">
                    {{-- Main Content --}}
                    <div class="lg:w-2/3">
                        <div class="bg-white rounded-3xl p-8 shadow-xl border border-gray-100">
                            <div class="mb-6">
                                @if ($news->categories->isNotEmpty())
                                    <span class="bg-leaf-100 text-leaf-700 font-bold px-3 py-1 rounded-full text-sm">
                                        {{ $news->categories->first()->name }}
                                    </span>
                                @endif
                                <span class="text-gray-400 text-sm ml-3">
                                    {{ $cdt->format('d \T\h\á\n\g m, Y') }}
                                </span>
                            </div>

                            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-6 leading-tight">
                                {{ $news->title }}
                            </h1>

                            {{-- Author Info --}}
                            @if ($news->user)
                                <div class="flex items-center gap-4 mb-8 border-b border-gray-100 pb-8">
                                    <div class="w-12 h-12 rounded-full bg-gray-200 overflow-hidden">
                                        <img src="{{ get_image($news->user->image) }}" alt="{{ $news->user->name }}" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($news->user->name) }}&background=random'" />
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800">{{ $news->user->name }}</p>
                                        {{-- <p class="text-sm text-gray-500">Kỹ sư nông nghiệp</p> --}}
                                    </div>
                                </div>
                            @endif

                            <div class="prose prose-lg prose-green max-w-none text-gray-600">
                                {!! htmlspecialchars_decode($news->content) !!}
                            </div>

                            {{-- Tags & Share --}}
                            <div class="mt-12 pt-8 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-6">
                                @if ($news->seo_keyword)
                                    <div class="flex gap-2 flex-wrap">
                                        <span class="font-bold text-gray-700">Tags:</span>
                                        @foreach (explode(',', $news->seo_keyword) as $tag)
                                            <a href="#" class="text-leaf-600 hover:underline">{{ trim($tag) }}</a>{{ !$loop->last ? ',' : '' }}
                                        @endforeach
                                    </div>
                                @endif
                                <div class="flex items-center gap-3">
                                    <span class="font-bold text-gray-700">Chia sẻ:</span>
                                    <button class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center hover:opacity-90">
                                        <span class="font-bold">f</span>
                                    </button>
                                    <button class="w-10 h-10 rounded-full bg-blue-400 text-white flex items-center justify-center hover:opacity-90">
                                        <span class="font-bold">t</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Sidebar --}}
                    <aside class="lg:w-1/3 space-y-8">
                        {{-- Search --}}
                        <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100">
                            <h3 class="font-bold text-lg text-gray-800 mb-4 border-l-4 border-leaf-500 pl-3">Tìm kiếm</h3>
                            <form action="{{ route('search') }}" method="GET" class="relative">
                                <input type="text" name="keyword" placeholder="Tìm bài viết..." class="w-full pl-4 pr-10 py-3 rounded-xl border border-gray-200 focus:border-leaf-500 focus:outline-none bg-gray-50" />
                                <button type="submit" class="absolute right-3 top-3.5 text-gray-400 hover:text-leaf-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>

                        {{-- Categories --}}
                        @if (isset($categories) && $categories->isNotEmpty())
                            <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100">
                                <h3 class="font-bold text-lg text-gray-800 mb-4 border-l-4 border-leaf-500 pl-3">Chuyên mục</h3>
                                <ul class="space-y-3">
                                    @foreach ($categories as $cat)
                                        <li>
                                            <a href="{{ route('news.category', $cat->slug) }}" class="flex justify-between items-center text-gray-600 hover:text-leaf-600 group transition">
                                                <span>{{ $cat->name }}</span>
                                                <span class="bg-gray-100 text-gray-400 text-xs px-2 py-1 rounded-full group-hover:bg-leaf-100 group-hover:text-leaf-600 transition">
                                                    {{ $cat->posts_count }}
                                                </span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Latest Posts --}}
                        @if (isset($latest_news) && $latest_news->isNotEmpty())
                            <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100">
                                <h3 class="font-bold text-lg text-gray-800 mb-4 border-l-4 border-leaf-500 pl-3">Bài viết mới</h3>
                                <div class="space-y-4">
                                    @foreach ($latest_news as $item)
                                        <a href="{{ route('news.detail', ['slug' => $item->slug, 'id' => $item->id]) }}" class="flex gap-4 group">
                                            <div class="w-20 h-20 rounded-xl overflow-hidden flex-shrink-0">
                                                <img src="{{ get_image($item->image) }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500" loading="lazy" />
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-gray-800 text-sm leading-tight mb-1 group-hover:text-leaf-600 transition line-clamp-2">
                                                    {{ $item->title }}
                                                </h4>
                                                <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($item->created_at)->format('d \T\h\á\n\g m, Y') }}</span>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Ad Banner (Hardcoded or Dynamic if settings allow) --}}
                        {{-- <div class="rounded-2xl overflow-hidden relative h-64 shadow-md group cursor-pointer">
                            <img src="{{ asset('image/Bắp Cải.jpg') }}" alt="Ad" class="absolute inset-0 w-full h-full object-cover transition duration-700 group-hover:scale-110" />
                            <div class="absolute inset-0 bg-gradient-to-t from-leaf-900/80 to-transparent flex flex-col justify-end p-6 text-center">
                                <p class="text-white font-bold text-xl mb-2">Combo Hạt Giống</p>
                                <p class="text-leaf-200 text-sm mb-4">Mua 5 tặng 1 + Freeship</p>
                                <span class="bg-white text-leaf-700 font-bold py-2 px-4 rounded-full text-sm hover:bg-leaf-50 transition">Mua ngay</span>
                            </div>
                        </div> --}}
                    </aside>
                </div>

                {{-- Related Posts --}}
                @if (isset($related_news) && $related_news->isNotEmpty())
                    <div class="mt-12">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Bài viết liên quan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach ($related_news as $item)
                                <a href="{{ route('news.detail', ['slug' => $item->slug, 'id' => $item->id]) }}" class="group block bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-md transition">
                                    <div class="aspect-w-16 aspect-h-9 h-48 overflow-hidden">
                                        <img src="{{ get_image($item->image) }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500" loading="lazy" />
                                    </div>
                                    <div class="p-4">
                                        <div class="flex items-center gap-2 mb-2 text-xs text-gray-500">
                                            @if ($item->categories->isNotEmpty())
                                                <span class="bg-leaf-100 text-leaf-700 px-2 py-0.5 rounded-full font-bold">
                                                    {{ $item->categories->first()->name }}
                                                </span>
                                                <span>•</span>
                                            @endif
                                            <span>{{ \Carbon\Carbon::parse($item->created_at)->format('d \T\h\á\n\g m, Y') }}</span>
                                        </div>
                                        <h4 class="font-bold text-gray-800 group-hover:text-leaf-600 transition line-clamp-2">
                                            {{ $item->title }}
                                        </h4>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
    </div>
    </section>
    </div>
@endsection

@push('scripts')
@endpush

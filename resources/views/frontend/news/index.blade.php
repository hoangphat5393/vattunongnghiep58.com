@extends('frontend.layouts.master')

@section('seo')
    @include('frontend.layouts.seo', $seo ?? [])
@endsection

@section('body-class', 'blog')

@php
    use Carbon\Carbon;
    Carbon::setLocale('vi');
@endphp

@section('content')
    @include('frontend.includes.menu')

    <div class="container mx-auto px-4 py-4">
        <div class="text-sm text-gray-500 flex items-center gap-2">
            <a href="{{ route('index') }}" class="hover:text-leaf-600">Trang chủ</a>
            <span>/</span>
            <span class="text-leaf-700 font-bold">Danh sách bài viết</span>
        </div>
    </div>

    <main class="flex-grow container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row gap-8">
            <aside class="md:w-1/4 order-2 md:order-1">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-leaf-100 sticky top-24 mb-8">
                    <h3 class="font-bold text-lg mb-4 text-gray-900 border-b pb-2">Danh mục</h3>
                    <div class="space-y-3 mb-8">
                        @include('frontend.includes.left_sidebar')
                    </div>
                </div>
            </aside>

            <div class="md:w-3/4 order-1 md:order-2">
                <div class="mb-6">
                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">Tin tức nông nghiệp</h1>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @foreach ($news as $item)
                        @php $cdt = new Carbon($item->created_at); @endphp
                        <article
                            class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition duration-300 flex flex-col h-full border border-gray-100">
                            <div class="h-52 overflow-hidden relative">
                                <a href="{{ route('news.detail', [$item->slug, $item->id]) }}"
                                    title="{{ $item->title }}">
                                    <img src="{{ get_image($item->image) }}" alt="{{ $item->title }}"
                                        class="w-full h-full object-cover hover:scale-110 transition duration-500">
                                </a>
                            </div>
                            <div class="p-6 flex flex-col flex-grow">
                                <h3
                                    class="font-bold text-xl text-gray-900 mb-3 hover:text-leaf-600 transition line-clamp-2">
                                    <a href="{{ route('news.detail', [$item->slug, $item->id]) }}"
                                        title="{{ $item->title }}">
                                        {{ $item->title }}
                                    </a>
                                </h3>
                                <p class="text-gray-600 text-sm mb-4 line-clamp-3 flex-grow">
                                    {!! htmlspecialchars_decode($item->description) !!}
                                </p>
                                <div class="flex justify-between items-center mt-auto border-t pt-4">
                                    <span class="text-xs text-gray-400">
                                        {{ $cdt->format('d-m-Y') }}
                                    </span>
                                    <a href="{{ route('news.detail', [$item->slug, $item->id]) }}"
                                        class="text-leaf-600 font-bold text-sm hover:underline">Đọc tiếp →</a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-12 flex justify-center">
                    {{ $news->links('frontend.pagination.custom') }}
                </div>
            </div>
        </div>
    </main>
@endsection

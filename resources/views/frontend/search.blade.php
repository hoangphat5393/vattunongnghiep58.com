@extends('frontend.layouts.master')

@section('seo')
    <title>Search</title>
@endsection

@php
    use Carbon\Carbon;
    Carbon::setLocale('vi');
@endphp

@section('content')
    @include('frontend.includes.menu')

    <div class="bg-leaf-100 py-4">
        <div class="container mx-auto px-4">
            <nav class="flex text-sm text-gray-600 font-medium">
                <a href="{{ route('index') }}" class="hover:text-leaf-600">Trang chủ</a>
                <span class="mx-2">/</span>
                <span class="text-leaf-700">Tìm kiếm</span>
            </nav>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8 flex-grow">
        <div class="flex flex-col md:flex-row gap-8">
            <div class="w-full md:w-1/4">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 sticky top-24">
                    <h3 class="font-bold text-lg mb-4 text-leaf-800 border-b pb-2">Bộ lọc tìm kiếm</h3>
                    <div class="space-y-3">
                        @include('frontend.includes.left_sidebar')
                    </div>
                </div>
            </div>

            <div class="w-full md:w-3/4">
                <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h1 class="text-3xl font-extrabold text-gray-900 mb-1">@lang('Search')</h1>
                        <p class="text-gray-500">
                            Từ tìm kiếm:
                            <span class="font-bold text-leaf-600">{{ $keyword ?? '' }}</span>
                        </p>
                    </div>
                </div>

                @if ($product->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($product as $item)
                            <div
                                class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition duration-300 group overflow-hidden border border-gray-100">
                                <div class="relative h-64 overflow-hidden">
                                    <a href="{{ route('product.detail', [$item->slug, $item->id]) }}"
                                        title="{{ $item->name }}" class="block w-full h-full">
                                        <img src="{{ get_image($item->image) }}" alt="{{ $item->name }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                    </a>
                                </div>
                                <div class="p-4">
                                    <h3
                                        class="font-bold text-lg mb-1 group-hover:text-leaf-600 transition line-clamp-2">
                                        <a href="{{ route('product.detail', [$item->slug, $item->id]) }}">
                                            {{ $item->name }}
                                        </a>
                                    </h3>
                                    <div class="mt-2">
                                        @if ($item->price)
                                            <span class="text-xl font-bold text-leaf-700">
                                                {{ number_format($item->price, 0, ',', '.') }} đ
                                            </span>
                                        @else
                                            <span class="text-sm font-semibold text-leaf-700">
                                                <a href="tel:{{ setting_option('phone') }}">Liên hệ</a>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-8 flex justify-center">
                        {!! $product->appends(request()->input())->links('frontend.pagination.custom') !!}
                    </div>
                @else
                    <p class="text-gray-500">Không có sản phẩm nào.</p>
                @endif
            </div>
        </div>
    </div>
@endsection

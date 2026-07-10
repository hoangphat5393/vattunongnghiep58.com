@extends('frontend.layouts.master')

@section('seo')
    @include('frontend.layouts.seo', [
        'seo_title' => '404 — Không tìm thấy trang | ' . setting_option('webtitle'),
        'seo_description' => 'Trang không tồn tại hoặc đã được di chuyển.',
    ])
@endsection

@section('content')
    @include('frontend.includes.menu')

    <div class="bg-leaf-50 grow">
        <div class="container mx-auto px-4 py-10 md:py-16 lg:py-24">
            <div class="mx-auto max-w-xl text-center">
                <div class="relative mb-8 inline-block">
                    <div class="absolute inset-0 rounded-full bg-leaf-200/40 blur-2xl scale-150"></div>
                    <div class="relative flex h-28 w-28 items-center justify-center rounded-full border-4 border-leaf-200 bg-white shadow-lg mx-auto">
                        <svg class="h-14 w-14 text-leaf-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>

                <p class="text-sm font-bold uppercase tracking-widest text-leaf-700/80 mb-2">Lỗi 404</p>
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 tracking-tight mb-2">Không tìm thấy trang</h1>
                <p class="text-gray-600 text-base md:text-lg mb-8 leading-relaxed">
                    Đường dẫn có thể đã đổi hoặc bạn nhập sai URL. Hãy quay về trang chủ hoặc tìm sản phẩm bạn cần.
                </p>

                {{-- Luôn 1 hàng, căn giữa; không dùng flex-col / w-full trên mobile --}}
                <div class="flex w-full flex-row flex-nowrap items-center justify-center gap-3 sm:gap-4">
                    <a href="{{ route('index') }}" class="inline-flex shrink-0 items-center justify-center gap-2.5 whitespace-nowrap rounded-xl bg-leaf-600 px-5 py-4 text-base font-bold text-white shadow-md shadow-leaf-500/25 hover:bg-leaf-700 transition sm:px-7 sm:py-4 sm:text-lg">
                        <svg class="h-6 w-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Về trang chủ
                    </a>
                    <a href="{{ route('page', 'about') }}" class="inline-flex shrink-0 items-center justify-center gap-2.5 whitespace-nowrap rounded-xl border-2 border-leaf-600 bg-white px-5 py-4 text-base font-bold text-leaf-800 hover:bg-leaf-50 transition sm:px-7 sm:py-4 sm:text-lg">
                        <svg class="h-6 w-6 shrink-0 text-leaf-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Giới thiệu
                    </a>
                </div>

                <div class="mt-8 sm:mt-10 flex flex-wrap items-center justify-center gap-3 sm:gap-4">
                    <a href="{{ route('product') }}" class="inline-flex min-h-11 shrink-0 items-center justify-center gap-2.5 rounded-xl border border-leaf-200 bg-white/90 px-5 py-3 text-base font-semibold text-leaf-800 shadow-sm transition hover:border-leaf-300 hover:bg-leaf-50 hover:shadow-md sm:min-h-12 sm:px-6 sm:py-3.5">
                        <svg class="h-5 w-5 shrink-0 text-leaf-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        Sản phẩm
                    </a>
                    <a href="{{ route('news') }}" class="inline-flex min-h-11 shrink-0 items-center justify-center gap-2.5 rounded-xl border border-leaf-200 bg-white/90 px-5 py-3 text-base font-semibold text-leaf-800 shadow-sm transition hover:border-leaf-300 hover:bg-leaf-50 hover:shadow-md sm:min-h-12 sm:px-6 sm:py-3.5">
                        <svg class="h-5 w-5 shrink-0 text-leaf-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                        Tin tức
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

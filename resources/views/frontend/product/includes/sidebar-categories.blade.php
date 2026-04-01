{{-- Cuộn nội bộ nếu danh mục dài; max-h dùng class trong app.css (cùng --site-header-sticky-height) --}}
<div class="md:max-h-product-sidebar-scroll md:overflow-y-auto md:overflow-x-hidden md:pr-0.5 [scrollbar-gutter:stable]">
    <div class="rounded-2xl border border-leaf-100 bg-white p-5 shadow-[0_4px_20px_-6px_rgba(34,84,61,0.07)] ring-1 ring-gray-900/[0.06]">
        <h3 class="mb-4 flex items-center gap-2 border-b border-leaf-100 pb-3 text-base font-bold text-leaf-800">
            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-leaf-100 text-leaf-600">
                <i class="fa-solid fa-seedling text-sm" aria-hidden="true"></i>
            </span>
            Danh mục sản phẩm
        </h3>
        @include('frontend.product.includes.categories-nav')
    </div>
</div>

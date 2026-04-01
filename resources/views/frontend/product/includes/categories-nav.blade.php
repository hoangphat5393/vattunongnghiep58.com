@php
    $categoriesNav = \App\Models\Frontend\Category::query()
        ->where(['parent' => 0, 'status' => 1])
        ->with([
            'children' => function ($q) {
                $q->orderBy('sort', 'asc');
            },
        ])
        ->orderBy('sort', 'asc')
        ->get();
    $currentSlug = request()->route('slug');
@endphp

<nav class="text-left" aria-label="Danh mục sản phẩm">
    <ul class="divide-y divide-leaf-100/90">
        <li>
            <a href="{{ route('product') }}" @class([
                'block py-3 text-[15px] transition',
                'font-semibold text-leaf-700' =>
                    request()->routeIs('product') && !$currentSlug,
                'text-gray-700 hover:text-leaf-700' => !(
                    request()->routeIs('product') && !$currentSlug
                ),
            ])>
                Tất cả sản phẩm
            </a>
        </li>
        @foreach ($categoriesNav as $item)
            @php
                $isParentActive = request()->routeIs('product.category') && $currentSlug === $item->slug;
                $hasChildren = $item->children->isNotEmpty();
            @endphp
            <li>
                <a href="{{ route('product.category', $item->slug) }}" title="{{ $item->name }}" @class([
                    'block py-3 text-[15px] transition',
                    'font-semibold text-leaf-700' => $isParentActive,
                    'text-gray-800 hover:text-leaf-700' => !$isParentActive,
                ])>
                    {{ $item->name }}
                </a>
                @if ($hasChildren)
                    {{-- Cấp 2: một vạch dọc mảnh + dòng chữ, không khối bọc --}}
                    <ul class="mb-2 ml-1 space-y-0 border-l border-leaf-200/80 pl-3">
                        @foreach ($item->children as $item2)
                            @php
                                $isChildActive = request()->routeIs('product.category') && $currentSlug === $item2->slug;
                            @endphp
                            <li>
                                <a href="{{ route('product.category', $item2->slug) }}" title="{{ $item2->name }}" @if ($isChildActive) aria-current="page" @endif @class([
                                    'block rounded-r-md py-2 pr-1 text-sm leading-snug transition',
                                    'bg-leaf-50/90 font-semibold text-leaf-900' => $isChildActive,
                                    'text-gray-600 hover:bg-leaf-50/50 hover:text-leaf-800' => !$isChildActive,
                                ])>
                                    {{ $item2->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>
</nav>

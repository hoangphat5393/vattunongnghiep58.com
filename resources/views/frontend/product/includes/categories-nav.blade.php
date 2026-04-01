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

<ul class="mb-0 space-y-3 text-left">
    <li>
        <a href="{{ route('product') }}" @class([
            'flex items-start gap-2 transition',
            'font-bold text-leaf-600' => request()->routeIs('product') && !$currentSlug,
            'text-gray-600 hover:text-leaf-600' => !(
                request()->routeIs('product') && !$currentSlug
            ),
        ])>
            <span @class([
                'mt-1.5 h-2 w-2 shrink-0 rounded-full',
                'bg-leaf-500' => request()->routeIs('product') && !$currentSlug,
                'bg-gray-300' => !(request()->routeIs('product') && !$currentSlug),
            ])></span>
            <span>Tất cả sản phẩm</span>
        </a>
    </li>
    @foreach ($categoriesNav as $item)
        @php
            $isParentActive = request()->routeIs('product.category') && $currentSlug === $item->slug;
        @endphp
        <li>
            <a href="{{ route('product.category', $item->slug) }}" title="{{ $item->name }}" @class([
                'flex items-start gap-2 transition',
                'font-bold text-leaf-600' => $isParentActive,
                'text-gray-600 hover:text-leaf-600' => !$isParentActive,
            ])>
                <span @class([
                    'mt-1.5 h-2 w-2 shrink-0 rounded-full',
                    'bg-leaf-500' => $isParentActive,
                    'bg-gray-300' => !$isParentActive,
                ])></span>
                <span class="leading-snug">{{ $item->name }}</span>
            </a>
            @if ($item->children->isNotEmpty())
                <ul class="mt-2 ml-2 space-y-2 border-l border-leaf-100 pl-3">
                    @foreach ($item->children as $item2)
                        @php
                            $isChildActive = request()->routeIs('product.category') && $currentSlug === $item2->slug;
                        @endphp
                        <li>
                            <a href="{{ route('product.category', $item2->slug) }}" title="{{ $item2->name }}" @class([
                                'flex items-start gap-2 text-sm transition',
                                'font-bold text-leaf-600' => $isChildActive,
                                'text-gray-600 hover:text-leaf-600' => !$isChildActive,
                            ])>
                                <span @class([
                                    'mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full',
                                    'bg-leaf-500' => $isChildActive,
                                    'bg-gray-300' => !$isChildActive,
                                ])></span>
                                <span class="leading-snug">{{ $item2->name }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </li>
    @endforeach
</ul>

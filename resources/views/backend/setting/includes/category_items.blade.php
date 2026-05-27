@php
    $parent_id = $parent_id ?? 0;
    $space = $space ?? '';
    $childrenMap = $childrenMap ?? collect();
    $categories = $categories ?? $childrenMap->get($parent_id, collect());
    $isRootMenuSourceList = (int) $parent_id === 0 && $space === '';
    $depth = $space !== '' ? substr_count($space, '-----') : 0;
@endphp
@if (count($categories) > 0)
    @if ($isRootMenuSourceList)
        <div class="menu-source-tools">
            <input type="search" class="form-control form-control-sm menu-source-filter" placeholder="Tìm nhanh danh mục..." aria-label="Tìm nhanh danh mục">
        </div>
        <div class="menu-source-list">
    @endif
    @foreach ($categories as $category)
        <div class="form-group menu-source-item" style="--menu-source-depth: {{ $depth }};">
            <label for="category_{{ $category->id }}" class="menu-source-label" title="{{ $category->name }}">
                <input type="checkbox" class="category_item_input menu-source-checkbox" value="{{ $category->id }}" id="category_{{ $category->id }}">
                <span class="menu-source-title">{{ $category->name }}</span>
                <input type="hidden" class="item-name-{{ $category->id }}" value="{{ $category->name }}">
                <input type="hidden" class="item-slug-{{ $category->id }}" value="{{ $category->slug }}">
                @switch($type)
                    @case('post')
                        <input type="hidden" class="item-url-{{ $category->id }}" value="{{ route('news.category', $category->slug) }}">
                    @break

                    @default
                        <input type="hidden" class="item-url-{{ $category->id }}" value="{{ route('page', $category->slug) }}">
                @endswitch
                <input type="hidden" class="item-type-{{ $category->id }}" value="category">
            </label>
        </div>
        @php
            $children = $childrenMap->get($category->id, collect());
        @endphp
        @if ($children->isNotEmpty())
            @include('backend.setting.includes.category_items', [
                'parent_id' => $category->id,
                'categories' => $children,
                'childrenMap' => $childrenMap,
                'space' => $space . '-----',
                'type' => $type ?? null,
            ])
        @endif
    @endforeach
    @if ($isRootMenuSourceList)
        </div>
    @endif
@endif

@php
    $pages = \App\Models\Backend\Page::where('status', 1)
        // ->orderByDesc('id')
        ->orderBy('sort')
        ->get();
@endphp
@if (count($pages) > 0)
    <div class="menu-source-tools">
        <input type="search" class="form-control form-control-sm menu-source-filter" placeholder="Tìm nhanh trang..." aria-label="Tìm nhanh trang">
    </div>
    <div class="menu-source-list">
        @foreach ($pages as $item)
            <div class="form-group menu-source-item">
                <label for="page_{{ $item->id }}" class="menu-source-label" title="{{ $item->name }}">
                    <input type="checkbox" class="category_item_input menu-source-checkbox" value="{{ $item->id }}" id="page_{{ $item->id }}">
                    <span class="menu-source-title">{{ $item->name }}</span>
                    <input type="hidden" class="item-name-{{ $item->id }}" value="{{ $item->name }}">
                    <input type="hidden" class="item-slug-{{ $item->id }}" value="{{ $item->slug }}">
                    <input type="hidden" class="item-url-{{ $item->id }}" value="{{ route('page', $item->slug) }}">
                    <input type="hidden" class="item-id-{{ $item->id }}" value="{{ $item->id }}">
                    <input type="hidden" class="item-type-{{ $item->id }}" value="page">
                </label>
            </div>
        @endforeach
    </div>
@endif

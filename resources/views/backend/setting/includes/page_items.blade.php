@php
    $pages = \App\Models\Backend\Page::where('status', 1)
        // ->orderByDesc('id')
        ->orderBy('sort')
        ->get();
@endphp
@if (count($pages) > 0)
    @foreach ($pages as $item)
        <div class="form-group">
            <label for="page_{{ $item->id }}" class="">
                <input type="checkbox" class="category_item_input" value="{{ $item->id }}" id="page_{{ $item->id }}">
                {{ $item->name }}
                <input type="hidden" class="item-name-{{ $item->id }}" value="{{ $item->name }}">
                <input type="hidden" class="item-slug-{{ $item->id }}" value="{{ $item->slug }}">
                <input type="hidden" class="item-url-{{ $item->id }}" value="{{ route('page', $item->slug) }}">
                <input type="hidden" class="item-id-{{ $item->id }}" value="{{ $item->id }}">
                <input type="hidden" class="item-type-{{ $item->id }}" value="page">
            </label>
        </div>
    @endforeach
@endif

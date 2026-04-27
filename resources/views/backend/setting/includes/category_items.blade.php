@php
    $parent_id = $parent_id ?? 0;
    $space = $space ?? '';
    $childrenMap = $childrenMap ?? collect();
    $categories = $categories ?? $childrenMap->get($parent_id, collect());
@endphp
@if (count($categories) > 0)
    @foreach ($categories as $category)
        <div class="form-group">
            <label for="category_{{ $category->id }}" class="">
                <input type="checkbox" class="category_item_input" value="{{ $category->id }}" id="category_{{ $category->id }}">
                {{ $space ?? '' }} {{ $category->name }}
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
@endif

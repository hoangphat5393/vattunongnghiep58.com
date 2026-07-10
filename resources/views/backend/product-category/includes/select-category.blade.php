@php
    $data_type = $data_type ?? '';
    $parent = $parent ?? 0;
    $slit = $slit ?? '-----';
@endphp

@if ($data_type == '')
    <select class="form-select me-2" name="parent">
        <option value="0">== Không có ==</option>
        @php
            $rootCategories = isset($childrenMap) ? $childrenMap->get(0, collect()) : collect();
        @endphp
        @foreach ($rootCategories as $category)
            <option value="{{ $category->id }}" {{ $parent == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @php
                $children = isset($childrenMap) ? $childrenMap->get($category->id, collect()) : collect();
            @endphp
            @if ($children->isNotEmpty())
                @include('backend.product-category.includes.select-category', [
                    'data' => $children,
                    'data_type' => 'option',
                    'parent' => $parent,
                    'slit' => $slit,
                    'childrenMap' => $childrenMap ?? collect(),
                ])
            @endif
        @endforeach
    </select>
@else
    @foreach ($data as $item)
        <option value="{{ $item->id }}" {{ $parent == $item->id ? 'selected' : '' }}>{!! $slit !!} {{ $item->name }}</option>
        @php
            $children = isset($childrenMap) ? $childrenMap->get($item->id, collect()) : collect();
        @endphp
        @if ($children->isNotEmpty())
            @include('backend.product-category.includes.select-category', [
                'data' => $children,
                'data_type' => 'option',
                'parent' => $parent,
                'slit' => $slit . '-----',
                'childrenMap' => $childrenMap ?? collect(),
            ])
        @endif
    @endforeach
@endif

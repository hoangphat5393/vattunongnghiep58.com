@php
    $type = $type ?? '';
    $parent = $parent ?? 0;
@endphp

@if ($type == '')
    @php
        $attributes = \App\Model\Attribute::where('status', 1)->where('parent', 0)->get();

        // dd($attributes);

    @endphp
    <select class="custom-select me-2" name="parent">
        <option value="0">== Không có ==</option>
        @if ($attributes->count() > 0)
            @foreach ($attributes as $attribute)
                <option value="{{ $attribute->id }}" {{ $parent == $attribute->id ? 'selected' : '' }}>{{ $attribute->attribute_name }}</option>

                @if ($attribute->children)
                    @include('admin.attribute.includes.select-attribute', [
                        'data' => $attribute->children,
                        'type' => 'option',
                        'parent' => $parent,
                        'slit' => '&nbsp;&nbsp;&nbsp;&nbsp;',
                    ])
                @endif
            @endforeach
        @endif
    </select>
@else
    @foreach ($data as $item)
        <option value="{{ $item->id }}" {{ $parent == $item->id ? 'selected' : '' }}>{!! $slit !!}{{ $item->attribute_name }}</option>
        @if ($item->children)
            @include('admin.attribute.includes.select-attribute', [
                'data' => $item->children,
                'type' => 'option',
                'parent' => $parent,
                'slit' => $slit . '&nbsp;&nbsp;&nbsp;&nbsp;',
            ])
        @endif
    @endforeach
@endif

@php
    $data_type = $data_type ?? '';
    $parent = $parent ?? 0;
@endphp

@if ($data_type == '')
    @php
        // $categories = \App\Models\Category::where('status', 1)
        //     ->where('type', 'product')
        //     ->where('parent', 0)
        //     ->whereNot('id', $id)
        //     ->get();

        $db = \App\Models\Backend\Category::where('status', 1)->where('parent', 0);
        if (isset($id)) {
            $db->whereNot('id', $id);
        }
        $categories = $db->get();
    @endphp
    <select class="custom-select me-2" name="parent">
        <option value="0">== Không có ==</option>
        @if ($categories->count() > 0)
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ $parent == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @if ($category->children('product')->get())
                    @include('backend.product-category.includes.select-category', [
                        'data' => $category->children('product')->whereNot('id', $id)->get(),
                        'data_type' => 'option',
                        'parent' => $parent,
                        'slit' => '-----',
                    ])
                @endif
            @endforeach
        @endif
    </select>
@else
    @foreach ($data as $item)
        <option value="{{ $item->id }}" {{ $parent == $item->id ? 'selected' : '' }}>{!! $slit !!} {{ $item->name }}</option>
        @if ($item->children('product')->get())
            @include('backend.product-category.includes.select-category', [
                'data' => $item->children('product')->whereNot('id', $id)->get(),
                'data_type' => 'option',
                'parent' => $parent,
                'slit' => $slit . '-----',
            ])
        @endif
    @endforeach
@endif

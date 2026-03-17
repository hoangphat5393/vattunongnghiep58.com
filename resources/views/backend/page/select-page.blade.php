@php
    $type = $type ?? '';
    $parent = $parent ?? 0;
@endphp

@if ($type == '')
    @php
        $pages = \App\Models\Backend\Page::where('status', 1)->where('parent', 0)->get();
    @endphp
    @if ($pages->count() > 0)
        <select class="custom-select me-2" name="parent">
            <option value="0">== Không có ==</option>
            @foreach ($pages as $page)
                <option value="{{ $page->id }}" {{ $parent == $page->id ? 'selected' : '' }}>{{ $page->title_en }} | {{ $page->title }}</option>
                @if ($page->children)
                    @include('backend.page.includes.select-page', [
                        'data' => $page->children,
                        'type' => 'option',
                        'parent' => $parent,
                        'slit' => '&nbsp;&nbsp;&nbsp;&nbsp;',
                    ])
                @endif
            @endforeach

        </select>
    @endif
@else
    @foreach ($data as $item)
        <option value="{{ $item->id }}" {{ $parent == $item->id ? 'selected' : '' }}>{!! $slit !!}{{ $item->title }}</option>
        @if ($item->children)
            @include('backend.page.includes.select-page', [
                'data' => $item->children,
                'type' => 'option',
                'parent' => $parent,
                'slit' => $slit . '&nbsp;&nbsp;&nbsp;&nbsp;',
            ])
        @endif
    @endforeach
@endif

@if (count($categories) > 0)
    @foreach ($categories as $item)
        <tr class="tr-item item-level-{{ isset($level) ? $level : 0 }}">
            <td class="text-center">
                <div class="icheck-info d-inline">
                    <input type="checkbox" id="post-cat-{{ $item->id }}" name="seq_list[]" value="{{ $item->id }}">
                    <label for="post-cat-{{ $item->id }}"></label>
                </div>
            </td>
            <td class="text-center">
                <input type="text" id="sort-{{ $item->id }}" class="text-center form-control quick_change_value" data-id="{{ $item->id }}" data-model="{{ get_class($item) }}" value="{{ $item->sort }}" reload-on-change>
            </td>
            <td class="title">
                <a class="row-title" href="{{ route('admin.post-category.edit', [$item->id]) }}">
                    <div>
                        {{ str_repeat('-----', $level) }}
                        <b style="color: #056FAD;">{{ $item->name }}</b>
                    </div>
                </a>
            </td>
            <td class="text-center">
                @if ($item->image != null)
                    <img src="{{ get_image($item->image) }}" style="height: 70px" alt="">
                @endif
            </td>
            <td class="text-center">
                <input type="checkbox" id="hot-{{ $item->id }}" class="quick_change_value" @checked($item->hot == 1) value="1" value-off="0" data-id="{{ $item->id }}" data-model="{{ get_class($item) }}" data-toggle="toggle" data-on="Bán chạy" data-off="Không" data-onstyle="danger" data-offstyle="light">
                <p class="my-2">{{ $item->updated_at }}</p>
                <input type="checkbox" id="status-{{ $item->id }}" class="quick_change_value" @checked($item->status == 1) value="1" value-off="0" data-id="{{ $item->id }}" data-model="{{ get_class($item) }}" data-toggle="toggle" data-on="Công khai" data-off="Bản nháp" data-onstyle="success" data-offstyle="light">
            </td>
        </tr>
        @php
            $children = isset($childrenMap) ? $childrenMap->get($item->id, collect()) : collect();
        @endphp
        @if ($children->isNotEmpty())
            @include('backend.post-category.includes.category_item', [
                'categories' => $children,
                'level' => $level + 1,
                'childrenMap' => $childrenMap ?? collect(),
            ])
        @endif
    @endforeach
@endif

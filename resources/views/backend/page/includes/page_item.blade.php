@if (count($pages) > 0)
    @foreach ($pages as $item)
        <tr class="tr-item item-level-{{ isset($level) ? $level : 0 }}">
            <td class="text-center">
                <div class="icheck-info d-inline">
                    <input type="checkbox" id="{{ $item->id }}" name="seq_list[]" value="{{ $item->id }}">
                    <label for="{{ $item->id }}"></label>
                </div>
            </td>

            <td class="text-center">
                <input type="text" id="sort" class="form-control quick_change_value text-center" data-id="{{ $item->id }}" data-model="{{ get_class($item) }}" value="{{ $item->sort }}" reload-on-change>
            </td>

            <td class="title">
                <a class="row-title " href="{{ route('admin.page.edit', [$item->id]) }}">
                    <div>
                        <b style='color: #056FAD;'>
                            {{ $item->title }}
                            {{-- | {{ $item->title_en }} --}}
                        </b>
                    </div>
                </a>
                @if ($item->slug)
                    <div>
                        <b style='color:#777;'>URL:</b>
                        <a style='color:#00C600; word-break:break-all;' target='_blank' href="{{ route('page', ['slug' => $item->slug]) }}">{{ route('page', ['slug' => $item->slug]) }}</a>
                    </div>
                @endif
            </td>
            <td class="text-center">
                <img src="{{ get_image($item->image) }}" style="height: 70px">
            </td>
            <td class="text-center">
                {{ $item->updated_at }}
                <br>
                <input type="checkbox" id="status" class="quick_change_value" @checked($item->status == 1) value="1" value-off="0" data-id="{{ $item->id }}" data-model="{{ get_class($item) }}" data-toggle="toggle" data-on="Công khai" data-off="Bản nháp" data-onstyle="success" data-offstyle="light">
            </td>
        </tr>
        {{-- @if (count($item->children) > 0)
            @include('backend.page.includes.page_item', [
                'pages' => $item->children,
                'level' => $level + 1,
            ])
        @endif --}}
    @endforeach
@endif

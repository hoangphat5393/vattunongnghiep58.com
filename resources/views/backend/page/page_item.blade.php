@if (count($pages) > 0)
    @foreach ($pages as $item)
        <tr class="align-middle tr-item item-level-{{ isset($level) ? $level : 0 }}">
            <td class="text-center">
                <div class="icheck-info d-inline">
                    <input type="checkbox" id="{{ $item->id }}" name="seq_list[]" value="{{ $item->id }}">
                    <label for="{{ $item->id }}"></label>
                </div>
            </td>

            <td class="text-center">
                <input type="text" id="sort" class="text-center form-control quick_change_value" data-id="{{ $item->id }}" data-model="{{ get_class($item) }}" value="{{ $item->sort }}" reload-on-change>
            </td>

            <td class="title">
                <a class="row-title" href="{{ route('admin.page.edit', [$item->id]) }}">
                    <div>
                        <strong style="color: #056FAD;">
                            {{ $item->name }}
                            {{-- | {{ $item->name_en }}</b> --}}
                        </strong>
                    </div>
                </a>
                @if ($item->slug)
                    <div>
                        <b style="color:#777;">@lang('admin.link'):</b>
                        <a style="color:#00C600; word-break:break-all;" class="" target='_blank' href="{{ route('page', ['slug' => $item->slug]) }}">{{ route('page', ['slug' => $item->slug]) }}</a>
                    </div>
                    {{--
                    <div>
                        <b style='color:#777;'>URL EN:</b>
                        <a style='color:#00C600; word-break:break-all;' target='_blank' href="{{ route('page', ['slug' => $item->slug], true, 'en') }}">{{ route('page', ['slug' => $item->slug], true, 'en') }}</a>
                    </div> --}}
                @endif
            </td>
            <td class="text-center">
                <img src="{{ get_image($item->image) }}" style="height: 70px">
            </td>
            <td class="text-center">
                @if ($item->user)
                    <div class="mx-auto w-fit-content">{{ $item->user->name }}</div>
                @endif
            </td>
            <td class="text-center">
                {{ $item->updated_at }}
                <br>
                <input type="checkbox" id="status" class="quick_change_value" @checked($item->status == 1) value="1" value-off="0" data-id="{{ $item->id }}" data-model="{{ get_class($item) }}" data-toggle="toggle" data-on="@lang('admin.publish')" data-off="@lang('admin.draft')" data-onstyle="success" data-offstyle="light">
                {{-- <a href="{{ route('admin.page.destroy', $item->id) }}" class="btn btn-danger"><i class="fa-duotone fa-trash"></i></a> --}}
            </td>
        </tr>
        {{-- @if (count($item->children) > 0)
            @include('admin.page.includes.page_item', [
                'pages' => $item->children,
                'level' => $level + 1,
            ])
        @endif --}}
    @endforeach
@endif

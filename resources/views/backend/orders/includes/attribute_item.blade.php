@php
    $parent = $parent ?? '0';
@endphp
@if (count($attributes) > 0)
    @foreach ($attributes as $data)
        <tr class="tr-item item-level-{{ isset($level) ? $level : 0 }} parent-id-{{ $parent }}" style="{{ $level > 0 ? 'display:none' : '' }}">
            <td class="text-center">
                <input type="checkbox" id="{{ $data->id }}" name="seq_list[]" value="{{ $data->id }}">
            </td>
            <td class="text-center stt">{{ $data->sort }}</td>
            <td class="title">
                <div class="d-flex align-items-center justify-content-between">
                    <a class="row-title" href="{{ route('admin.attribute.edit', $data->id) }}">
                        <div>
                            {{ $level != 0 ? '---' : '' }}
                            <b style='color: #056FAD;'>{{ $data->attribute_name }}</b>
                            <sub><b>{{ $data->attribute_alias ?? '' }}</b></sub>
                        </div>
                    </a>
                    @if ($level == 0 && $data->children->count() > 0)
                        <div>
                            <button class="btn btn-info order-view-detail" data-id="{{ $data->id }}">Child attribute <i class="fa fa-fw fa-list-alt"></i></button>
                            <button class="btn btn-info order-view-hide" data-id="{{ $data->id }}" style="display: none;">Hide <i class="fa fa-fw fa-list-alt"></i></button>
                        </div>
                    @endif
                </div>
            </td>
            <td class="text-center">
                @if ($data->icon != null)
                    <img src="{{ asset($data->icon) }}" style="height: 50px">
                @endif
            </td>
            <td class="text-center">
                {{ $data->updated_at }}
                <br>
                @if ($data->status == 1)
                    <span class="badge text-bg-primary">Public</span>
                @else
                    <span class="badge text-bg-secondary">Draft</span>
                @endif
            </td>
        </tr>

        @if (count($data->children) > 0)
            @include('admin.attribute.includes.attribute_item', [
                'attributes' => $data->children,
                'level' => $level + 1,
                'parent' => $data->id,
            ])
        @endif
    @endforeach
@endif


@push('scripts')
    <script type="text/javascript">
        $(function() {
            $('.order-view-hide').click(function() {
                var id = $(this).data('id');
                $(this).hide();
                $(this).closest('tr').find('.order-view-detail').show();
                $('.parent-id-' + id).hide();
            });
            $('.order-view-detail').click(function() {
                var id = $(this).data('id')
                $(this).hide();
                $('.parent-id-' + id).show();
                $(this).closest('tr').find('.order-view-hide').show();
            });
        });
    </script>
@endpush

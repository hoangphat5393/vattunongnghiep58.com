@if (isset($product_id) && $product_id > 0)
    @php
        $variables = App\Models\Variable::where('status', 0)
            ->where('parent', 0)
            ->orderBy('stt', 'asc')
            ->pluck('name_en', 'id');

        $product_attr = App\Models\ThemeVariable::where('theme_id', $product_id)
            ->where('parent', 0)
            ->orderBy('id')
            ->get();
    @endphp

    <table class="table table-hover-variants">
        <thead>
            <tr>
                <th>Images</th>
                @foreach ($variables as $item)
                    <th>{{ $item }}</th>
                @endforeach
                <th>Price</th>
                <!-- <th>Is default</th> -->
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($product_attr as $attr_item)
                <tr>
                    <td>
                        @php
                            $variable_gallery = $attr_item->image ? unserialize($attr_item->image) : '';

                        @endphp
                        @if (is_array($variable_gallery))
                            <img src="{{ asset($variable_gallery[0]) }}" alt="" style="height: 50px;">
                        @endif
                    </td>
                    @foreach ($variables as $id_group => $item)
                        <td>
                            @if ($attr_item->variable_parent == $id_group)
                                {{ $attr_item->getVariable ? $attr_item->getVariable->name : '' }}
                            @else
                                @php
                                    $attr = \App\Models\ThemeVariable::where('variable_parent', $id_group)
                                        ->where('parent', $attr_item->id)
                                        ->first();
                                @endphp
                                @if ($attr)
                                    {{ $attr->getVariable ? $attr->getVariable->name : '' }}
                                @endif
                            @endif
                        </td>
                    @endforeach
                    <td>
                        @if ($attr_item->promotion)
                            <del class="text-danger">{{ $attr_item->price }}</del>
                            <span>{{ $attr_item->promotion }}</span>
                        @else
                            <span>{{ $attr_item->price }}</span>
                        @endif

                    </td>
                    <td style="width: 180px;" class="text-center">
                        <a href="#" class="btn btn-sm btn-info btn-trigger-edit-product-version edit-variation" data="{{ $attr_item->id }}">Edit</a>
                        <a href="javascript:;" class="btn-trigger-delete-version btn btn-danger btn-sm delete-variation" data="{{ $attr_item->id }}">Delete</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

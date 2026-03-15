@if ($orders_item->items()->count() > 0)

    @foreach ($orders_item->items as $item)
        <tr class="parent-id-{{ $orders_item->id }}">
            <td class="text-center">
                <b>{{ $item->id }}</b>
            </td>
            <td class="text-center">
                {{ $item->product->name }}
            </td>

            <td class="text-center">
                <div class="text-red">Sô lượng: {{ $item->quanlity }}</div>
                <div class="text-red">Giá: {{ number_format($item->price * $item->quanlity, 0, ',', '.') }} đ</div>
            </td>

            <td class="text-center">
            </td>
            <td class="text-center">
                {{-- {{ $item->updated_at }} --}}
            </td>
        </tr>
    @endforeach
@endif

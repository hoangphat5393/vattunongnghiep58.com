@if ($orders_item->items()->count() > 0)
    @foreach ($orders_item->items as $item)
        <tr class="parent-id-{{ $orders_item->cart_id }}" style="display: none;">
            <td class="text-center">
                <b>{{ $item->id }}</b>
            </td>
            <td class="text-center">
                {{ $item->product?->name ?? '—' }}
            </td>
            <td class="text-center">
                <div class="text-red">Số lượng: {{ $item->quanlity }}</div>
                <div class="text-red">Thành tiền: {{ number_format($item->price * $item->quanlity, 0, ',', '.') }} đ</div>
            </td>
            <td class="text-center"></td>
            <td class="text-center"></td>
        </tr>
    @endforeach
@endif

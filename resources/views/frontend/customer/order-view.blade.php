@php

    $id = $order->cart_id ?? 0;

    $order_details = \App\Models\Frontend\OrderItem::where('cart_id', $id)->get();

@endphp



@foreach ($order_details as $index => $item)

    @php

        $product = \App\Models\Frontend\Product::find($item->product_id);

    @endphp

    <tr class="order-detail order-id-{{ $id }}">

        <td colspan="2">

            <img src="{{ asset($product->image) }}" alt="" width="100" height="100">

            {{ $product->name }}

        </td>

        <td colspan="2" class="text-end">QTY: {{ $item->quanlity }}</td>

        <td class="text-end">{!! render_price($item->subtotal) !!}</td>

    </tr>

@endforeach



<tr class="payment order-id-{{ $id }}">

    <td colspan="4">

        Payment Method

    </td>

    <td>

        <ul class="order-price">

            <li>

                Total: <span>{!! render_price($order->cart_total) !!}</span>

            </li>

            <li>SUB-TOTAL: <span class="price">{!! render_price($order->cart_total + $order->shipping_cost) !!}</span></li>

        </ul>

    </td>

</tr>


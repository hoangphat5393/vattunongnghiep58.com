<div class="your-order mb-4">
    <div class="block-content bdr">
        <div class="title-block-p2">YOUR ORDER</div>
        <div class="body">
            <div class="table-responsive-sm order-table">
                <table class="table table-borderless text-center align-middle mb-0">
                    <thead>
                        <tr>
                            <th></th>
                            <th class="text-left">Name</th>
                            <th width="20%">Price</th>
                            <th>QTY</th>
                            <th width="20%">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($carts as $cart)
                        @php
                        $subtotal = 0;
                        $product = $ProductModel::find($cart->id);
                        $price = product_price($product, $cart->qty);
                        $subtotal += $price;
                        @endphp
                        <tr>
                            <td>
                                @if (!empty($product->image))
                                <a class="flex-shrink-0" href="{{ route('game.detail', $product->slug) }}" title="{{ $product->name }}">
                                    <img class="img-fluid item-image d-block mx-auto" src="{{ asset($product->image) }}" alt="{{ $product->name }}" width="70">
                                </a>
                                @endif
                            </td>
                            <td class="text-left">{{ $product->name }}</td>
                            <td>{!! render_price($cart->price) !!}</td>
                            <td>{{ $cart->qty }} {{ $product->unit }}</td>
                            <td style="white-space: nowrap;">$ {{ $price }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="font-weight-600">
                        <tr>
                            <td colspan="4" class="text-right">Subtotal</td>
                            <td class="cart_total" style="white-space: nowrap;">$ {{ $subtotal }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
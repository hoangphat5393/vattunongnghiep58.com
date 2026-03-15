@inject('ProductModel', 'App\Product')

@php
    $subtotal = 0;
@endphp

<table id="table-cart" class="table table-borderless align-middle table-cart">
    <thead>
        <tr>
            <th scope="col"></th>
            <th scope="col">Product</th>
            <th scope="col">Quantity</th>
            <th scope="col">Price</th>
            {{-- <th scope="col">Promotion</th> --}}
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($carts as $cart)
            @php
                $price = 0;
                $product = $ProductModel::find($cart->id);
                $price = product_price($product, $cart->qty);
                $subtotal += $price;
            @endphp
            @if (!empty($product))
                <tr class="cart-items cart__row_item">
                    <td class="cart-thumb">
                        @if (!empty($product->image))
                            <a class="flex-shrink-0" href="{{ route('game.detail', $product->slug) }}" title="{{ $product->name }}">
                                <img class="img-fluid item-image d-block mx-auto" src="{{ asset($product->image) }}" alt="{{ $product->name }}" width="70">
                            </a>
                        @endif
                    </td>
                    <td style="width: 250px">
                        <a class="flex-shrink-0 item-name" href="{{ route('game.detail', $product->slug) }}" title="{{ $product->name }}">{{ $product->name }}</a>
                    </td>
                    <td>
                        <div class="mx-auto mx-sm-0 text-center text-sm-start" style="max-width: 9rem;">
                            <input data-rowid="{{ $cart->rowId }}" class="form-control form-control-sm cart__qty-input quantity1" type="number" name="updates[]" value="{{ $cart->qty }}" min="{{ $product->min_quantity }}" max="{{ $product->stock }}">
                        </div>
                    </td>
                    <td>
                        <div class="fs-lg text-accent">{!! render_price($cart->price) !!}</div>
                    </td>
                    {{-- <td></td> --}}
                    <td style="width: 80px">
                        <button class="btn btn-link w-100 cart__remove" type="button" data="{{ $cart->rowId }}">
                            <img class="d-block mx-auto icon" src="{{ asset($templateFile . '/images/remove-icon.png') }}" alt="remove">
                        </button>
                    </td>
                </tr>
            @endif
        @endforeach
        <tr>
            <td colspan="6">
                <div class="d-flex justify-content-between">
                    <a href="{{ url()->previous() }}" class="btn btn-sm btn-table-cart" title="Continue shopping">
                        <i class="fas fa-arrow-left"></i>&nbsp; Continue shopping
                    </a>
                </div>
            </td>
        </tr>

    </tbody>
</table>

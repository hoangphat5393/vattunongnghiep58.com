<div class="your-order mb-4">
    <div class="block-content bdr">
        <div class="title-block-p2">YOUR ORDER</div>
        <div class="body">
            <div class="table-responsive-sm order-table">
                <table class="table table-borderless text-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-start">Name</th>
                            <th width="20%">Price</th>
                            <th width="15%">Unit</th>
                            <th>QTY</th>
                            <th width="20%">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $weight = 0; @endphp
                        <tr>
                            <td class="text-start">{{ $product->name }}</td>
                            <td>{!! render_price($product->price) !!}</td>
                            <td>
                                @if (count($option['options']))
                                    <div>{!! render_option_name($option['options']['unit']) !!}</div>
                                @endif
                            </td>
                            <td>{{ $option['qty'] }}</td>
                            <td style="white-space: nowrap;">{!! render_price($product->price) !!}</td>
                        </tr>
                    </tbody>
                    <tfoot class="font-weight-600">
                        <tr>
                            <td colspan="4" class="text-end">Subtotal</td>
                            <td class="cart_total" style="white-space: nowrap;">{!! render_price($total) !!}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="ounces" value="0">
<input type="hidden" name="weight" value="{{ $weight }}">
{{--

<div class="your-order">
    <h4 class="order-title mb-4">Order detail</h4>

    <div class="table-responsive-sm order-table">
        <table class="bg-white table table-bordered table-hover text-center">
            <thead>
                <tr>
                    <th class="text-start">Name</th>
                    <th width="20%">Price</th>
                    <th width="15%">Unit</th>
                    <th width="15%">Promotion</th>
                    <th>Qty</th>
                    <th width="20%">Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    //$product = \App\Product::find($cart->id);
                    // dd($option, $total);
                @endphp
                <tr>
                    <td class="text-start">{{ $product->name }}</td>
                    <td>$ {!! number_format($product->price, 2, '.', ',') !!}</td>
                    <td>
                        @if (count($option['options']))
                            <div>{!! render_option_name($option['options']['unit']) !!}</div>
                        @endif
                    </td>
                    <td>
                        @if (count($option['options']))
                            <div>$ {!! number_format($option['options']['promotion'], 2, '.', ',') !!}</div>
                        @endif
                    </td>
                    <td>{{ $option['qty'] }}</td>
                    <td>$ {!! number_format($product->price, 2, '.', ',') !!}</td>
                </tr>
            </tbody>
            <tfoot class="font-weight-600">
                <tr>
                    <td colspan="5" class="text-end">Subtotal</td>
                    <td class="cart_total">$ {!! number_format($total, 2, '.', ',') !!}</td>
                </tr>
            </tfoot>
        </table>
        <input type="hidden" name="ounces" value="0">
    </div>
</div>

--}}
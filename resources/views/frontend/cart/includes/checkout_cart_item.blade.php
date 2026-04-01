@inject('ProductModel', 'App\Models\Frontend\Product')

<div class="overflow-x-auto">
    <table class="w-full min-w-[640px]">
        <thead class="bg-leaf-50 border-b border-leaf-100 text-left">
            <tr>
                <th class="py-4 px-4 md:px-6 font-bold text-gray-700">Sản phẩm</th>
                <th class="py-4 px-4 md:px-6 font-bold text-gray-700 text-center hidden sm:table-cell">Đơn giá</th>
                <th class="py-4 px-4 md:px-6 font-bold text-gray-700 text-center">Số lượng</th>
                <th class="py-4 px-4 md:px-6 font-bold text-gray-700 text-right">Thành tiền</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach ($carts as $cart)
                @php
                    $product = $ProductModel::find($cart->id);
                    $lineTotal = $cart->price * $cart->qty;
                @endphp
                @if (!empty($product))
                    <tr class="hover:bg-gray-50/80 transition">
                        <td class="py-4 px-4 md:px-6 align-middle">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0 ring-1 ring-gray-100">
                                    @if (!empty($product->image))
                                        <a href="{{ route('product.detail', [$product->slug, $product->id]) }}" title="{{ $product->name }}">
                                            <img src="{{ get_image($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover" width="64" height="64">
                                        </a>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <a href="{{ route('product.detail', [$product->slug, $product->id]) }}" class="font-bold text-gray-900 hover:text-leaf-600 leading-snug block">
                                        {{ $product->name }}
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-4 md:px-6 text-center align-middle hidden sm:table-cell">
                            <span class="font-semibold text-leaf-700">{!! render_price($cart->price, 'VND') !!}</span>
                        </td>
                        <td class="py-4 px-4 md:px-6 text-center align-middle">
                            <span class="inline-flex items-center justify-center min-w-[2.5rem] px-2 py-1 rounded-lg bg-gray-100 text-gray-800 font-bold text-sm">
                                {{ $cart->qty }}@if (!empty($product->unit))
                                    <span class="text-gray-500 font-normal ml-0.5">{{ $product->unit }}</span>
                                @endif
                            </span>
                        </td>
                        <td class="py-4 px-4 md:px-6 text-right align-middle whitespace-nowrap">
                            <span class="font-extrabold text-gray-900">{!! render_price($lineTotal, 'VND') !!}</span>
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>

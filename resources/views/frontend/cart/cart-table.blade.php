<table class="w-full">
    <thead class="bg-leaf-50 border-b border-leaf-100 text-left">
        <tr>
            <th class="py-4 px-4 md:px-6 font-bold text-gray-700">Sản phẩm</th>
            <th class="py-4 px-4 md:px-6 font-bold text-gray-700 text-center hidden md:table-cell">
                Đơn giá
            </th>
            <th class="py-4 px-4 md:px-6 font-bold text-gray-700 text-center">
                Số lượng
            </th>
            <th class="py-4 px-4 md:px-6 font-bold text-gray-700 text-center">
                Thành tiền
            </th>
            <th class="py-4 px-4 md:px-6"></th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-100">
        @forelse ($cart_items as $item)
            <tr class="hover:bg-gray-50 transition cart-items cart__row_item">
                <td class="py-4 px-4 md:px-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-100 shrink-0">
                            @if (!empty($item['image']))
                                <a href="{{ route('product.detail', [$item['slug'], $item['id']]) }}" title="{{ $item['name'] }}">
                                    <img src="{{ get_image($item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                                </a>
                            @endif
                        </div>
                        <div>
                            <a href="{{ route('product.detail', [$item['slug'], $item['id']]) }}" title="{{ $item['name'] }}" class="font-bold text-gray-900 hover:text-leaf-600">
                                {{ $item['name'] }}
                            </a>
                            @if (!empty($item['price_label']))
                                <div class="mt-1 inline-flex items-center gap-2 text-xs font-bold text-gray-600">
                                    <span class="px-2 py-1 rounded-full bg-gray-100">
                                        {{ $item['price_label'] }}@if (!empty($item['price_unit']))
                                            / {{ $item['price_unit'] }}
                                        @endif
                                    </span>
                                </div>
                            @endif
                            @if (!empty($item['description_excerpt']))
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $item['description_excerpt'] }}
                                </p>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="py-4 px-4 md:px-6 text-center hidden md:table-cell align-middle">
                    <span class="font-bold text-leaf-700">
                        {!! render_price($item['unit_price'], 'VND') !!}
                    </span>
                </td>
                <td class="py-4 px-4 md:px-6 align-middle">
                    <div class="flex items-center justify-center gap-2">
                        <button type="button" class="cursor-pointer w-8 h-8 flex items-center justify-center rounded-full border border-gray-300 text-gray-600 hover:bg-gray-100 quantity-decrease" data-rowid="{{ $item['row_id'] }}">
                            -
                        </button>
                        <input class="w-12 text-center font-bold text-gray-800 border border-gray-200 rounded-lg quantity1 cart__qty-input cursor-text" type="number" name="updates[]" value="{{ $item['qty'] }}" min="1" data-rowid="{{ $item['row_id'] }}">
                        <button type="button" class="cursor-pointer w-8 h-8 flex items-center justify-center rounded-full border border-gray-300 text-gray-600 hover:bg-gray-100 quantity-increase" data-rowid="{{ $item['row_id'] }}">
                            +
                        </button>
                    </div>
                </td>
                <td class="py-4 px-4 md:px-6 text-center align-middle">
                    <span class="font-bold text-gray-900">
                        {!! render_price($item['line_total'], 'VND') !!}
                    </span>
                </td>
                <td class="py-4 px-4 md:px-6 text-center align-middle">
                    <button class="cursor-pointer text-red-500 hover:text-red-700 transition inline-flex items-center gap-1 text-sm font-bold cart__remove" type="button" data-rowid="{{ $item['row_id'] }}" data="{{ $item['row_id'] }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                        Xóa
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="py-10 px-6 text-center text-gray-500 text-base font-medium">
                    Giỏ hàng của bạn đang trống!
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="p-4 md:p-6 bg-gray-50 flex flex-col sm:flex-row sm:items-center justify-between gap-3 mt-2 rounded-b-2xl border-t border-gray-100">
    <a href="{{ route('product') }}" class="cursor-pointer inline-flex items-center gap-2 text-leaf-600 font-bold hover:underline text-sm md:text-base">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
            </path>
        </svg>
        Tiếp tục mua hàng
    </a>
    <a href="{{ route('carts.remove') }}" class="cursor-pointer text-red-500 hover:text-red-700 font-bold text-sm md:text-base whitespace-nowrap">
        Xóa tất cả
    </a>
</div>

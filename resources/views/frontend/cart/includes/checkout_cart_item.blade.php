<div class="overflow-x-auto">
    <table class="w-full min-w-160">
        <thead class="bg-leaf-50 border-b border-leaf-100 text-left">
            <tr>
                <th class="py-4 px-4 md:px-6 font-bold text-gray-700">Sản phẩm</th>
                <th class="py-4 px-4 md:px-6 font-bold text-gray-700 text-center hidden sm:table-cell">Đơn giá</th>
                <th class="py-4 px-4 md:px-6 font-bold text-gray-700 text-center">Số lượng</th>
                <th class="py-4 px-4 md:px-6 font-bold text-gray-700 text-right">Thành tiền</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach ($cart_items as $item)
                <tr class="hover:bg-gray-50/80 transition">
                    <td class="py-4 px-4 md:px-6 align-middle">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-100 shrink-0 ring-1 ring-gray-100">
                                @if (!empty($item['image']))
                                    <a href="{{ route('product.detail', [$item['slug'], $item['id']]) }}" title="{{ $item['name'] }}">
                                        <img src="{{ get_image($item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover" width="64" height="64">
                                    </a>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <a href="{{ route('product.detail', [$item['slug'], $item['id']]) }}" class="font-bold text-gray-900 hover:text-leaf-600 leading-snug block">
                                    {{ $item['name'] }}
                                </a>
                                @if (!empty($item['price_label']))
                                    <div class="mt-1 text-xs font-bold text-gray-600">
                                        <span class="px-2 py-1 rounded-full bg-gray-100 inline-block">
                                            {{ $item['price_label'] }}@if (!empty($item['price_unit']))
                                                / {{ $item['price_unit'] }}
                                            @endif
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-4 md:px-6 text-center align-middle hidden sm:table-cell">
                        <span class="font-semibold text-leaf-700">{!! render_price($item['unit_price'], 'VND') !!}</span>
                    </td>
                    <td class="py-4 px-4 md:px-6 text-center align-middle">
                        <span class="inline-flex items-center justify-center min-w-10 px-2 py-1 rounded-lg bg-gray-100 text-gray-800 font-bold text-sm">
                            {{ $item['qty'] }}@if (!empty($item['unit']))
                                <span class="text-gray-500 font-normal ml-0.5">{{ $item['unit'] }}</span>
                            @endif
                        </span>
                    </td>
                    <td class="py-4 px-4 md:px-6 text-right align-middle whitespace-nowrap">
                        <span class="font-extrabold text-gray-900">{!! render_price($item['line_total'], 'VND') !!}</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

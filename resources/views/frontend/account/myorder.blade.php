@extends('frontend.layouts.master')

@section('seo')
    @include('frontend.layouts.seo', $seo ?? [])
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6 md:py-10">
        <div class="mb-6 text-sm text-gray-500 flex items-center gap-2 flex-wrap">
            <a href="{{ route('index') }}" class="hover:text-leaf-600 no-underline">Trang chủ</a>
            <span>/</span>
            <span class="text-leaf-700 font-bold">Đơn hàng của tôi</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8">
            <aside class="lg:col-span-3">
                @include($templatePath . '.account.includes.account-nav')
            </aside>

            <div class="lg:col-span-9">
                <div class="rounded-2xl border border-leaf-100 bg-white p-5 md:p-6 shadow-sm">
                    <h1 class="text-xl md:text-2xl font-extrabold text-gray-900 mb-6">Đơn hàng của tôi</h1>

                    @if ($orders->isEmpty())
                        <div class="rounded-xl border border-dashed border-gray-200 bg-leaf-50/50 px-6 py-10 text-center">
                            <p class="text-gray-600 mb-4">Bạn chưa có đơn hàng nào.</p>
                            <a href="{{ route('index') }}" class="inline-flex items-center justify-center rounded-xl bg-leaf-600 px-5 py-2.5 font-bold text-white no-underline hover:bg-leaf-700 transition">
                                Tiếp tục mua sắm
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-100 text-left text-gray-500">
                                        <th class="pb-3 pr-4 font-semibold">Mã đơn</th>
                                        <th class="pb-3 pr-4 font-semibold">Ngày đặt</th>
                                        <th class="pb-3 pr-4 font-semibold">Tổng tiền</th>
                                        <th class="pb-3 pr-4 font-semibold">Trạng thái</th>
                                        <th class="pb-3 font-semibold text-right">Chi tiết</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td class="py-4 pr-4 align-top">
                                                <a href="{{ route('customer.orders.show', $order->cart_id) }}" class="font-bold text-leaf-700 hover:text-leaf-500 no-underline">
                                                    {{ $order->cart_code ?: '#' . $order->cart_id }}
                                                </a>
                                            </td>
                                            <td class="py-4 pr-4 align-top text-gray-600">
                                                {{ $order->created_at?->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="py-4 pr-4 align-top font-semibold text-gray-900">
                                                {!! render_price($order->cart_total) !!}
                                            </td>
                                            <td class="py-4 pr-4 align-top">
                                                <span class="inline-flex rounded-full bg-leaf-50 px-3 py-1 text-xs font-semibold text-leaf-700">
                                                    {{ $orderStatus[$order->cart_status] ?? 'Chờ xác nhận' }}
                                                </span>
                                            </td>
                                            <td class="py-4 text-right align-top">
                                                <a href="{{ route('customer.orders.show', $order->cart_id) }}" class="inline-flex rounded-lg border border-leaf-200 px-3 py-1.5 font-semibold text-leaf-700 no-underline hover:bg-leaf-50 transition">
                                                    Xem
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $orders->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

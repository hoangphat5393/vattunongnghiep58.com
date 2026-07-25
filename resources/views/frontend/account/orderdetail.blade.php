@php
    extract($data);
@endphp

@extends('frontend.layouts.master')

@section('seo')
    @include('frontend.layouts.seo', $seo ?? [])
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6 md:py-10">
        <div class="mb-6 text-sm text-gray-500 flex items-center gap-2 flex-wrap">
            <a href="{{ route('index') }}" class="hover:text-leaf-600 no-underline">Trang chủ</a>
            <span>/</span>
            <a href="{{ route('customer.orders.index') }}" class="hover:text-leaf-600 no-underline">Đơn hàng</a>
            <span>/</span>
            <span class="text-leaf-700 font-bold">{{ $order->cart_code ?: '#' . $order->cart_id }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8">
            <aside class="lg:col-span-3">
                @include($templatePath . '.account.includes.account-nav')
            </aside>

            <div class="lg:col-span-9 space-y-6">
                <div class="rounded-2xl border border-leaf-100 bg-white p-5 md:p-6 shadow-sm">
                    <div class="flex flex-wrap items-start justify-between gap-3 mb-6">
                        <div>
                            <h1 class="text-xl md:text-2xl font-extrabold text-gray-900">Chi tiết đơn hàng</h1>
                            <p class="text-gray-500 mt-1">{{ $order->cart_code ?: '#' . $order->cart_id }}</p>
                        </div>
                        <span class="inline-flex rounded-full bg-leaf-50 px-3 py-1 text-sm font-semibold text-leaf-700">
                            {{ $orderStatus[$order->cart_status] ?? 'Chờ xác nhận' }}
                        </span>
                    </div>

                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <dt class="text-gray-500">Họ tên</dt>
                            <dd class="font-semibold text-gray-900">{{ $order->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Điện thoại</dt>
                            <dd class="font-semibold text-gray-900">{{ $order->cart_phone }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Email</dt>
                            <dd class="font-semibold text-gray-900">{{ $order->cart_email }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Thanh toán</dt>
                            <dd class="font-semibold text-gray-900">{{ $orderPayment[$order->cart_payment] ?? 'Chưa thanh toán' }}</dd>
                        </div>
                        <div class="md:col-span-2">
                            <dt class="text-gray-500">Địa chỉ giao hàng</dt>
                            <dd class="font-semibold text-gray-900">{{ $order->cart_address }}</dd>
                        </div>
                        @if ($order->cart_note)
                            <div class="md:col-span-2">
                                <dt class="text-gray-500">Ghi chú</dt>
                                <dd class="text-gray-900">{{ $order->cart_note }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>

                <div class="rounded-2xl border border-leaf-100 bg-white p-5 md:p-6 shadow-sm overflow-x-auto">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Sản phẩm</h2>
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-100 text-left text-gray-500">
                                <th class="pb-3 pr-4 font-semibold">Sản phẩm</th>
                                <th class="pb-3 pr-4 font-semibold">Đơn giá</th>
                                <th class="pb-3 pr-4 font-semibold">SL</th>
                                <th class="pb-3 font-semibold text-right">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($order_detail as $item)
                                @php
                                    $product = $item->product;
                                    $lineTotal = (float) $item->price * (int) $item->quanlity;
                                @endphp
                                <tr>
                                    <td class="py-4 pr-4 align-top">
                                        @if ($product)
                                            <a href="{{ route('product.detail', [$product->slug, $product->id]) }}" class="font-semibold text-leaf-700 hover:text-leaf-500 no-underline">
                                                {{ $product->name }}
                                            </a>
                                        @else
                                            <span class="text-gray-700">Sản phẩm #{{ $item->product_id }}</span>
                                        @endif
                                    </td>
                                    <td class="py-4 pr-4 align-top">{!! render_price($item->price) !!}</td>
                                    <td class="py-4 pr-4 align-top">{{ $item->quanlity }}</td>
                                    <td class="py-4 text-right align-top font-semibold">{!! render_price($lineTotal) !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="pt-4 text-right font-bold text-gray-700">Tổng cộng</td>
                                <td class="pt-4 text-right font-extrabold text-leaf-700">{!! render_price($order->cart_total) !!}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <a href="{{ route('customer.orders.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-leaf-700 hover:text-leaf-500 no-underline">
                    ← Quay lại danh sách đơn
                </a>
            </div>
        </div>
    </div>
@endsection

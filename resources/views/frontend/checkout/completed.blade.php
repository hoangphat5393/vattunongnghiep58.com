@extends('frontend.layouts.master')

@section('seo')
    @include('frontend.layouts.seo', ['seo_title' => 'Đặt hàng thành công'])
@endsection

@section('content')
    @include('frontend.includes.menu')

    <div class="container mx-auto px-4 py-4">
        <div class="text-sm text-gray-500 flex items-center gap-2 flex-wrap">
            <a href="{{ route('index') }}" class="hover:text-leaf-600">Trang chủ</a>
            <span>/</span>
            <span class="text-leaf-700 font-bold">Đặt hàng thành công</span>
        </div>
    </div>

    <div class="bg-leaf-50 grow pb-16">
        <div class="container mx-auto px-4 py-8 md:py-12 max-w-3xl">
            @if (session('checkout_success'))
                <div class="mb-6 rounded-xl border border-leaf-200 bg-leaf-50 px-4 py-3 text-sm text-leaf-900">
                    {{ session('checkout_success') }}
                </div>
            @endif

            <div class="text-center mb-8">
                <div class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-leaf-100 text-leaf-600 mb-4">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">Đã ghi nhận đơn hàng</h1>
                <p class="text-gray-600 mt-2 text-sm md:text-base">Cảm ơn bạn đã đặt hàng tại {{ setting_option('webtitle') }}. Nhân viên sẽ liên hệ qua điện thoại để xác nhận và thống nhất giao hàng.</p>
            </div>

            <div class="bg-white rounded-2xl border border-leaf-100 shadow-sm p-6 md:p-8">
                <p class="text-gray-800 mb-6">
                    Xin chào <span class="font-bold text-gray-900">{{ $cart->name }}</span>,
                </p>

                <dl class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-sm border-b border-gray-100 pb-6 mb-6">
                    <dt class="text-gray-500 sm:col-span-1">Mã đơn hàng</dt>
                    <dd class="font-bold text-gray-900 sm:col-span-2">#{{ $cart->cart_id }}</dd>

                    <dt class="text-gray-500 sm:col-span-1">Tổng giá trị</dt>
                    <dd class="font-bold text-leaf-700 sm:col-span-2">{!! render_price($cart->cart_total, 'VND') !!}</dd>

                    <dt class="text-gray-500 sm:col-span-1">Trạng thái</dt>
                    <dd class="font-semibold text-gray-800 sm:col-span-2">
                        @switch($cart->cart_status ?? null)
                            @case(0)
                                Chờ xác nhận
                            @break

                            @case(1)
                                Đã xác nhận
                            @break

                            @default
                                Đang xử lý
                        @endswitch
                    </dd>
                </dl>

                <p class="text-gray-600 text-sm leading-relaxed mb-8">
                    Chúng tôi sẽ liên hệ bạn trong thời gian sớm nhất. Nếu cần hỗ trợ gấp, vui lòng gọi hotline trên website.
                </p>

                @if ($cart->items()->exists())
                    <div id="order-detail-panel" class="hidden mb-8 border border-gray-100 rounded-xl overflow-hidden">
                        <div class="bg-gray-50 px-4 py-2 font-bold text-gray-800 text-sm">Chi tiết sản phẩm</div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-leaf-50/80 text-left text-gray-700">
                                    <tr>
                                        <th class="py-3 px-4">Sản phẩm</th>
                                        <th class="py-3 px-4 text-center">SL</th>
                                        <th class="py-3 px-4 text-right">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($cart->items as $item)
                                        @php
                                            $product = $item->product;
                                        @endphp
                                        @if ($product)
                                            <tr>
                                                <td class="py-3 px-4">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-100 shrink-0">
                                                            @if (!empty($product->image))
                                                                <img src="{{ get_image($product->image) }}" alt="" class="w-full h-full object-cover">
                                                            @endif
                                                        </div>
                                                        <div class="min-w-0">
                                                            <div class="font-medium text-gray-900">{{ $product->name }}</div>
                                                            @if (!empty($item->price_label))
                                                                <div class="mt-1 text-xs font-bold text-gray-600">
                                                                    <span class="px-2 py-1 rounded-full bg-gray-100 inline-block">
                                                                        {{ $item->price_label }}@if (!empty($item->price_unit))
                                                                            / {{ $item->price_unit }}
                                                                        @endif
                                                                    </span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-3 px-4 text-center">{{ $item->quanlity }}</td>
                                                <td class="py-3 px-4 text-right font-semibold">{!! render_price(($item->price ?? $product->price) * $item->quanlity, 'VND') !!}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('index') }}" class="inline-flex justify-center items-center rounded-xl bg-leaf-600 px-6 py-3 font-bold text-white shadow hover:bg-leaf-700 transition">
                        Tiếp tục mua hàng
                    </a>
                    @if ($cart->items()->exists())
                        <button type="button" id="toggle-order-detail" class="inline-flex justify-center items-center rounded-xl border-2 border-gray-200 px-6 py-3 font-bold text-gray-800 hover:border-leaf-400 transition">
                            Xem chi tiết đơn
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('toggle-order-detail')?.addEventListener('click', function() {
            var p = document.getElementById('order-detail-panel');
            if (!p) return;
            p.classList.toggle('hidden');
            this.textContent = p.classList.contains('hidden') ? 'Xem chi tiết đơn' : 'Ẩn chi tiết đơn';
        });
    </script>
@endpush

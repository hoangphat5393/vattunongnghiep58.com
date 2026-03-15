<ul class="list-group mb-3">
    <li class="list-group-item">
        <div class="row">
            <div class="col-10">
                <div class="d-flex">
                    <div style="width: 150px;">Người nhận</div>
                    <div style="flex: 1;">
                        <div>Họ tên: {{ $ship_name ?? '' }}</div>
                        <div>Điện thoại: {{ $ship_phone ?? '' }}</div>
                        <div>Email: {{ $email ?? '' }}</div>
                    </div>
                </div>
            </div>
            <div class="col-2 text-end">
                @isset($product)
                    <a href="{{ route('shop.buyNow', $product->id) }}" title="">Thay đổi</a>
                @else
                    <a href="{{ route('cart.checkout') }}" title="">Thay đổi</a>
                @endisset
            </div>
        </div>
    </li>
    <li class="list-group-item">
        <div class="row">
            <div class="col-10">
                <div class="d-flex">
                    <div style="width: 150px;">
                        @if ($delivery == 'shipping')
                            @lang('Giao hàng nhanh')
                        @else
                            @lang('Pick up')
                        @endif
                    </div>
                    <div style="flex: 1;">
                        @if ($delivery == 'shipping')
                            @php
                                $state = \App\Models\Province::where('name', $state_province)->first();
                            @endphp
                            <span>{{ implode(', ', array_filter([$address_line1, $state_province])) }}</span>
                        @else
                            {!! htmlspecialchars_decode(setting_option('pickup_address')) !!}
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-2 text-end">
                @isset($product)
                    <a href="{{ route('shop.buyNow', $product->id) }}" title="">Thay đổi</a>
                @else
                    <a href="{{ route('cart.checkout') }}" title="">Thay đổi</a>
                @endisset
            </div>
        </div>
    </li>
</ul>

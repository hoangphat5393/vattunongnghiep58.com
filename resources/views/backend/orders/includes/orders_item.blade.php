@if ($orders_item->items && $orders_item->items->count() > 0)
    <tr class="order-items-row parent-id-{{ $orders_item->cart_id }} bg-light" style="display: none;">
        <td colspan="8" class="p-3">
            <div class="card border border-light-subtle shadow-sm mb-0">
                <div class="card-header bg-white py-2 px-3 d-flex justify-content-between align-items-center">
                    <span class="fw-bold small text-primary">
                        <i class="fa-solid fa-boxes-stacked me-1"></i> Chi tiết sản phẩm trong đơn hàng #{{ $orders_item->cart_code ?: $orders_item->cart_id }}
                    </span>
                    <span class="badge bg-secondary-subtle text-secondary border small">
                        Tổng cộng: {{ $orders_item->items->count() }} sản phẩm
                    </span>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 40px;">#</th>
                                <th style="min-width: 250px;">Tên sản phẩm</th>
                                <th class="text-center" style="width: 140px;">Đơn giá</th>
                                <th class="text-center" style="width: 100px;">Số lượng</th>
                                <th class="text-end pe-3" style="width: 150px;">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders_item->items as $idx => $it)
                                <tr>
                                    <td class="text-center text-muted small">{{ $idx + 1 }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">
                                            {{ $it->product?->name ?? ($it->name ?? 'Sản phẩm đã gỡ') }}
                                        </div>
                                        @if (!empty($it->sku))
                                            <small class="text-muted">Mã SKU: <code>{{ $it->sku }}</code></small>
                                        @endif
                                    </td>
                                    <td class="text-center text-muted">
                                        {{ number_format($it->price ?? 0, 0, ',', '.') }} đ
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                            x {{ $it->quanlity ?? $it->quantity ?? 1 }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-3 fw-bold text-danger">
                                        {{ number_format(($it->price ?? 0) * ($it->quanlity ?? $it->quantity ?? 1), 0, ',', '.') }} đ
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </td>
    </tr>
@endif

<div class="mb-4 card card-info card-outline shadow-sm">
    <div class="card-header font-weight-bold bg-light">
        <i class="fa fa-tags me-1 text-info"></i> Price & Stock | Giá và kho
    </div>
    <div class="card-body">
        <!-- Hàng 1: Hình thức định giá -->
        <div class="form-group mb-3 pb-3 border-bottom">
            <label class="font-weight-bold d-block mb-2 text-dark">Hình thức định giá:</label>
            <div class="custom-control custom-radio custom-control-inline mr-4">
                <input type="radio" id="price_type1" class="custom-control-input" name="price_type"
                    value="price" @if ($price_type == 'price') checked @endif>
                <label for="price_type1" class="custom-control-label font-weight-normal">Bán theo giá</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="price_type2" class="custom-control-input" name="price_type"
                    value="contact" @if ($price_type == 'contact') checked @endif>
                <label for="price_type2" class="custom-control-label font-weight-normal">Liên hệ báo giá</label>
            </div>
        </div>

        <!-- Hàng 2: Giá bán & Giá vốn/gốc -->
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="form-group mb-0">
                    <label for="sale_price" class="font-weight-bold">
                        Giá bán <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="sale_price" id="sale_price" value="{{ $sale_price ?? '' }}"
                        class="form-control" placeholder="Ví dụ: 160000">
                    <small class="form-text text-muted">Giá khách mua / thanh toán thực tế</small>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="form-group mb-0">
                    <label for="price" class="font-weight-bold">
                        Giá vốn / Giá gốc
                    </label>
                    <input type="text" name="price" id="price" value="{{ $price ?? '' }}"
                        class="form-control" placeholder="Ví dụ: 188000">
                    <small class="form-text text-muted">Giá niêm yết (để trống nếu không giảm giá)</small>
                </div>
            </div>
        </div>

        <!-- Hàng 3: Đơn vị tính & Kho hàng -->
        <div class="row">
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="form-group mb-0">
                    <label for="unit" class="font-weight-bold">Đơn vị tính</label>
                    <input type="text" name="unit" id="unit" value="{{ $unit ?? '' }}"
                        class="form-control" placeholder="Ví dụ: VNĐ, Kg, Gói, Hộp...">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group mb-0">
                    <label for="stock" class="font-weight-bold">Kho hàng</label>
                    <input type="text" name="stock" id="stock" value="{{ $stock ?? '' }}"
                        class="form-control" placeholder="Số lượng tồn kho">
                </div>
            </div>
        </div>

        @php
            $product_prices = isset($product_detail) ? $product_detail->prices : [];
            $default_index = 0;
            if (!empty($product_prices)) {
                foreach ($product_prices as $idx => $pp) {
                    if (!empty($pp->is_default)) {
                        $default_index = $idx;
                        break;
                    }
                }
            }
        @endphp

        <hr>

        <div class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Nhiều mức giá</h5>
            <button type="button" class="btn btn-sm btn-primary js-add-price-row">Thêm mức giá</button>
        </div>

        <div class="mt-3 table-responsive">
            <table class="table mb-0 align-middle table-bordered" id="product-prices-table">
                <thead>
                    <tr>
                        <th style="width: 30%">Tên mức giá</th>
                        <th style="width: 20%">Giá</th>
                        <th style="width: 20%">Đơn vị</th>
                        <th style="width: 10%" class="text-center">Mặc định</th>
                        <th style="width: 10%" class="text-center">Hiện</th>
                        <th style="width: 10%"></th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($product_prices) && count($product_prices))
                        @foreach ($product_prices as $i => $pp)
                            <tr>
                                <td>
                                    <input type="text" class="form-control"
                                        name="prices[{{ $i }}][label]" value="{{ $pp->label ?? '' }}">
                                </td>
                                <td>
                                    <input type="text" class="form-control"
                                        name="prices[{{ $i }}][price]" value="{{ $pp->price ?? '' }}">
                                </td>
                                <td>
                                    <input type="text" class="form-control"
                                        name="prices[{{ $i }}][unit]" value="{{ $pp->unit ?? '' }}">
                                </td>
                                <td class="text-center">
                                    <input type="radio" name="prices_default" value="{{ $i }}"
                                        @if ($i == $default_index) checked @endif>
                                </td>
                                <td class="text-center">
                                    <input type="hidden" name="prices[{{ $i }}][status]" value="0">
                                    <input type="checkbox" value="1"
                                        @if (($pp->status ?? 1) == 1) checked @endif
                                        onchange="this.previousElementSibling.value = this.checked ? 1 : 0;">
                                </td>
                                <td class="text-center">
                                    <button type="button"
                                        class="btn btn-sm btn-outline-danger js-remove-price-row">Xóa</button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

</div>

@push('scripts')
    <script>
        (function() {
            var tableBody = document.querySelector('#product-prices-table tbody');
            var addBtn = document.querySelector('.js-add-price-row');
            if (!tableBody || !addBtn) return;

            function nextIndex() {
                var rows = tableBody.querySelectorAll('tr');
                return rows.length;
            }

            function addRow() {
                var i = nextIndex();
                var tr = document.createElement('tr');
                tr.innerHTML = `
                    <td><input type="text" class="form-control" name="prices[${i}][label]" value=""></td>
                    <td><input type="text" class="form-control" name="prices[${i}][price]" value=""></td>
                    <td><input type="text" class="form-control" name="prices[${i}][unit]" value=""></td>
                    <td class="text-center"><input type="radio" name="prices_default" value="${i}" ${i === 0 ? 'checked' : ''}></td>
                    <td class="text-center">
                        <input type="hidden" name="prices[${i}][status]" value="1">
                        <input type="checkbox" value="1" checked onchange="this.previousElementSibling.value = this.checked ? 1 : 0;">
                    </td>
                    <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger js-remove-price-row">Xóa</button></td>
                `;
                tableBody.appendChild(tr);
            }

            addBtn.addEventListener('click', function() {
                addRow();
            });

            tableBody.addEventListener('click', function(e) {
                var btn = e.target.closest('.js-remove-price-row');
                if (!btn) return;
                var row = btn.closest('tr');
                if (row) row.remove();
            });

            if (tableBody.querySelectorAll('tr').length === 0) {
                addRow();
            }
        })();
    </script>
@endpush

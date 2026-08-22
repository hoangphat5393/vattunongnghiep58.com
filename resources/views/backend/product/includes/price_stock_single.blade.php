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
    </div>
</div>

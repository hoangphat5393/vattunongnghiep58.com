<div class="card">
    <div class="card-header">Giá và kho</div> <!-- /.card-header -->
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group row">
                    <label for="price" class="title_txt col-form-label col-md-4">Giá</label>
                    <div class="col-md-8">
                        <input type="text" id="display_price" value="{{ $price ?? '' }}" class="form-control">
                        <input type="hidden" name="price" id="price" value="{{ $price ?? '' }}" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group row">
                    <label for="unit_qly" class="title_txt col-form-label col-md-4">Đơn vị tính</label>
                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control" name="unit_qly" value="{{ $unit_qly ?? '' }}">
                            <select class="form-control" id="unit" name="unit">
                                @foreach ($listUnit as $unit_item)
                                    <option value="{{ $unit_item }}" {{ $unit == $unit_item ? 'selected' : '' }}>{{ $unit_item }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group row">
                    <label for="stock" class="title_txt col-form-label col-md-4">Kho</label>
                    <div class="col-md-8">
                        <input type="number" name="stock" id="stock" value="{{ $stock ?? 0 }}" class="form-control">
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group row">
                    <label for="total_price" class="title_txt col-form-label col-md-4">Tổng giá</label>
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="text" name="total_price" id="total_price" class="form-control" disabled>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group row">
                    <label for="min_quantity" class="title_txt col-form-label col-md-4">Số lượng mua tối thiểu</label>
                    <div class="col-md-8">
                        <input type="number" name="min_quantity" id="min_quantity" value="{{ $min_quantity ?? 1 }}" class="form-control">
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group row">
                    <label for="min_increase" class="title_txt col-form-label col-md-4">Sô lượng tăng tối thiểu</label>
                    <div class="col-md-8">
                        <input type="number" name="min_increase" id="min_increase" min="1" {{ isset($stock) && $stock ? 'max=' . $stock : '' }} value="{{ $min_increase ?? 1 }}" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <div class="card">
            <div class="card-header">Khuyến mãi</div>
            <div class="card-body">
                @if (isset($id) && $id > 0)
                    <!-- variable -->
                    @include('admin.product.includes.custom_fields', ['product_id' => $id])
                    <!-- variable -->
                @else
                    <p style="color: rgb(49, 38, 38);">Please click save product to use this function</p>
                @endif
            </div>
        </div>

        {{-- <hr> --}}

        <div class="form-group col-lg-8">
            <div class="row">
                <label for="gra_delivery_time" class="title_txt col-form-label col-md-6 px-md-1">Thời gian giao hàng</label>
                <div class="col-md-6">
                    <input type="text" name="gra_delivery_time" id="gra_delivery_time" value="{{ $gra_delivery_time ?? '' }}" class="form-control" placeholder="1h">
                </div>
            </div>
        </div>

        <div class="form-group col-lg-8">
            <div class="row">
                <label for="delivery_method" class="title_txt col-form-label col-md-6 px-md-1">Phương thức giao hàng</label>
                <div class="col-md-6">
                    <input type="text" name="delivery_method" id="delivery_method" value="{{ $delivery_method ?? '' }}" class="form-control" placeholder="Car, Struck">
                </div>
            </div>
        </div>

    </div>
</div>

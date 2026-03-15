<div class="card">
    <div class="card-header">
        Price & Stock | Giá và kho
    </div> <!-- /.card-header -->

    <div class="card-body">
        <div class="row row-cols-3">
            <div class="col">
                <div class="form-group">
                    <div class="custom-control custom-radio">
                        <input type="radio" id="price_type1" class="custom-control-input" name="price_type" value="price" @if ($price_type == 'price') checked @endif>
                        <label for="price_type1" class="custom-control-label">Giá</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input type="radio" id="price_type2" class="custom-control-input" name="price_type" value="contact" @if ($price_type == 'contact') checked @endif>
                        <label for="price_type2" class="custom-control-label">Liên hệ</label>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="form-group row">
                    <label for="price" class="title_txt col-form-label col-md-4">Price</label>
                    <div class="col-md-8">
                        <input type="text" name="price" id="price" value="{{ $price ?? '' }}" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="form-group row">
                    <label for="unit" class="col-form-label col-md-4">Price Unit</label>
                    <div class="col-md-8">
                        <input type="text" name="unit" id="unit" value="{{ $unit ?? '' }}" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="form-group col-lg-6">
                <div class="row">
                    <label for="promotion" class=" col-form-label col-md-4 px-md-1">Promotion</label>
                    <div class="col-md-8">
                        <input type="text" name="promotion" id="promotion" value="{{ $promotion ?? '' }}" class="form-control">
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-lg-6">
                <div class="row">
                    <label for="date_start" class="title_txt col-form-label col-md-4 px-md-1">Start date</label>
                    <div class="col-md-8">
                        <input type="text" name="date_start" id="date_start" value="{{ $date_start ?? '' }}" class="form-control" autocomplete="off">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-lg-6">
                <div class="row">
                    <label for="date_end" class="title_txt col-form-label col-md-4 px-md-1">End date</label>
                    <div class="col-md-8">
                        <input type="text" name="date_end" id="date_end" value="{{ $date_end ?? '' }}" class="form-control" autocomplete="off">
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-lg-6">
                <div class="form-group row">
                    <label for="stock" class="col-form-label col-md-4">Stock</label>
                    <div class="col-md-8">
                        <input type="text" name="stock" id="stock" value="{{ $stock ?? '' }}" class="form-control">
                    </div>
                </div>
            </div>
            {{-- <div class="col-lg-6">
                <div class="form-group row">
                    <label for="sku" class="col-form-label col-md-4">Item no.</label>
                    <div class="col-md-8">
                        <input type="text" name="sku" id="sku" value="{{ $sku ?? '' }}" class="form-control">
                    </div>
                </div>
            </div> --}}
            {{-- <div class="col-lg-6">
                <div class="form-group row">
                    <label for="sort" class="col-form-label col-md-4">Priority</label>
                    <div class="col-md-8">
                        <input type="text" name="sort" id="sort" value="{{ $sort ?? '' }}" class="form-control">
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
</div>

@push('scripts')
    {{-- <script>
        $('#display_price').on('focusin, focusout', function() {
            val = $(this).val();
            val_format = number_format($(this).val(), 0, ',', '.');
            $('#price').val(val);
            $(this).val(val_format);
        });
    </script> --}}
@endpush

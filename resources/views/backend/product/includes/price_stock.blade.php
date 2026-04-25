<div class="mb-4 card">

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

                    <label for="promotion" class="col-form-label col-md-4 px-md-1">Promotion</label>

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
                                    <input type="text" class="form-control" name="prices[{{ $i }}][label]" value="{{ $pp->label ?? '' }}">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="prices[{{ $i }}][price]" value="{{ $pp->price ?? '' }}">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="prices[{{ $i }}][unit]" value="{{ $pp->unit ?? '' }}">
                                </td>
                                <td class="text-center">
                                    <input type="radio" name="prices_default" value="{{ $i }}" @if ($i == $default_index) checked @endif>
                                </td>
                                <td class="text-center">
                                    <input type="hidden" name="prices[{{ $i }}][status]" value="0">
                                    <input type="checkbox" value="1" @if (($pp->status ?? 1) == 1) checked @endif onchange="this.previousElementSibling.value = this.checked ? 1 : 0;">
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-danger js-remove-price-row">Xóa</button>
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
    {{-- <script>

        $('#display_price').on('focusin, focusout', function() {

            val = $(this).val();

            val_format = number_format($(this).val(), 0, ',', '.');

            $('#price').val(val);

            $(this).val(val_format);

        });

    </script> --}}


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

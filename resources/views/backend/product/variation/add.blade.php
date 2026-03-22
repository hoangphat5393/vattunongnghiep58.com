@php
    $variables = App\Models\Variable::where('status', 0)->where('parent', 0)->orderBy('stt', 'asc')->get();

    $id = $variable_id ?? 0;
    $product_attr = '';
    $variable_gallery = [];
    if ($id > 0) {
        $product_attr = App\Models\ThemeVariable::find($id);
        $variable_gallery = $product_attr->image ? unserialize($product_attr->image) : '';
    }

@endphp
<!-- Modal -->
<div class="modal fade" id="AddVariation" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Thêm biến thể</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('admin.product.variation.post') }}" id="form-AddVariation">
                    <input type="hidden" name="product_id" value="{{ $product_id ?? '' }}">
                    <input type="hidden" name="id" value="{{ $id }}">
                    <div class="list-content-loading">
                        <div class="half-circle-spinner">
                            <div class="circle circle-1"></div>
                            <div class="circle circle-2"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        @foreach ($variables as $index => $variable)
                            <div class="col-md-4">
                                <label>{{ $variable->name }} *</label>
                                @if (count($variable->get_child) > 0)
                                    <select id="attribute-{{ $variable->slug }}" name="attribute_sets[{{ $variable->id }}]" class="form-control">
                                        <option value="">Lựa chọn</option>
                                        @foreach ($variable->get_child as $child)
                                            @if ($index == 0)
                                                @php
                                                    $selected = $product_attr && $product_attr->variable_id == $child->id ? 'selected' : '';
                                                @endphp
                                                <option value="{{ $child->id }}" {{ $selected }}>{{ $child->name }}</option>
                                            @else
                                                @php
                                                    if ($product_attr) {
                                                        $attr = \App\Models\ThemeVariable::where('variable_id', $child->id)->where('parent', $product_attr->id)->first();

                                                        $selected = $attr && $attr->variable_id == $child->id ? 'selected' : '';
                                                    }
                                                @endphp
                                                <option value="{{ $child->id }}" {{ $selected ?? '' }}>{{ $child->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="row price-group">
                        <div class="col-md-4">
                            <div class="form-group ">
                                <label class="text-title-field">Mã sản phẩm</label>
                                <input class="form-control" id="sku" data-counter="30" name="sku" type="text" value="{{ $product_attr ? $product_attr->sku : '' }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="text-title-field">Giá cơ bản <span class="next-input-add-on next-input__add-on--before">$</span></label>
                                <div class="next-input--stylized">

                                    <input name="price" class="form-control" step="any" type="number" value="{{ $product_attr ? $product_attr->price : 0 }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="text-title-field">
                                    <span>Giá giảm</span>
                                    <span class="next-input-add-on next-input__add-on--before">$</span>
                                </label>
                                <div class="next-input--stylized">
                                    <input name="promotion" class="form-control" type="number" value="{{ $product_attr ? $product_attr->promotion : '' }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <!--Post Gallery-->
                        <div class="form-group">
                            <label>Post Gallery</label>
                            <div class="content_gallery_list_images">
                                <div class="content_add_item_images ">
                                    @php $type_gallery = 'variable'; @endphp
                                    @if (empty($variable_gallery))
                                        @include('admin.partials.gallery-item', ['type' => $type_gallery])
                                    @else
                                        @foreach ($variable_gallery as $index => $gallery)
                                            @include('admin.partials.gallery-item', compact('index', 'gallery', 'type_gallery'))
                                        @endforeach
                                    @endif
                                </div><!--content_add_item_images-->
                                <div class="clearfix add_link text-end">
                                    <button type="button" class="btn btn-primary add_part_images">+ Add Image</button>
                                </div>
                            </div>
                        </div>
                        <!--End Post Gallery-->
                    </div>

                    <div class="row">
                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Hủy bỏ</button>
                            <button type="button" class="btn btn-info btn-sm add-submit">Lưu thay đổi</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function($) {
        $(document).on('click', '.ckfinder-popup', function() {
            var id = $(this).attr('id'),
                input = $(this).attr('data'),
                view_img = $(this).data('show');
            selectFileWithCKFinder(input, view_img);
        })
        $('#AddVariation').modal('show');
        $('#AddVariation').on('hidden.bs.modal', function(e) {
            $('#AddVariation').remove();
        });

        var form_add = $('#form-AddVariation');
        $('.add-submit').click(function(event) {
            // form_add.find('.list-content-loading').show();

            var form_ = document.getElementById('form-AddVariation');
            var fdnew = new FormData(form_);

            axios({
                method: 'post',
                url: form_add.prop("action"),
                data: fdnew,
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(res => {
                if (res.data.error == 0) {
                    $('#AddVariation').modal('hide');
                    $('.list-variation').html(res.data.view);
                }

            }).catch(e => console.log(e));
        });
    });
</script>

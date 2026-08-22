@extends('backend.layouts.master')

@php
    if (isset($product)) {
        $product_detail = $product;
    }
    if (isset($product_detail)) {
        $product_info = $product_detail->getInfo;
        if ($product_info != '') {
            extract($product_info->toArray());
        }
        $attrs = $product_detail->getAttributes();
        $galleryRaw = $attrs['gallery'] ?? null;
        extract($attrs);

        $gallery = [];
        if (is_array($galleryRaw)) {
            $gallery = $galleryRaw;
        } elseif (is_string($galleryRaw) && $galleryRaw !== '') {
            $unserialized = @unserialize($galleryRaw);
            if ($unserialized !== false || $galleryRaw === 'b:0;') {
                $gallery = is_array($unserialized) ? $unserialized : [];
            } else {
                $decoded = json_decode($galleryRaw, true);
                $gallery = is_array($decoded) ? $decoded : [];
            }
        }
    }
    $title_head = isset($name) && $name !== '' ? $name : __('admin.add_product');

    $id = $id ?? 0;

    $date_update = $updated_at ?? date('Y-m-d H:i:s');

    $spec_short = $spec_short ?? '';

    $price_type = $price_type ?? 'price';
@endphp

@section('seo')
    @php
        $seo = [
            'title' => $title_head . ' | ' . setting_option('seo-title-add'),
            'keywords' => setting_option('seo-keywords-add'),
            'description' => setting_option('seo-description-add'),
            'og_title' => $title_head . ' | ' . setting_option('seo-title-add'),
            'og_description' => setting_option('seo-description-add'),
            'og_url' => Request::url(),
            'og_img' => asset('assets/images/logo_seo.png'),
            'current_url' => Request::url(),
            'current_url_amp' => '',
        ];
    @endphp
    @include('backend.partials.seo')
@endsection

@section('content')
    {{-- Page header --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0">{{ $title_head }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb" class="float-sm-end"><ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('admin.home')</a></li>
                        <li class="breadcrumb-item active">{{ $title_head }}</li>
                    </ol></nav>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    {{-- END Page header --}}

    <!-- Main content -->
    <div class="app-content">
        <div class="container-fluid">
            <form action="{{ $id > 0 ? route('admin.product.update', $id) : route('admin.product.store') }}" method="POST" id="frm-create-product" enctype="multipart/form-data">
                @if ($id > 0)
                    @method('PUT')
                @endif
                <input type="hidden" name="id" value="{{ $id ?? 0 }}">
                @csrf
                <div class="row">
                    <div class="col-md-9">
                        <div class="mb-4 card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">{{ $title_head }}</h3>
                            </div> <!-- /.card-header -->
                            <div class="card-body">

                                <!-- show error form -->
                                @if ($errors->any())
                                    <div class="alert alert-danger" role="alert">
                                        <ul class="mb-0 ps-3">
                                            @foreach ($errors->all() as $err)
                                                <li>{{ $err }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="mb-2 js-validation-messages small" role="alert"></div>

                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="vi" role="tabpanel" aria-labelledby="vi-tab">
                                        <div class="mb-3 form-group">
                                            <label for="slug" class="form-label">Slug</label>
                                            <input type="text" class="form-control slug_slugify" id="slug" name="slug" placeholder="Slug" value="{{ $slug ?? '' }}">
                                            @if ($id > 0)
                                                <p><b style="color: #0000cc;">Link:</b>
                                                    <u><i><a style="color: #F00;" href="{{ route('product.detail', [$slug, $id]) }}" target="_blank">{{ route('product.detail', [$slug, $id]) }}</a></i></u>
                                                </p>
                                            @endif
                                        </div>

                                        <div class="mb-3 form-group">
                                            <label for="title" class="form-label">Tên sản phẩm</label>
                                            <input type="text" class="form-control title_slugify" id="title" name="name" placeholder="Tiêu đề" value="{{ $name ?? '' }}">
                                        </div>
                                        <hr>
                                        @php
                                            $quote_arr = ['id' => 'description', 'label' => 'Trích dẫn', 'name' => 'description', 'description' => $description ?? ''];
                                            $content_arr = ['id' => 'content', 'label' => 'Nội dung', 'name' => 'content', 'content' => $content ?? ''];
                                        @endphp
                                        @include('backend.partials.quote', $quote_arr)
                                        @include('backend.partials.content', $content_arr)
                                        <hr class="my-4">
                                        <div class="mb-3 form-group">
                                            <label for="sort" class="form-label font-weight-bold">Độ ưu tiên</label>
                                            <input type="number" name="sort" id="sort" value="{{ $sort ?? 0 }}"
                                                class="form-control" placeholder="0" style="max-width: 250px;">
                                            <small class="form-text text-muted">Số càng lớn sản phẩm càng được ưu tiên hiển thị trước</small>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- /.card-body -->
                        </div><!-- /.card -->

                        {{-- Gallery --}}
                        @include('backend.partials.galleries', ['gallery_images' => $gallery ?? ''])
                        {{-- End Gallery --}}

                        @include('backend.product.includes.price_stock_multi', ['price_type' => $price_type])
                    </div>

                    <div class="col-md-3">

                        @include('backend.partials.action_button')

                        {{-- SELECT CATEGORY --}}
                        <div class="mb-4 card card-secondary card-outline widget-category">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Chuyên mục</h4>
                            </div>
                            <div class="card-body max-vh-75">
                                <div class="inside clearfix">
                                    @php
                                        $array_checked = isset($product_detail) ? $product_detail->categories->pluck('id')->toArray() : [];
                                        $category_type = 'product';
                                    @endphp
                                    @include('backend.partials.category-item', ['categories' => $categoryTree ?? collect(), 'childrenMap' => $childrenMap ?? collect()])
                                </div>
                            </div>
                        </div>
                        {{-- END SELECT CATEGORY --}}

                        {{-- UPLOAD IMAGE --}}

                        @include('backend.partials.image', ['title' => 'Ảnh đại diện', 'id' => 'img', 'name' => 'image', 'image' => $image ?? ''])
                        {{-- @include('backend.partials.image', ['title' => 'Ảnh đại diện', 'id' => 'cover-img', 'name' => 'cover', 'image' => $cover ?? '']) --}}
                        {{-- END UPLOAD IMAGE --}}

                    </div> <!-- /.col-9 -->
                </div> <!-- /.row -->

                {{-- SEO --}}
                <div class="row">
                    <div class="col-12 col-md-9">
                        @include('backend.partials.form-seo')
                    </div>
                </div>
                {{-- END SEO --}}
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        editor('content');
        editorQuote('description');

        // auto check parrent
        $('#muti_menu_post input').each(function(index, el) {
            if ($(this).is(':checked')) {
                $(this).closest('.sub-menu').parent().find('label').first().find('input').prop('checked', true);
            }
        });

        // $('#display_price').on('focusin, focusout', function() {
        //     val = $(this).val();
        //     $('#price').val(val);
        //     total_price();
        // });

        // const total_price = () => {
        //     var price = parseFloat($('#price').val());
        //     var stock = parseFloat($('#stock').val());

        //     decimals = 2;
        //     if (price.toString().split(".").length > 1)
        //         var decimals = price.toString().split(".")[1].length;

        //     // var total_price = (parseFloat(price) * stock).toFixed(coutfixed);

        //     var total_price = number_format(price * stock, decimals, '.', ',');

        //     // console.log(arr.length);
        //     $('#total_price').val(total_price);
        // };

        // $('#price, #stock').on('change', () => {
        //     total_price();
        // });
        // total_price();


        $(function() {
            validate_form();

            function validate_form() {

                //xử lý validate
                $("#frm-create-product").validate({
                    errorLabelContainer: '#frm-create-product .js-validation-messages',
                    // errorPlacement: function(error, element) {
                    //     var place = element.closest('.form-group');
                    //     if (!place.get(0)) {
                    //         place = element;
                    //     }
                    //     if (place.get(0).type === 'checkbox') {
                    //         place = element.parent();
                    //     }
                    //     if (error.text() !== '') {
                    //         place.before(error);
                    //     }
                    //     console.log(error, element)
                    // },
                    rules: {
                        name: "required",
                        price: {
                            required: function() {
                                return $('input:radio[name="price_type"]:checked').val() == 'price';
                            },
                            number: function() {
                                return $('input:radio[name="price_type"]:checked').val() == 'price';
                            },
                        },
                        'category_id[]': {
                            required: true,
                            minlength: 1,
                        },
                    },
                    messages: {
                        name: "Nhập tên sản phẩm",
                        price: {
                            required: "Nhập giá",
                            number: "Giá phải là số",
                        },
                        'category_id[]': "Chọn ít nhất một chuyên mục",
                    },
                    errorElement: 'div',
                    invalidHandler: function(event, validator) {
                        $('html, body').animate({
                            scrollTop: 0
                        }, 500);
                    }
                });
            }
        });
    </script>
@endpush

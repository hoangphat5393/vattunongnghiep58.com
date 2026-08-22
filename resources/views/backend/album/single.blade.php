@extends('backend.layouts.master')
@php
    $lc = app()->getLocale();
    if (isset($album)) {
        extract($album->getAttributes());
    }
    $target = '_blank';

    $title_head = isset($name) && $name !== '' ? $name : __('admin.album_detail');
    $id = $id ?? 0;

    if (request()->route()->named('admin.album.create')) {
        $form_action = route('admin.album.store'); // Create news
    } else {
        $form_action = route('admin.album.update', $id); // Update news
    }
@endphp

@section('seo')
    @php
        $seo = [
            'title' => $title_head,
            'keywords' => '',
            'description' => '',
            'og_title' => $title_head,
            'og_description' => '',
            'og_url' => Request::url(),
            'og_img' => asset('assets/images/logo_seo.png'),
            'current_url' => Request::url(),
            'current_url_amp' => '',
        ];
    @endphp
    @include('backend.partials.seo')
@endsection

@section('content')
    {{-- begin::App Content Header --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0">{{ $title_head }}</h1>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb" class="float-sm-end"><ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('admin.home')</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $title_head }}</li>
                    </ol></nav>
                </div>
            </div>
        </div>
    </div>
    {{-- end::App Content Header --}}

    {{-- begin::App Content --}}
    <div class="app-content">
        <div class="container-fluid">
            <form id="formEdit" action="{{ $form_action }}" method="POST" enctype="multipart/form-data">
                @if (!request()->route()->named('admin.album.create'))
                    @method('PUT')
                @endif
                @csrf

                <div class="row">
                    <div class="col-lg-9 order-2 order-lg-1">

                        {{-- card --}}
                        <div class="card card-primary card-outline mb-4">

                            {{-- header --}}
                            <div class="card-header">
                                <h3 class="card-title">{{ $title_head }}</h3>
                            </div>

                            <div class="card-body">
                                @if ($id)
                                    <p class="mb-3">Shortcode: <span style="background: #f1f1f1; display: inline-block; padding: 3px">[Album id="{{ $id }}" items="4"]</span></p>
                                @endif

                                {{-- show error form --}}
                                <div class="js-validation-messages mb-2 small" role="alert"></div>

                                <ul class="nav nav-tabs d-none" id="tabLang" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="vi-tab" data-bs-toggle="tab" href="#vi" role="tab" aria-controls="vi" aria-selected="true">@lang('admin.Vietnamese')</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="en-tab" data-bs-toggle="tab" href="#en" role="tab" aria-controls="en" aria-selected="false">@lang('admin.English')</a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="vi" role="tabpanel" aria-labelledby="vi-tab">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">@lang('admin.Title')</label>
                                            <input type="text" class="form-control title_slugify" id="name" name="name" placeholder="@lang('admin.Title')" value="{{ $name ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="en" role="tabpanel" aria-labelledby="en-tab">
                                        <div class="mb-3">
                                            <label for="name_en" class="form-label">@lang('admin.Title')</label>
                                            <input type="text" class="form-control" id="name_en" name="name_en" placeholder="Title" value="{{ $name_en ?? '' }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="sort" class="form-label">@lang('admin.Sort')</label>
                                    <input type="text" class="form-control" id="sort" name="sort" placeholder="Thứ tự" value="{{ $sort ?? 0 }}">
                                </div>

                            </div> <!-- /.card-body -->
                        </div><!-- /.card -->
                    </div> <!-- /.col-9 -->

                    <div class="col-lg-3 order-1 order-lg-2">
                        @include('backend.partials.action_button')
                    </div>
                </div>
                {{-- end::row --}}
            </form>


            {{-- Image list --}}
            <div class="row">
                <div class="col-lg-9">
                    @if ($id == 0)
                        <p style="color: #f00;">Please update to add images</p>
                    @else
                        <div class="card card-info card-outline mb-4">
                            <div class="card-header">
                                <h3>@lang('admin.Image list')</h3>
                            </div>

                            <div class="card-body">

                                <div class="mb-3 text-end">
                                    <button type="button" id="openCKFinder" class="btn btn-outline-primary me-2" data-parent="{{ $id }}"><i class="fa-regular fa-image"></i> @lang('admin.add image')</button>
                                    <button type="button" class="btn btn-outline-info create-item me-2" data="0" data-parent="{{ $id }}"><i class="fa-regular fa-images"></i> @lang('admin.add images')</button>
                                    <button type="button" class="btn btn-outline-danger delete-item" data="0" data-parent="{{ $id }}"><i class="fa-regular fa-trash-list"></i> @lang('admin.delete all')</button>
                                </div>

                                <div class="col-lg-12">
                                    <div class="row border py-2 mb-4">
                                        <div class="col-lg-3 text-center">@lang('admin.image')</div>
                                        <div class="col-lg-3">@lang('admin.name')</div>
                                        <div class="col-lg-3">@lang('admin.link')</div>
                                        <div class="col-lg-3 text-center">@lang('admin.action')</div>
                                    </div>
                                </div>

                                @include('backend.album.includes.album-items', ['album_items' => $album_items])
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            {{-- END SEO --}}
        </div> <!-- /.container-fluid -->
    </div>

    <div class="content-html"></div>

    {{-- Modal --}}
    <div class="modal fade" id="albumItemModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">@lang('admin.Add image')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                </div>

                <div class="modal-footer">
                    <div class="js-validation-messages-modal col-lg-12 small" role="alert"></div>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('admin.Close')</button>
                    <button type="button" class="btn btn-info post-slider" data-parent="{{ $id }}">@lang('admin.Save')</button>
                    <button type="button" class="btn btn-danger update-item" data-id="" data-parent="{{ $id }}">@lang('admin.Update')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // var button1 = document.getElementById('ckfinder-popup-1');
        // var button2 = document.getElementById('ckfinder-popup-2');

        // var width = window.innerWidth > 768 ? '80%' : '100%'; // Sử dụng 80% nếu màn hình rộng hơn 768px, 100% cho màn hình nhỏ hơn
        // var height = window.innerHeight > 600 ? '80%' : '100%'; // Sử dụng 80% nếu chiều cao màn hình lớn hơn 600px, 100% cho màn hình nhỏ hơn

        document.getElementById('openCKFinder').onclick = function() {

            var parent_id = $(this).data("parent");

            CKFinder.modal({
                chooseFiles: true,
                width: 1000,
                height: 600,
                onInit: function(finder) {
                    // finder.on('files:choose', function(evt) {
                    //     var file = evt.data.files.first();
                    //     console.log('Đường dẫn file:', file.getUrl());
                    //     // Xử lý đường dẫn file, ví dụ: hiển thị trong input

                    // });

                    finder.on('files:choose', function(e) {
                        var files = e.data.files;

                        // Mảng để lưu các đường dẫn
                        var fileUrls = [];

                        // Lặp qua danh sách các file và lấy đường dẫn
                        files.forEach(function(file) {
                            fileUrls.push(file.getUrl());
                        });

                        // Gửi danh sách đường dẫn đến máy chủ để lưu vào DB
                        storeFileUrls(fileUrls, parent_id);
                    });

                    finder.on('file:choose:resizedImage', function(evt) {
                        var output = document.getElementById(elementId);
                        output.value = evt.data.resizedUrl;
                        if (view_img != '') $('.' + view_img).attr('src', evt.data.resizedUrl);
                    });
                },
            });
        };

        function storeFileUrls(fileUrls, parent_id) {
            axios
                .post(`{{ url('/admin/album') }}/${parent_id}/storeMultiple`, {
                    fileUrls: fileUrls
                })
                .then(response => {
                    if (response.data.success) {
                        console.log('Lưu file dẫn file thành công.');
                        $("#album_items").html(response.data.view);
                        // $(".slider-list").html(response.data.view);
                    } else {
                        console.error('Lỗi khi lưu đường dẫn file:', response.data.error);
                    }
                })
                .catch(error => {
                    console.error('Lỗi khi gửi yêu cầu:', error);
                });
        }
    </script>

    <script>
        // OPEN FORM TO CREATE NEWS ALBUM ITEMS
        $(document).on("click", ".create-item", function() {

            var parent_id = $(this).data("parent");

            $('.post-slider').removeClass("d-none");
            $('.update-item').addClass("d-none");

            if (parent_id) {
                axios.get(`{{ url('/admin/album') }}/${parent_id}/album_item/create`)
                    .then((res) => {
                        if (res.data.view != "") {
                            $("#albumItemModal .modal-body").html(res.data.view);
                            $("#albumItemModal").modal("show");

                            editorQuote("description");
                            editorQuote("description_en");

                            $(".ckfinder-popup").each(function(index, el) {
                                var id = $(this).attr("id"),
                                    input = $(this).attr("data"),
                                    view_img = $(this).data("show");

                                var button1 = document.getElementById(id);
                                button1.onclick = function() {
                                    selectFileWithCKFinder(input, view_img);
                                };
                            });
                        }
                    })
                    .catch((e) => console.log(e));
            }
        });

        // ALBUM ITEMS STORE NEWS
        $(function() {
            var sliderModal = $("#albumItemModal");

            if (sliderModal.length > 0) {
                $("#form-inserSlider, #form-editSlider").validate({
                    errorLabelContainer: '#albumItemModal .js-validation-messages-modal',
                    onfocusout: false,
                    onkeyup: false,
                    onclick: false,
                    rules: {
                        name: "required",
                        src: "required",
                    },
                    messages: {
                        name: "Vui lòng nhập tên",
                        src: "Chọn hình ảnh",
                    },
                    errorElement: "div",
                    invalidHandler: function(event, validator) {},
                });

                $(document).on("click", ".post-slider", function() {

                    var parent_id = $(this).data("parent");

                    for (instance in CKEDITOR.instances) {
                        CKEDITOR.instances[instance].updateElement();
                    }
                    //rest of your code

                    var type = $(this).attr("data");

                    from = $("#form-editSlider");

                    // var form = document.getElementById('frm-slider');
                    // var fdnew = new FormData(form);

                    if (from.valid()) {
                        var dataString = from.serialize();
                        axios.post(`{{ url('/admin/album') }}/${parent_id}/album_item`, dataString)
                            .then((res) => {
                                if (res.data.view != "") {
                                    // $('#inserSlider form')[0].reset();
                                    $("#albumItemModal").modal("hide");
                                    // $(".slider-list").html(res.data.view);
                                    $("#album_items").html(res.data.view);
                                }
                            })
                            .catch((e) => console.log(e));
                    }
                });
            }
        });

        // EDIT ALBUM ITEMS
        $(document).on("click", ".edit-slider", function() {

            $('.post-slider').addClass("d-none");
            $('.update-item').removeClass("d-none");

            var id = $(this).attr("data"),
                parent = $(this).data("parent");

            if (id) {
                axios.get(`{{ url('/admin/album_item') }}/${id}/edit`)
                    .then((res) => {
                        if (res.data.view != "") {

                            console.log(id);
                            $("#albumItemModal").find('.update-item').attr('data-id', id);

                            $("#albumItemModal .modal-body").html(res.data.view);
                            $("#albumItemModal").modal("show");

                            editorQuote("description");
                            editorQuote("description_en");

                            $(".ckfinder-popup").each(function(index, el) {
                                var id = $(this).attr("id"),
                                    input = $(this).attr("data"),
                                    view_img = $(this).data("show");

                                var button1 = document.getElementById(id);
                                button1.onclick = function() {
                                    selectFileWithCKFinder(input, view_img, id);
                                };
                            });
                        }
                    })
                    .catch((e) => console.log(e));
            }
        });


        // ALBUM ITEMS UPDATE
        $(document).on("click", ".update-item", function() {

            // var parent_id = $(this).data("parent");
            var parent_id = $(this).data("parent");

            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
            //rest of your code

            // var id = $(this).data("id");
            var id = $(this).attr("data-id");
            // console.log('update-item');
            // console.log($(this));
            // console.log(id);

            from = $("#form-editSlider");

            if (from.valid()) {
                var dataString = from.serialize();
                axios.put(`{{ url('/admin/album_item') }}/${id}`, dataString)
                    .then((res) => {
                        if (res.data.view != "") {
                            // $('#inserSlider form')[0].reset();
                            $("#albumItemModal").modal("hide");
                            $("#album_items").html(res.data.view);
                        }
                    })
                    .catch((e) => console.log(e));
            }
        });
    </script>

    <script>
        $(function() {
            //xử lý validate
            $("#formEdit").validate({
                errorLabelContainer: '#formEdit .js-validation-messages',
                rules: {
                    name: "required",
                    name_en: "required",
                },
                messages: {
                    name: "Enter name VI",
                    name_en: "Enter name EN",
                },
                errorElement: 'div',
                invalidHandler: function(event, validator) {
                    $('html, body').animate({
                        scrollTop: 0
                    }, 500);
                }
            });
        });
    </script>
@endpush

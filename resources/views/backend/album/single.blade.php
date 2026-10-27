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
                                    <button type="button" class="btn btn-outline-info create-item me-2" data="0" data-parent="{{ $id }}"><i class="fa-regular fa-image"></i> @lang('admin.add image')</button>
                                    <button type="button" id="openCKFinder" class="btn btn-outline-primary me-2" data-parent="{{ $id }}"><i class="fa-regular fa-images"></i> @lang('admin.add images')</button>
                                    <button type="button" class="btn btn-outline-danger delete-item" data="0" data-parent="{{ $id }}"><i class="fa-solid fa-trash-can"></i> @lang('admin.delete all')</button>
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
    <div class="modal fade" id="albumItemModal" tabindex="-1" aria-labelledby="albumItemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title fs-5 fw-bold" id="albumItemModalLabel">@lang('admin.add image')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                </div>

                <div class="modal-footer bg-light">
                    <div class="js-validation-messages-modal col-lg-12 small text-danger" role="alert"></div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-dismiss="modal">
                        <i class="fa-solid fa-xmark me-1"></i> @lang('admin.close')
                    </button>
                    <button type="button" class="btn btn-info text-white post-slider" data-parent="{{ $id }}">
                        <i class="fa-solid fa-floppy-disk me-1"></i> @lang('admin.save')
                    </button>
                    <button type="button" class="btn btn-primary update-item" data-id="" data-parent="{{ $id }}">
                        <i class="fa-solid fa-check me-1"></i> @lang('admin.update')
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function showAlbumModal() {
            var modalEl = document.getElementById('albumItemModal');
            if (window.bootstrap && bootstrap.Modal) {
                bootstrap.Modal.getOrCreateInstance(modalEl).show();
            } else {
                $("#albumItemModal").modal("show");
            }
        }

        function hideAlbumModal() {
            var modalEl = document.getElementById('albumItemModal');
            if (window.bootstrap && bootstrap.Modal) {
                var inst = bootstrap.Modal.getInstance(modalEl);
                if (inst) {
                    inst.hide();
                }
            }
            $("#albumItemModal").modal("hide");
        }

        window.chooseAlbumImage = function(inputId, imgId) {
            if (typeof CKFinder === 'undefined') {
                console.error('CKFinder is not defined');
                return;
            }

            CKFinder.modal({
                chooseFiles: true,
                width: 1000,
                height: 600,
                onInit: function(finder) {
                    finder.on('files:choose', function(evt) {
                        var file = evt.data.files.first();
                        if (file) {
                            var url = file.getUrl();
                            var inputEl = document.getElementById(inputId);
                            if (inputEl) {
                                inputEl.value = url;
                                $(inputEl).trigger('change');
                            }
                            var imgEl = document.getElementById(imgId);
                            if (imgEl) {
                                imgEl.src = url;
                            }
                            $('#' + imgId).attr('src', url);
                            $('.' + imgId).attr('src', url);
                        }
                    });

                    finder.on('file:choose:resizedImage', function(evt) {
                        var url = evt.data.resizedUrl;
                        var inputEl = document.getElementById(inputId);
                        if (inputEl) {
                            inputEl.value = url;
                            $(inputEl).trigger('change');
                        }
                        var imgEl = document.getElementById(imgId);
                        if (imgEl) {
                            imgEl.src = url;
                        }
                        $('#' + imgId).attr('src', url);
                        $('.' + imgId).attr('src', url);
                    });
                }
            });
        };

        window.clearAlbumImage = function(inputId, imgId) {
            var inputEl = document.getElementById(inputId);
            var imgEl = document.getElementById(imgId);
            var placeholder = '{{ asset('assets/images/placeholder.png') }}';
            if (inputEl) {
                inputEl.value = '';
                $(inputEl).trigger('change');
            }
            if (imgEl) {
                imgEl.src = placeholder;
            }
            $('#' + imgId).attr('src', placeholder);
            $('.' + imgId).attr('src', placeholder);
        };

        $(document).on("click", "#albumItemModal [data-bs-dismiss='modal'], #albumItemModal [data-dismiss='modal'], #albumItemModal .btn-close", function(e) {
            e.preventDefault();
            hideAlbumModal();
        });

        document.getElementById('openCKFinder').onclick = function() {
            var parent_id = $(this).data("parent");

            CKFinder.modal({
                chooseFiles: true,
                width: 1000,
                height: 600,
                onInit: function(finder) {
                    finder.on('files:choose', function(e) {
                        var files = e.data.files;
                        var fileUrls = [];

                        files.forEach(function(file) {
                            fileUrls.push(file.getUrl());
                        });

                        storeFileUrls(fileUrls, parent_id);
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
                        $("#album_items").html(response.data.view);
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
        function showModalError(messages) {
            var alertBox = $("#albumItemModal .modal-error-alert");
            if (alertBox.length) {
                alertBox.find(".modal-error-content").html(messages);
                alertBox.removeClass("d-none");
                $("#albumItemModal .modal-body").animate({ scrollTop: 0 }, 200);
            } else {
                alert(messages.replace(/<br>/g, "\n"));
            }
        }

        function clearModalError() {
            var alertBox = $("#albumItemModal .modal-error-alert");
            alertBox.addClass("d-none");
            alertBox.find(".modal-error-content").html("");
            $("#form-editSlider .form-control").removeClass("is-invalid");
        }

        $(document).on("input change", "#form-editSlider input, #form-editSlider textarea", function() {
            $(this).removeClass("is-invalid");
            if ($("#form-editSlider .form-control.is-invalid").length === 0) {
                $("#albumItemModal .modal-error-alert").addClass("d-none");
            }
        });

        function validateAlbumItemForm() {
            clearModalError();
            var nameInput = $("#form-editSlider input[name='name']");
            var nameVal = (nameInput.val() || "").trim();

            if (!nameVal) {
                nameInput.addClass("is-invalid");
                showModalError("Vui lòng nhập Tiêu đề.");
                nameInput.focus();
                return false;
            }
            return true;
        }

        function handleModalFormError(error) {
            if (error.response && error.response.data) {
                var resData = error.response.data;
                var errorList = [];
                if (resData.errors) {
                    for (var field in resData.errors) {
                        if (resData.errors.hasOwnProperty(field)) {
                            errorList.push(resData.errors[field].join('<br>'));
                            $('#form-editSlider [name="' + field + '"]').addClass('is-invalid');
                        }
                    }
                }
                var msg = errorList.length > 0 ? errorList.join('<br>') : (resData.message || 'Lỗi lưu dữ liệu.');
                showModalError(msg);
            } else {
                showModalError('Có lỗi xảy ra khi kết nối máy chủ.');
            }
        }

        // OPEN FORM TO CREATE NEWS ALBUM ITEMS
        $(document).on("click", ".create-item", function() {
            var parent_id = $(this).data("parent");

            $('.post-slider').removeClass("d-none").prop("disabled", false);
            $('.update-item').addClass("d-none");
            $('#albumItemModalLabel').text("@lang('admin.add image')");

            if (parent_id) {
                axios.get(`{{ url('/admin/album') }}/${parent_id}/album_item/create`)
                    .then((res) => {
                        if (res.data.view != "") {
                            $("#albumItemModal .modal-body").html(res.data.view);
                            clearModalError();
                            showAlbumModal();

                            editorQuote("description");
                            editorQuote("description_en");
                        }
                    })
                    .catch((e) => console.log(e));
            }
        });

        // ALBUM ITEMS STORE NEWS
        $(document).on("click", ".post-slider", function() {
            var parent_id = $(this).data("parent");

            for (var instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }

            if (!validateAlbumItemForm()) {
                return;
            }

            var from = $("#form-editSlider");
            var dataString = from.serialize();
            var btn = $(this);
            btn.prop("disabled", true);

            axios.post(`{{ url('/admin/album') }}/${parent_id}/album_item`, dataString)
                .then((res) => {
                    btn.prop("disabled", false);
                    if (res.data.view != "") {
                        hideAlbumModal();
                        $("#album_items").html(res.data.view);
                    }
                })
                .catch((error) => {
                    btn.prop("disabled", false);
                    handleModalFormError(error);
                });
        });

        // EDIT ALBUM ITEMS
        $(document).on("click", ".edit-slider", function() {
            $('.post-slider').addClass("d-none");
            $('.update-item').removeClass("d-none").prop("disabled", false);
            $('#albumItemModalLabel').text("@lang('admin.edit')");

            var id = $(this).attr("data"),
                parent = $(this).data("parent");

            if (id) {
                axios.get(`{{ url('/admin/album_item') }}/${id}/edit`)
                    .then((res) => {
                        if (res.data.view != "") {
                            $("#albumItemModal").find('.update-item').attr('data-id', id);
                            $("#albumItemModal .modal-body").html(res.data.view);
                            clearModalError();
                            showAlbumModal();

                            editorQuote("description");
                            editorQuote("description_en");
                        }
                    })
                    .catch((e) => console.log(e));
            }
        });

        // ALBUM ITEMS UPDATE
        $(document).on("click", ".update-item", function() {
            for (var instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }

            if (!validateAlbumItemForm()) {
                return;
            }

            var id = $(this).attr("data-id");
            var from = $("#form-editSlider");
            var dataString = from.serialize();
            var btn = $(this);
            btn.prop("disabled", true);

            axios.put(`{{ url('/admin/album_item') }}/${id}`, dataString)
                .then((res) => {
                    btn.prop("disabled", false);
                    if (res.data.view != "") {
                        hideAlbumModal();
                        $("#album_items").html(res.data.view);
                    }
                })
                .catch((error) => {
                    btn.prop("disabled", false);
                    handleModalFormError(error);
                });
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

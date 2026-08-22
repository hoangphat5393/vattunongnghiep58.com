@extends('backend.layouts.master')

@section('seo')
    @php
        $title_head = __('admin.theme_option');
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

@push('style')
    <style>
        .icon_change_postion {
            text-align: center;
            padding-top: 10px;
            margin-right: 10px;
        }

        .container_group_setting {
            background: #f8f8f8 none repeat scroll 0 0;
            padding: 20px 10px;
            margin: 0 -10px;
        }

        .posts_tbl_setting {
            margin: 0px -10px 0 -10px;
            padding-top: 20px;
            border-top: 1px solid #e1e1e1;
            padding-left: 10px;
        }

        .posts_tbl_setting #submit_setting {}

        #page_title h3 {
            display: block;
            font-size: 25px;
            line-height: 30px;
            margin: 10px 0 0;
        }

        #post_body_content .content_setting {
            display: block;
            background: #FFF none repeat scroll 0 0;
            border-radius: 4px;
            margin-bottom: 20px;
            padding: 10px 10px 20px;
        }

        .tbl_create_theme_add {
            background-color: #dff0d8;
            border-color: #d6e9c6;
            color: #3c763d;
            line-height: 27px;
            margin: 10px 0px;
            padding: 10px 30px;
            border-radius: 3px;
        }

        .right_item_theme {
            display: block;
            float: left;
            width: 78%;
            padding: 0 5px;
        }

        .left_item_theme {
            display: block;
            float: left;
            width: 22%;
            line-height: 29px;
        }

        .right_item_theme select.select_option_choise {
            height: 28px;
            line-height: 28px;
            padding: 2px 0;
            width: 150px;
            margin-right: 20px;
        }

        .right_item_theme select.select_option_choise option {
            height: 25px;
            line-height: 25px;
            display: block;
            color: #F30;
            margin-top: 3px;
        }

        .create_option_class,
        .tbl_choise_img_set {
            background-color: #0275d8 !important;
            border-color: #0275d8 !important;
            border-radius: 3px;
            border-style: solid;
            border-width: 1px;
            box-sizing: border-box;
            cursor: pointer;
            display: inline-block;
            font-size: 13px;
            height: 28px;
            line-height: 26px;
            margin: 0 5px !important;
            text-align: center;
            color: #fff !important;
            padding: 0 10px 1px;
            text-decoration: none;
            white-space: nowrap;
        }

        .group_item_theme h3.line {
            border-bottom: 1px solid #e1e1e1;
            color: #900;
            font-size: 18px;
            font-weight: 600;
            margin: 0 -10px 0px;
            padding: 20px 30px;
        }

        #create_option {
            line-height: 15px;
        }

        .left_genate {
            width: 30%;
        }

        .left_genate input {
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .right_genate {
            width: 68%;
        }

        .right_genate input.regular-text {
            width: 80%;
            margin-left: 5px;
            margin-right: 5px;
            border-radius: 3px;
            display: block;
            float: left;
            border: 1px solid #ccc;
        }

        .right_genate textarea.regular-area {
            width: 80%;
            border-radius: 3px;
            display: block;
            float: left;
            border: 1px solid #ccc;
            padding: 10px;
        }

        .group_item_auto_theme .group_item_theme {
            display: flex;
            margin-bottom: 10px;
        }
    </style>
@endpush

@section('content')
    {{-- begin::App Content Header --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0">{{ $title_head }}</h1>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb" class="float-sm-end">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('admin.home')</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $title_head }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    {{-- end::App Content Header --}}

    {{-- begin::App Content --}}
    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">

                    {{-- card --}}
                    <div class="card card-primary card-outline mb-4">

                        {{-- header --}}
                        <div class="card-header">
                            <h3 class="card-title">{{ $title_head }}</h3>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('admin.theme-option.post') }}" method="POST" id="frm-theme-option" enctype="multipart/form-data">
                                @csrf
                                <div class="container_group_setting clearfix">
                                    <div class="group_item_auto_theme clearfix">
                                        @php
                                            $settings = App\Models\Setting::orderBy('sort')->get();
                                        @endphp
                                        @if ($settings->count())
                                            <div class="group_item_theme_sort" id="group_item_theme_sort">
                                                @foreach ($settings as $index => $setting)
                                                    @if ($setting->type == 'line')
                                                        <div class="group_item_theme" data-id="{{ $setting->id }}">
                                                            <div class="icon_change_postion">
                                                                <i class="fa fa-sort"></i>
                                                            </div>
                                                            <div class="left_item_theme left_genate">
                                                                <input type="text" class="form-control" value="{{ $setting->name }}" placeholder="@lang('admin.please_enter_name_field')" name="header_option[line][name][]" />
                                                            </div>
                                                            <div class="right_item_theme right_genate">
                                                                <input type="text" class="form-control regular-text" placeholder="@lang('admin.please_enter_value_field')" name="header_option[line][value][]" value="{{ $setting->content }}" />
                                                                <input type="button" class="btn btn-danger button button-secondary tbl_button_delete_clean" value="@lang('admin.delete')" name="delete_tbl">
                                                            </div>
                                                        </div>
                                                    @elseif($setting->type == 'text')
                                                        <div class="group_item_theme" data-id="{{ $setting->id }}">
                                                            <div class="icon_change_postion"><i class="fa fa-sort"></i></div>
                                                            <div class="left_item_theme left_genate">
                                                                <input type="text" class="form-control" value="{{ $setting->name }}" placeholder="@lang('admin.please_enter_name_field')" name="header_option[text][name][]" />
                                                            </div>
                                                            <div class="right_item_theme right_genate">
                                                                <textarea class="form-control regular-area" id="header_option_text_{{ $index }}" name="header_option[text][value][]" rows="5">{!! $setting->content !!}</textarea>
                                                            </div>
                                                            <div class="action">
                                                                <input type="button" class="btn btn-danger button button-secondary tbl_button_delete_clean" value="@lang('admin.delete')" name="delete_tbl">
                                                            </div>
                                                        </div>
                                                    @elseif($setting->type == 'editor')
                                                        <div class="group_item_theme" data-id="{{ $setting->id }}">
                                                            <div class="icon_change_postion"><i class="fa fa-sort"></i></div>
                                                            <div class="left_item_theme left_genate">
                                                                <input type="text" class="form-control" value="{{ $setting->name }}" placeholder="@lang('admin.please_enter_name_field')" name="header_option[editor][name][]" />
                                                            </div>
                                                            <div class="right_item_theme right_genate">
                                                                <textarea class="form-control regular-area" id="header_option_text_{{ $index }}" name="header_option[editor][value][]" rows="5">{!! htmlspecialchars_decode($setting->content) !!}</textarea>
                                                            </div>
                                                            <div class="action">
                                                                <input type="button" class="btn btn-danger button button-secondary tbl_button_delete_clean" value="@lang('admin.delete')" name="delete_tbl">
                                                            </div>
                                                        </div>
                                                        @push('scripts')
                                                            <script>
                                                                editorQuote('header_option_text_{{ $index }}');
                                                            </script>
                                                        @endpush
                                                    @elseif($setting->type == 'img')
                                                        @include('backend.partials.image-inline', [
                                                            'code_name' => $setting->name,
                                                            'name' => $setting->name,
                                                            'image' => $setting->content,
                                                            'id' => 'id_' . $setting->name,
                                                            'data_id' => $setting->id,
                                                        ])
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    {{-- group_item_auto_theme --}}
                                    <div class="tbl_create_theme_add d-flex">
                                        <div class="left_item_theme">
                                            <b><i>@lang('admin.choose_field_create')</i></b>
                                        </div>
                                        <div class="right_item_theme d-flex">
                                            <select name="option_choise_add" class="form-control select_option_choise">
                                                <option value="line">line</option>
                                                <option value="content_editor">@lang('admin.multiline_editor')</option>
                                                <option value="content">@lang('admin.multiline')</option>
                                                <option value="img">@lang('admin.image')</option>
                                            </select>
                                            <button id="create_option" type="button" class="btn btn-primary create_option_class">@lang('admin.create_option')</button>
                                        </div>
                                    </div>

                                </div>


                                <div class="posts_tbl_setting  text-center">
                                    <button id="submit_setting" class="btn btn-primary pull-left" name="submit" type="submit">@lang('admin.save_changes')</button>
                                    <p>
                                        <strong>@lang('admin.use'):</strong> <i style="color: #FF0000;">setting_option('name');</i>
                                    </p>
                                </div>
                            </form>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
    {{-- end::App Content --}}

    {{-- <div class="inlcude-image d-none">
        @include('backend.partials.image-inline')
    </div> --}}
@endsection


@push('scripts')
    <script>
        $(function() {
            $(document).on('submit', '#frm-theme-option', function(e) {
                e.preventDefault();
                var $form = $(this);
                var $btn = $('#submit_setting');
                var originalHtml = $btn.html();

                $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-1"></i> Đang lưu...');

                if (typeof CKEDITOR !== 'undefined') {
                    for (var instance in CKEDITOR.instances) {
                        CKEDITOR.instances[instance].updateElement();
                    }
                }

                var formData = new FormData(this);

                $.ajax({
                    url: $form.attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $btn.prop('disabled', false).html(originalHtml);
                        if (typeof Swal !== 'undefined') {
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3500,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.onmouseenter = Swal.stopTimer;
                                    toast.onmouseleave = Swal.resumeTimer;
                                }
                            });
                            Toast.fire({
                                icon: 'success',
                                title: response.message || 'Cấu hình cài đặt đã được lưu thành công!'
                            });
                        }
                    },
                    error: function(xhr) {
                        $btn.prop('disabled', false).html(originalHtml);
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'error',
                                title: 'Có lỗi xảy ra khi lưu cấu hình!',
                                showConfirmButton: false,
                                timer: 4000
                            });
                        }
                    }
                });
            });

            $(document).on("click", "#create_option", function(event) {
                event.preventDefault();
                var choise_option = $(this).parent().find('.select_option_choise').val();
                var line_html = `
                    <div class="group_item_theme">
                        <div class="icon_change_postion">
                            <i class="fa fa-sort"></i>
                        </div>
                        <div class="left_item_theme left_genate">
                            <input type="text" class="form-control" value="" placeholder="@lang('admin.please_enter_name_field')" name="header_option[line][name][]" />
                        </div>
                        <div class="right_item_theme right_genate">
                            <input type="text" class="form-control regular-text" placeholder="@lang('admin.please_enter_value_field')" name="header_option[line][value][]" value="" />
                            <input type="button" class="btn btn-danger button button-secondary tbl_button_delete_clean" value="@lang('admin.delete')" name="delete_tbl">
                        </div>
                    </div>`;
                var content = `<div class="group_item_theme">
                    <div class="icon_change_postion"><i class="fa fa-sort"></i></div>
                    <div class="left_item_theme left_genate"><input type="text" class="form-control" value="" placeholder="@lang('admin.please_enter_name_field')"  name="header_option[text][name][]" /></div>
                    <div class="right_item_theme right_genate"><textarea class="form-control regular-area" name="header_option[text][value][]" cols="5" rows="5" placeholder="@lang('admin.please_enter_value_field')"></textarea><input type="button" class="btn btn-danger button button-secondary tbl_button_delete_clean" value="@lang('admin.delete')" name="delete_tbl"></div>
                    </div>`;
                var content_editor = `<div class="group_item_theme">
                    <div class="icon_change_postion"><i class="fa fa-sort"></i></div>
                    <div class="left_item_theme left_genate"><input type="text" class="form-control" value="" placeholder="@lang('admin.please_enter_name_field')" name="header_option[editor][name][]" /></div>
                    <div class="right_item_theme right_genate"><textarea class="form-control regular-area" name="header_option[editor][value][]" cols="5" rows="5" placeholder="@lang('admin.please_enter_value_field')"></textarea><input type="button" class="btn btn-danger button button-secondary tbl_button_delete_clean" value="@lang('admin.delete')" name="delete_tbl"></div>
                </div>`;

                var image_input = $('.inlcude-image').find('.group_item_theme').clone();
                var id = 'image',
                    btn_id = image_input.find('button').attr('id'),
                    data_input = image_input.find('button').attr('data'),
                    data_show = image_input.find('button').attr('data-show'),
                    index = $('.group_item_theme').length;

                index = index + 1;
                console.log(index);
                image_input.find('img').attr('src', "{{ asset('assets/images/placeholder.png') }}");
                image_input.find('img').attr('class', data_show + '' + index);
                image_input.find('button').attr('data-show', data_show + '' + index);
                image_input.find('.input_image').val('');
                image_input.find('.input_image').addClass(data_show + '' + index);
                image_input.find('.input_image').attr('id', id + '' + index);
                image_input.find('button').attr('id', btn_id + '' + index);
                image_input.find('button').attr('data', data_input + '' + index);

                switch (choise_option) {
                    case "line":
                        $('.container_group_setting .group_item_theme_sort').append(line_html);
                        break;
                    case "content_editor":
                        $('.container_group_setting .group_item_theme_sort').append(content_editor);
                        break;
                    case "content":
                        $('.container_group_setting .group_item_theme_sort').append(content);
                        break;
                    case "img":
                        $('.container_group_setting .group_item_theme_sort').append(image_input);
                        $(document).on('click', '.ckfinder-popup', function(index, el) {
                            var id = $(this).attr('id'),
                                input = $(this).attr('data'),
                                view_img = $(this).attr('data-show');
                            selectFileWithCKFinder(input, view_img);
                        });
                        break;
                    default:
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'warning',
                            title: "@lang('admin.choose_field_create')",
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                }
            });

            $(document).on("click", ".tbl_button_delete_clean", function(event) {
                event.preventDefault();
                var elem = $(this).closest('.group_item_theme');
                Swal.fire({
                    title: "@lang('admin.delete_confirmation')",
                    text: "{{ strip_tags(__('admin.confirm_delete_option')) }}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: "@lang('admin.btn_yes')",
                    cancelButtonText: "@lang('admin.btn_no')"
                }).then((result) => {
                    if (result.isConfirmed) {
                        elem.remove();
                    }
                });
            });

            $(document).on('change', '.inputimg', function() {
                var filename = $(this).val().replace(/C:\\fakepath\\/i, '')
                $(this).parent().find('.title_img').val(filename);
            });

        });

        // $(".group_item_theme_sort").sortable();
        var sortable = new Sortable(group_item_theme_sort, {
            handle: '.icon_change_postion', // handle's class
            // swap: true, // Enable swap plugin
            // swapClass: 'highlight', // The class applied to the hovered swap item
            animation: 150,
            onEnd: function(evt) {
                var items = sortable.toArray(); // Lấy thứ tự các mục sau khi sắp xếp
                updateOrder(items);
            }
        });

        function updateOrder(items) {
            // Sử dụng Axios để gửi yêu cầu cập nhật thứ tự
            axios.post('{{ route('admin.theme-option.update_sort') }}', {
                    sort: items
                })
                .then(function(response) {
                    console.log('Order updated:', response.data);
                })
                .catch(function(error) {
                    console.error('Error updating order:', error);
                });
        }
    </script>
@endpush

@extends('backend.layouts.master')

@section('seo')
    @php
        $title_head = __('admin.sidebar_menu');
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
    <link rel="stylesheet" href="{{ asset('assets/plugin/nestable/jquery.nestable.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugin/iconpicker/fontawesome-iconpicker.min.css') }}">
@endpush

@section('content')
    @php
        $id = empty($id) ? 0 : $id;
    @endphp

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0">{{ $title_head }}</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid pt-3">
            <div class="row">

                <div class="col-md-6">

                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h3 class="card-title fs-3">{!! $title_head !!}</h3>
                            <div class="card-tools">
                                <a class="btn btn-warning btn-flat menu-sort-save" title="@lang('admin.save')">
                                    <i class="fa fa-save"></i> @lang('admin.save')
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="box-body table-responsive">
                                <div class="dd" id="menu-sort">
                                    <ol class="dd-list">
                                        @php
                                            $menus = \App\Models\Backend\AdminMenu::getListAll()->groupBy('parent_id');
                                        @endphp
                                        {{-- Level 0 --}}
                                        @foreach ($menus[0] as $level0)
                                            @if ($level0->type == 1)
                                                <li class="dd-item " data-id="{{ $level0->id }}">
                                                    <div class="dd-handle header-fix  {{ $level0->id == $id ? 'active-item' : '' }}">
                                                        {!! __($level0->title) !!}
                                                        <span class="float-end dd-nodrag">
                                                            <a href="{{ route('admin.admin-menu.edit', ['id' => $level0->id]) }}"><i class="fa fa-edit"></i></a>
                                                            &nbsp;
                                                            <a data-id="{{ $level0->id }}" class="remove_menu"><i class="fa fa-trash"></i></a>
                                                        </span>
                                                    </div>
                                                </li>
                                            @elseif($level0->uri)
                                                <li class="dd-item" data-id="{{ $level0->id }}">
                                                    <div class="dd-handle {{ $level0->id == $id ? 'active-item' : '' }}">
                                                        <i class="{{ $level0->icon }}"></i> {!! __($level0->title) !!}
                                                        <span class="float-end dd-nodrag">
                                                            <a href="{{ route('admin.admin-menu.edit', ['id' => $level0->id]) }}"><i class="fa fa-edit fa-edit"></i></a>
                                                            &nbsp;
                                                            <a data-id="{{ $level0->id }}" class="remove_menu"><i class="fa fa-trash fa-edit"></i></a>
                                                        </span>
                                                    </div>
                                                </li>
                                            @else
                                                <li class="dd-item " data-id="{{ $level0->id }}">
                                                    <div class="dd-handle {{ $level0->id == $id ? 'active-item' : '' }}">
                                                        <i class="{{ $level0->icon }}"></i> {!! __($level0->title) !!}
                                                        <span class="float-end dd-nodrag">
                                                            <a href="{{ route('admin.admin-menu.edit', ['id' => $level0->id]) }}"><i class="fa fa-edit fa-edit"></i></a>
                                                            &nbsp;
                                                            <a data-id="{{ $level0->id }}" class="remove_menu"><i class="fa fa-trash fa-edit"></i></a>
                                                        </span>
                                                    </div>

                                                    {{-- Level 1 --}}
                                                    @if (isset($menus[$level0->id]) && count($menus[$level0->id]))
                                                        <ol class="dd-list">
                                                            @foreach ($menus[$level0->id] as $level1)
                                                                @if ($level1->uri)
                                                                    <li class="dd-item" data-id="{{ $level1->id }}">
                                                                        <div class="dd-handle {{ $level1->id == $id ? 'active-item' : '' }}">
                                                                            <i class="{{ $level1->icon }}"></i> {!! __($level1->title) !!}
                                                                            <span class="float-end dd-nodrag">
                                                                                <a href="{{ route('admin.admin-menu.edit', ['id' => $level1->id]) }}"><i class="fa fa-edit fa-edit"></i></a>
                                                                                &nbsp;
                                                                                <a data-id="{{ $level1->id }}" class="remove_menu"><i class="fa fa-trash fa-edit"></i></a>
                                                                            </span>
                                                                        </div>
                                                                    </li>
                                                                @else
                                                                    <li class="dd-item" data-id="{{ $level1->id }}">
                                                                        <div class="dd-handle {{ $level1->id == $id ? 'active-item' : '' }}">
                                                                            <i class="{{ $level1->icon }}"></i> {!! __($level1->title) !!}
                                                                            <span class="float-end dd-nodrag">
                                                                                <a href="{{ route('admin.admin-menu.edit', ['id' => $level1->id]) }}"><i class="fa fa-edit fa-edit"></i></a>
                                                                                &nbsp;
                                                                                <a data-id="{{ $level1->id }}" class="remove_menu"><i class="fa fa-trash fa-edit"></i></a>
                                                                            </span>
                                                                        </div>
                                                                        {{-- LEvel 2  --}}
                                                                        @if (isset($menus[$level1->id]) && count($menus[$level1->id]))
                                                                            <ol class="dd-list dd-collapsed">
                                                                                @foreach ($menus[$level1->id] as $level2)
                                                                                    @if ($level2->uri)
                                                                                        <li class="dd-item" data-id="{{ $level2->id }}">
                                                                                            <div class="dd-handle {{ $level2->id == $id ? 'active-item' : '' }}">
                                                                                                <i class="{{ $level2->icon }}"></i> {!! __($level2->title) !!}
                                                                                                <span class="float-end dd-nodrag">
                                                                                                    <a href="{{ route('admin.admin-menu.edit', ['id' => $level2->id]) }}"><i class="fa fa-edit fa-edit"></i></a>
                                                                                                    &nbsp;
                                                                                                    <a data-id="{{ $level2->id }}" class="remove_menu"><i class="fa fa-trash fa-edit"></i></a>
                                                                                                </span>
                                                                                            </div>
                                                                                        </li>
                                                                                    @else
                                                                                        <li class="dd-item" data-id="{{ $level2->id }}">
                                                                                            <div class="dd-handle {{ $level2->id == $id ? 'active-item' : '' }}">
                                                                                                <i class="{{ $level2->icon }}"></i> {!! __($level2->title) !!}
                                                                                                <span class="float-end dd-nodrag">
                                                                                                    <a href="{{ route('admin.admin-menu.edit', ['id' => $level2->id]) }}"><i class="fa fa-edit fa-edit"></i></a>
                                                                                                    &nbsp;
                                                                                                    <a data-id="{{ $level2->id }}" class="remove_menu"><i class="fa fa-trash fa-edit"></i></a>
                                                                                                </span>
                                                                                            </div>
                                                                                        </li>
                                                                                    @endif
                                                                                @endforeach
                                                                            </ol>
                                                                        @endif
                                                                        {{--  end level 2 --}}
                                                                    </li>
                                                                @endif
                                                            @endforeach
                                                        </ol>
                                                    @endif
                                                    {{-- end level 1 --}}
                                                </li>
                                            @endif
                                        @endforeach
                                        {{-- end level 0 --}}

                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="card card-primary card-outline">
                        <div class="card-header align-items-center">
                            <h3 class="card-title fs-3">@lang('admin.admin_menu_form')</h3>
                            @if ($layout == 'edit')
                                <div class="card-tools">
                                    <a href="{{ route('admin.admin-menu.index') }}" class="btn btn-flat btn-danger" title="List">
                                        <i class="fa fa-list"></i><span class="hidden-xs"> {{ __('admin.back to list') }}</span>
                                    </a>
                                </div>
                            @endif
                        </div>

                        <form id="form-main" class="form-horizontal" action="{{ $url_action }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row {{ $errors->has('parent_id') ? ' text-red' : '' }}">
                                    <label for="parent_id" class="col-sm-2 col-form-label">@lang('admin.parent')</label>
                                    <div class="col-sm-10 ">
                                        <select class="form-control parent mb-3" name="parent_id">
                                            <option value="0" {{ old('parent', $menu['parent'] ?? '') == 0 ? 'selected' : '' }}>== ROOT ==</option>
                                            @foreach ($treeMenu as $k => $v)
                                                <option value="{{ $k }}" {{ old('parent', $menu['parent_id'] ?? '') == $k ? 'selected' : '' }}>{!! $v !!}</option>
                                            @endforeach
                                        </select>

                                        @if ($errors->has('parent_id'))
                                            <span class="text-sm">
                                                <i class="fa fa-info-circle"></i> {{ $errors->first('parent_id') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="row {{ $errors->has('title') ? ' text-red' : '' }}">
                                    <label for="title" class="col-sm-2 col-form-label">@lang('admin.title')</label>
                                    <div class="col-sm-10 ">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                            <input type="text" id="title" name="title" value="{!! old() ? old('title') : $menu['title'] ?? '' !!}" class="form-control title {{ $errors->has('title') ? ' is-invalid' : '' }}">
                                        </div>
                                        @if ($errors->has('title'))
                                            <span class="text-sm">
                                                <i class="fa fa-info-circle"></i> {{ $errors->first('title') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="row {{ $errors->has('icon') ? ' text-red' : '' }}">
                                    <label for="icon" class="col-sm-2 col-form-label">@lang('admin.icon')</label>
                                    <div class="col-sm-10 ">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text">
                                                <i class="fas fa-archive picker-target"></i>
                                            </span>
                                            <input type="text" id="icon" class="form-control icp icp-auto {{ $errors->has('icon') ? ' is-invalid' : '' }} " name="icon" value="{!! old() ? old('icon') : $menu['icon'] ?? 'fas fa-bars' !!}" placeholder="@lang('admin.input_icon')">
                                        </div>

                                        @if ($errors->has('icon'))
                                            <span class="text-sm">
                                                <i class="fa fa-info-circle"></i> {{ $errors->first('icon') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="row {{ $errors->has('uri') ? ' text-red' : '' }}">
                                    <label for="uri" class="col-sm-2 col-form-label">@lang('admin.url')</label>
                                    <div class="col-sm-10 ">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text"><i class="fas fa-link"></i></span>
                                            <input type="text" id="uri" name="uri" value="{!! old() ? old('uri') : $menu['uri'] ?? '' !!}" class="form-control uri {{ $errors->has('uri') ? ' is-invalid' : '' }}" placeholder="@lang('admin.input_uri')">
                                        </div>
                                        @if ($errors->has('uri'))
                                            <span class="text-sm">
                                                <i class="fa fa-info-circle"></i> {{ $errors->first('uri') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="row {{ $errors->has('sort') ? ' text-red' : '' }}">
                                    <label for="sort" class="col-sm-2 col-form-label">@lang('admin.sort')</label>
                                    <div class="col-sm-10 ">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text"><i class="fas fa-sort-numeric-down"></i></span>
                                            <input type="number" style="width: 100px;" id="sort" name="sort" value="{!! old() ? old('sort') : $menu['sort'] ?? '' !!}" class="form-control sort {{ $errors->has('sort') ? ' is-invalid' : '' }}" placeholder="@lang('admin.input_sort')">
                                        </div>
                                        @if ($errors->has('sort'))
                                            <span class="text-sm">
                                                <i class="fa fa-info-circle"></i> {{ $errors->first('sort') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="row {{ $errors->has('sort') ? ' text-red' : '' }}">
                                    {{-- <label for="sort" class="col-form-label col-sm-2">Ẩn menu</label> --}}
                                    <div class="col-sm-10 offset-sm-2">
                                        @php $hidden = $menu['hidden'] ?? 0;@endphp
                                        <div class="form-check">
                                            <input type="checkbox" name="hidden" class="form-check-input" id="customCheck_show" value="1" {{ $hidden == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="customCheck_show">@lang('admin.hide_menu')</label>
                                        </div>

                                    </div>
                                </div>

                            </div>

                            <!-- /.card-body -->
                            <div class="card-footer">
                                <div class="float-end">
                                    <button type="submit" class="btn btn-primary">@lang('admin.submit')</button>
                                    <button type="reset" class="btn btn-warning">@lang('admin.reset')</button>
                                </div>
                            </div>

                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>
    </div>

@endsection


@push('scripts')
    {{-- Ediable     --}}
    <script src="{{ asset('assets/plugin/nestable/jquery.nestable.min.js') }}"></script>
    <script src="{{ asset('assets/plugin/iconpicker/fontawesome-iconpicker.min.js') }}"></script>

    <script type="text/javascript">
        $('.remove_menu').click(function(event) {
            var id = $(this).data('id');
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-danger mx-1',
                    cancelButton: 'btn btn-secondary mx-1'
                },
                buttonsStyling: false,
            });

            swalWithBootstrapButtons.fire({
                title: '{{ __('action.delete_confirm') }}',
                text: "",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '{{ __('action.confirm_yes') }}',
                cancelButtonText: '{{ __('action.confirm_no') }}',
                reverseButtons: true,
                showLoaderOnConfirm: true,
                preConfirm: function() {
                    return axios.post('{{ $urlDeleteItem ?? '' }}', {
                            id: id,
                            _token: '{{ csrf_token() }}',
                        })
                        .then(function(response) {
                            const data = response.data;
                            if (data.error == 1) {
                                Swal.showValidationMessage(data.msg || 'Không thể xóa mục này!');
                                return false;
                            }
                            return data;
                        })
                        .catch(function(e) {
                            Swal.showValidationMessage('Lỗi kết nối máy chủ!');
                        });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });
                    Toast.fire({
                        icon: 'success',
                        title: '{{ __('action.delete_confirm_deleted_msg') }}'
                    });
                    setTimeout(function() {
                        window.location.replace('{{ route('admin.admin-menu.index') }}');
                    }, 800);
                }
            });
        });

        $('#menu-sort').nestable();
        $('.menu-sort-save').click(function() {
            var $btn = $(this);
            var originalHtml = $btn.html();
            $btn.prop('disabled', true).addClass('disabled').html('<i class="fa fa-spinner fa-spin"></i> Đang lưu...');

            var serialize = $('#menu-sort').nestable('serialize');
            var menu = JSON.stringify(serialize);
            axios.post('{{ route('admin.admin-menu.update_sort') }}', {
                    _token: '{{ csrf_token() }}',
                    menu: menu
                })
                .then(function(response) {
                    $btn.prop('disabled', false).removeClass('disabled').html(originalHtml);
                    const data = response.data;
                    if (data.error == 0) {
                        if (typeof Swal !== 'undefined') {
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.onmouseenter = Swal.stopTimer;
                                    toast.onmouseleave = Swal.resumeTimer;
                                }
                            });
                            Toast.fire({
                                icon: 'success',
                                title: data.msg || 'Thứ tự Menu Admin đã được lưu thành công!'
                            });
                        } else {
                            alert('Thứ tự Menu Admin đã được lưu thành công!');
                        }
                    } else {
                        if (typeof Swal !== 'undefined') {
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3500,
                                timerProgressBar: true
                            });
                            Toast.fire({
                                icon: 'error',
                                title: data.msg || 'Có lỗi xảy ra khi lưu menu!'
                            });
                        } else {
                            alert(data.msg || 'Có lỗi xảy ra khi lưu menu!');
                        }
                    }
                })
                .catch(function(e) {
                    $btn.prop('disabled', false).removeClass('disabled').html(originalHtml);
                    console.error(e);
                    if (typeof Swal !== 'undefined') {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3500,
                            timerProgressBar: true
                        });
                        Toast.fire({
                            icon: 'error',
                            title: 'Lỗi kết nối máy chủ khi lưu menu!'
                        });
                    } else {
                        alert('Lỗi kết nối máy chủ!');
                    }
                });
        });


        $(document).ready(function() {

            $('.active-item').parents('li').removeClass('dd-collapsed');
            //icon picker
            $('.icp-auto').iconpicker({
                // placement: 'bottomRight',
                animation: false
            });

            $('.iconpicker-item').on('click', function(e) {
                e.preventDefault();
                //do other stuff when a click happens
            });

            $('.icp').on('iconpickerSelected', function(e) {
                $('.lead .picker-target').get(0).className = 'picker-target ' +
                    e.iconpickerInstance.options.iconBaseClass + ' ' +
                    e.iconpickerInstance.options.fullClassFormatter(e.iconpickerValue);
            });
        });
    </script>
@endpush

@extends('backend.layouts.master')
@section('seo')
    @php
        $data_seo = [
            'title' => 'Lọc sản phẩm | ' . setting_option('seo-title-add'),
            'keywords' => setting_option('seo-keywords-add'),
            'description' => setting_option('seo-description-add'),
            'og_title' => 'Lọc sản phẩm | ' . setting_option('seo-title-add'),
            'og_description' => setting_option('seo-description-add'),
            'og_url' => Request::url(),
            'og_img' => asset('assets/images/logo_seo.png'),
            'current_url' => Request::url(),
            'current_url_amp' => '',
        ];
        $seo = WebService::getSEO($data_seo);
    @endphp
    @include('backend.partials.seo')
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0">@lang('admin.filter_products')</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb" class="float-sm-end">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('admin.home')</a></li>
                            <li class="breadcrumb-item active">@lang('admin.filter_products')</li>
                        </ol>
                    </nav>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.app-content-header -->
    <!-- Main content -->
    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="mb-4 card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">@lang('admin.filter_products')</h3>
                        </div> <!-- /.card-header -->
                        <div class="card-body">
                            <div class="clearfix">
                                <ul class="nav float-start">
                                    <li class="nav-item">
                                        <a class="btn btn-danger" onclick="delete_id('product')" href="javascript:void(0)"><i class="fas fa-trash"></i> Delete</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="btn btn-primary" href="{{ route('admin.product.create') }}" style="margin-left: 6px;"><i class="fas fa-plus"></i> Add New</a>
                                    </li>
                                </ul>
                                <div class="float-end">
                                    <form method="GET" action="{{ route('admin.product.index') }}" id="frm-filter-post" class="form-inline">
                                        <?php
                                        $list_cate = \App\Models\Backend\Category::query()
                                            ->orderBy('name')
                                            ->get(['id', 'name']);
                                        ?>
                                        <select class="custom-select me-2" name="category_id">
                                            <option value="">Danh mục sản phẩm</option>
                                            @foreach ($list_cate as $cate)
                                                <option value="{{ $cate->id }}" @if (isset($_GET['category_id']) && $_GET['category_id'] == $cate->id) selected @endif>{{ $cate->name }}</option>
                                            @endforeach
                                        </select>
                                        <input type="text" class="form-control" name="search_title" value="<?php if (isset($_GET['search_title'])) {
                                            echo $_GET['search_title'];
                                        } ?>" id="search_title" placeholder="Từ khoá">
                                        <button type="submit" class="btn btn-primary ms-2" aria-label="Tìm kiếm">
                                            <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <br />
                            <div class="clearfix">
                                <div class="float-start" style="font-size: 17px;">
                                    <b>Tổng</b>: <span class="bold" style="color: red; font-weight: bold;">{{ $total_item }}</span> sản phẩm
                                </div>
                                @if (false)
                                    <div class="float-end">
                                        {!! $data_product->appends(request()->except('page'))->links() !!}
                                    </div>
                                @endif
                            </div>
                            <br />
                            <div class="table-responsive">
                                <table class="table table-bordered" id="table_index">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center"><input type="checkbox" id="selectall" onclick="select_all()"></th>
                                            <th scope="col" class="text-center">Title</th>
                                            <th scope="col" class="text-center">Thumbnail</th>
                                            <th scope="col" class="text-center">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data_product as $data)
                                            <tr>
                                                <td class="text-center"><input type="checkbox" id="{{ $data->id }}" name="seq_list[]" value="{{ $data->id }}"></td>
                                                <td class="text-center" style="width: 250px;">
                                                    <a class="row-title" href="{{ route('admin.product.edit', [$data->id]) }}">
                                                        <b>{{ $data->title }}</b>
                                                        <br>
                                                        <b style="color:#c76805;">{{ $data->slug }}</b>
                                                        @if (isset($data->categories) && $data->categories->isNotEmpty())
                                                            <div class="list_cat_post_content_link">
                                                                @foreach ($data->categories as $category)
                                                                    <a class="tag" target="_blank" href="#">{{ $category->name }}</a>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    @if ($data->thubnail != '')
                                                        <img src="{{ asset('uploads/product/' . $data->thubnail) }}" style="height: 70px;">
                                                    @else
                                                        <img src="{{ asset('assets/images/placeholder.png') }}">
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    {{ $data->created }}
                                                    <br>
                                                    @if ($data->status == 0)
                                                        Public
                                                    @else
                                                        Draft
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div> <!-- /.card-body -->

                        @if (false)
                            <div class="float-end">
                                {!! $data_product->appends(request()->except('page'))->links() !!}
                            </div>
                        @endif

                        {{ $data_product->appends(request()->except('page'))->links('backend.pagination.custom') }}
                    </div><!-- /.card -->
                </div> <!-- /.col -->
            </div> <!-- /.row -->
        </div> <!-- /.container-fluid -->
    </div>
@endsection

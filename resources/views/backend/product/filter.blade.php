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
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Lọc sản phẩm</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Lọc sản phẩm</li>
                    </ol>
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
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Lọc sản phẩm</h3>
                        </div> <!-- /.card-header -->
                        <div class="card-body">
                            <div class="clear">
                                <ul class="nav fl">
                                    <li class="nav-item">
                                        <a class="btn btn-danger" onclick="delete_id('product')" href="javascript:void(0)"><i class="fas fa-trash"></i> Delete</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="btn btn-primary" href="{{ route('admin.product.create') }}" style="margin-left: 6px;"><i class="fas fa-plus"></i> Add New</a>
                                    </li>
                                </ul>
                                <div class="fr">
                                    <form method="GET" action="{{ route('admin.product.index') }}" id="frm-filter-post" class="form-inline">
                                        <?php
                                        $list_cate = App\Models\Category_Theme::orderBy('category_theme.categoryName', 'ASC')->select('category_theme.categoryID', 'category_theme.categoryName')->get();
                                        ?>
                                        <select class="custom-select me-2" name="category_theme">
                                            <option value="">Thể loại sản phẩm</option>
                                            @foreach ($list_cate as $cate)
                                                <option value="{{ $cate->categoryID }}" @if (isset($_GET['category_theme']) && $_GET['category_theme'] == $cate->categoryID) selected @endif>{{ $cate->categoryName }}</option>
                                            @endforeach
                                        </select>
                                        <input type="text" class="form-control" name="search_title" value="<?php if (isset($_GET['search_title'])) {
                                            echo $_GET['search_title'];
                                        } ?>" id="search_title" placeholder="Từ khoá">
                                        <button type="submit" class="btn btn-primary ms-2">Tìm kiếm</button>
                                    </form>
                                </div>
                            </div>
                            <br />
                            <div class="clear">
                                <div class="fl" style="font-size: 17px;">
                                    <b>Tổng</b>: <span class="bold" style="color: red; font-weight: bold;">{{ $total_item }}</span> sản phẩm
                                </div>
                                <div class="fr">
                                    {!! $data_product->appends(request()->except('page'))->links() !!}
                                </div>
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
                                                        <?php
                                                $categories = \App\Models\Theme::where('theme.id', '=', $data->id)
                                                    ->join('join_category_theme','theme.id','=','join_category_theme.id_theme')
                                                    ->join('category_theme','join_category_theme.id_category_theme','=','category_theme.categoryID')
                                                    ->select('category_theme.categoryID','category_theme.categoryName','category_theme.categorySlug')
                                                    ->orderBy('category_theme.categoryParent','ASC')
                                                    ->get(); 
                                                if($categories): ?>
                                                        <div class="list_cat_post_content_link">
                                                            @foreach ($categories as $category)
                                                                <a class="tag" target="_blank" href="#">{{ $category->categoryName }}</a>
                                                            @endforeach
                                                        </div>
                                                        <?php endif; ?>
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
                            <div class="fr">
                                {!! $data_product->appends(request()->except('page'))->links() !!}
                            </div>
                        </div> <!-- /.card-body -->
                    </div><!-- /.card -->
                </div> <!-- /.col -->
            </div> <!-- /.row -->
        </div> <!-- /.container-fluid -->
    </div>
@endsection

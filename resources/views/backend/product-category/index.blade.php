@extends('backend.layouts.master')
@section('seo')
    @php
        $title_head = 'Sản phẩm';
        $seo = [
            'title' => $title_head . ' | ' . setting_option('seo-title-add'),
            'keywords' => setting_option('seo-keywords-add'),
            'description' => setting_option('seo-description-add'),
            'og_title' => 'List Category Product | ' . setting_option('seo-title-add'),
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
    <!-- Content Header (Page header) -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">{{ $title_head }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <div class="card-header">
                            <h5>Danh sách thể loại sản phẩm</h5>
                        </div>

                        <div class="card-body">

                            <div class="d-flex justify-content-between">
                                @include('backend.partials.button_add_delete', ['type' => 'product-category', 'route' => route('admin.product-category.create')])

                                <div class="float-end mt-3 mt-lg-0">
                                    <form method="GET" action="" id="frm-filter-post" class="form-inline">
                                        <input type="text" class="form-control" name="search_name" id="search_name" placeholder="@lang('admin.Keyword')" value="{{ request('search_name') }}">
                                        <button type="submit" class="btn btn-primary ms-2" aria-label="@lang('admin.Search')">
                                            <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between my-4">
                                <div class="float-start">
                                    <b>@lang('admin.Total')</b>: <span class="fw-bold text-red">{{ $total_item ?? 0 }}</span> @lang('admin.category')
                                </div>
                                <div class="float-end">
                                    {!! $categories->links() !!}
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover v-center" id="table_index">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center">
                                                <div class="icheck-info d-inline">
                                                    <input type="checkbox" id="selectall" onclick="select_all()">
                                                    <label for="selectall">
                                                    </label>
                                                </div>
                                            </th>
                                            <th scope="col" class="text-center" style="width:100px">STT</th>
                                            <th scope="col" class="text-center">Title</th>
                                            <th scope="col" class="text-center">Image</th>
                                            <th scope="col" class="text-center">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($categories->count())
                                            @include('backend.product-category.includes.category_item', ['level' => 0, 'categories' => $categories])
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="float-end">
                                {!! $categories->links() !!}
                            </div>
                        </div> <!-- /.card-body -->
                    </div><!-- /.card -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div><!-- /.app-content -->
@endsection

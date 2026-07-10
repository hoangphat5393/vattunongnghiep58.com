@extends('backend.layouts.master')
@section('seo')
    @php
        $title_head = 'Danh mục sản phẩm';
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
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0">{{ $title_head }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb" class="float-sm-end"><ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">{{ $title_head }}</li>
                    </ol></nav>
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
                    <div class="mb-4 card card-primary card-outline">

                        <div class="card-header">
                            <h3 class="card-title">{{ $title_head }} @lang('admin.list')</h3>
                        </div>

                        <div class="card-body">

                            <div class="d-flex justify-content-between">
                                @include('backend.partials.button_add_delete', ['type' => 'product-category', 'route' => route('admin.product-category.create')])

                                <div class="float-end mt-3 mt-lg-0">
                                    <form method="GET" action="" id="frm-filter-post" class="form-inline">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="search_name" id="search_name" placeholder="@lang('admin.keyword')" value="{{ request('search_name') }}">
                                            <button type="submit" class="btn btn-outline-primary" aria-label="@lang('admin.search')">
                                                <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between my-4">
                                <div class="float-start">
                                    <b>@lang('admin.Total')</b>: <span class="fw-bold text-red">{{ $total_item ?? 0 }}</span> @lang('admin.category')
                                </div>
                                @if (false)
                                    <div class="float-end">
                                        {!! $categories->links() !!}
                                    </div>
                                @endif
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
                                            @include('backend.product-category.includes.category_item', [
                                                'level' => 0,
                                                'categories' => $categories,
                                                'childrenMap' => $childrenMap ?? collect(),
                                            ])
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div> <!-- /.card-body -->

                        @if (false)
                            <div class="float-end">
                                {!! $categories->links() !!}
                            </div>
                        @endif

                        {{-- card-footer --}}
                        {{ $categories->links('backend.pagination.custom') }}
                    </div><!-- /.card -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div><!-- /.app-content -->
@endsection

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
            <div class="row">
                <div class="col-sm-6">
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Product</li>
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
                            <h3 class="card-title">@lang('admin.Products')</h3>
                        </div> <!-- /.card-header -->
                        <div class="card-body">

                            <div class="d-flex justify-content-between mb-3">
                                @include('backend.partials.button_add_delete', ['type' => 'product', 'route' => route('admin.product.create')])
                                <div class="fr mt-3 mt-lg-0">
                                    <form method="GET" action="" id="frm-filter-post" class="d-flex align-items-center">
                                        @php
                                            $categories = App\Models\Backend\Category::select('id', 'name')->orderByDesc('sort')->get();
                                        @endphp
                                        <select class="form-select me-2" name="category_id">
                                            <option value="">@lang('admin.Category')</option>
                                            @foreach ($categories as $item)
                                                <option value="{{ $item->id }}" {{ request('category_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        <input type="text" class="form-control me-2" name="name" id="name" placeholder="@lang('admin.Keyword')" value="{{ request('name') }}">
                                        <button type="submit" class="btn btn-primary">@lang('admin.Search')</button>
                                    </form>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between my-4">
                                <div class="fl">
                                    <b>@lang('admin.Total')</b>: <span class="fw-bold text-red">{{ $total_item ?? 0 }}</span> @lang('admin.Products')
                                </div>
                                <div class="fr">
                                    {!! $products->links() !!}
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered list-data v-center" id="table_index">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width:50px">
                                                <div class="icheck-info d-inline">
                                                    <input type="checkbox" id="selectall" onclick="select_all()">
                                                    <label for="selectall">
                                                    </label>
                                                </div>
                                            </th>
                                            <th scope="col" class="text-center" style="width:100px">Ưu tiên</th>
                                            <th scope="col" class="text-center">Tên sản phẩm</th>
                                            <th scope="col" class="text-center">Hình ảnh</th>
                                            <th scope="col" class="text-center">Danh mục</th>
                                            <th scope="col" class="text-center">Ngày</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($products as $item)
                                            <tr>
                                                <td class="text-center">
                                                    <div class="icheck-info d-inline">
                                                        <input type="checkbox" id="{{ $item->id }}" name="seq_list[]" value="{{ $item->id }}">
                                                        <label for="{{ $item->id }}"></label>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" id="sort" class="form-control quick_change_value text-center" data-id="{{ $item->id }}" data-model="{{ get_class($item) }}" value="{{ $item->sort }}" reload-on-change>
                                                </td>
                                                <td>
                                                    <a class="row-title me-3" href="{{ route('admin.product.edit', [$item->id]) }}">
                                                        <b>{{ $item->name }}</b>
                                                    </a>
                                                    <br>
                                                    <a class="link to-link fw-bold" href="{{ route('product.detail', [$item->slug, $item->id]) }}" target="_blank">
                                                        <span>URL: </span>{{ route('product.detail', [$item->slug, $item->id]) }}
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    @if ($item->image != '')
                                                        <img src="{{ get_image($item->image) }}" style="height: 70px;">
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        $categories = $item->categories;
                                                    @endphp
                                                    @foreach ($categories as $k => $category)
                                                        <a class="link" target="_blank" href="{{ route('admin.product-category.edit', $category->id) }}">{{ $category->name }}</a> </br>
                                                    @endforeach
                                                </td>

                                                <td class="text-center">
                                                    <input type="checkbox" id="hot" class="quick_change_value" @checked($item->hot == 1) value="1" value-off="0" data-id="{{ $item->id }}" data-model="{{ get_class($item) }}" data-toggle="toggle" data-on="Hot" data-off="Không" data-onstyle="danger" data-offstyle="light">
                                                    <p class="my-2">{{ $item->updated_at }}</p>
                                                    <input type="checkbox" id="status" class="quick_change_value" @checked($item->status == 1) value="1" value-off="0" data-id="{{ $item->id }}" data-model="{{ get_class($item) }}" data-toggle="toggle" data-on="Công khai" data-off="Bản nháp" data-onstyle="success" data-offstyle="light">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="fr">
                                {!! $products->links() !!}
                            </div>
                        </div> <!-- /.card-body -->
                    </div><!-- /.card -->
                </div> <!-- /.col -->
            </div> <!-- /.row -->
        </div> <!-- /.container-fluid -->
    </div>
@endsection

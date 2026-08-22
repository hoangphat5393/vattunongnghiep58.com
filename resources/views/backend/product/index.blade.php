@extends('backend.layouts.master')
@section('seo')
    @php
        $title_head = __('admin.product');
        $seo = [
            'title' => $title_head . ' | ' . setting_option('seo-title-add'),
            'keywords' => setting_option('seo-keywords-add'),
            'description' => setting_option('seo-description-add'),
            'og_title' => $title_head,
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
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('admin.home')</a></li>
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
                            <h3 class="card-title">{{ $title_head }}</h3>
                        </div> <!-- /.card-header -->
                        <div class="card-body">

                            <div class="d-flex flex-column flex-lg-row justify-content-between">
                                @include('backend.partials.button_add_delete', ['type' => 'product', 'route' => route('admin.product.create')])
                                <div class="mt-3 mt-lg-0">
                                    <form method="GET" action="" id="frm-filter-post" class="form-inline">
                                        @php
                                            $categories = App\Models\Backend\Category::select('id', 'name')->orderByDesc('sort')->get();
                                        @endphp
                                        <div class="input-group">
                                            <select class="form-select" name="category_id">
                                                <option value="">@lang('admin.category')</option>
                                                @foreach ($categories as $item)
                                                    <option value="{{ $item->id }}" {{ request('category_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                            <input type="text" class="form-control" name="name" id="name" placeholder="@lang('admin.keyword')" value="{{ request('name') }}">
                                            <button type="submit" class="btn btn-outline-primary" aria-label="@lang('admin.search')">
                                                <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i> @lang('admin.search')
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="my-4 d-flex align-items-center justify-content-between">
                                <div>
                                    <b>@lang('admin.total')</b>: <span class="fw-bold text-red">{{ $total_item ?? 0 }}</span> @lang('admin.product')
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover list-data v-center" id="table_index">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width:50px">
                                                <div class="icheck-info d-inline">
                                                    <input type="checkbox" id="selectall" onclick="select_all()">
                                                    <label for="selectall">
                                                    </label>
                                                </div>
                                            </th>
                                            <th scope="col" class="text-center" style="width:100px">@lang('admin.priority')</th>
                                            <th scope="col" class="text-center">@lang('admin.name')</th>
                                            <th scope="col" class="text-center">@lang('admin.thumbnail')</th>
                                            <th scope="col" class="text-center" style="min-width: 140px;">Giá</th>
                                            <th scope="col" class="text-center">@lang('admin.category')</th>
                                            <th scope="col" class="text-center">@lang('admin.created date')</th>
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
                                                    <input type="text" id="sort" class="text-center form-control quick_change_value" data-id="{{ $item->id }}" data-model="{{ get_class($item) }}" value="{{ $item->sort }}" reload-on-change>
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
                                                <td class="text-start">
                                                    @php
                                                        $isContact = ($item->price_type === 'contact' || empty($item->price) || (float)$item->price <= 0);
                                                        $regularPrice = (float)($item->price ?? 0);
                                                        $salePrice = (float)($item->sale_price ?? 0);
                                                        $hasDiscount = ($salePrice > 0 && $regularPrice > 0 && $salePrice < $regularPrice);
                                                        $displaySalePrice = $hasDiscount ? $salePrice : $regularPrice;
                                                        $discountAmount = $hasDiscount ? ($regularPrice - $salePrice) : 0;
                                                        $discountPercent = ($hasDiscount && $regularPrice > 0) ? (int) round(($discountAmount / $regularPrice) * 100) : 0;
                                                        $unitDisplay = $item->unit ?? 'VNĐ';
                                                    @endphp
                                                    @if ($isContact)
                                                        <span class="badge bg-secondary">Liên hệ</span>
                                                    @else
                                                        <div class="small">
                                                            <div class="mb-1">
                                                                <span class="text-muted">Giá bán:</span> 
                                                                <b class="text-success">{{ number_format($displaySalePrice, 0, ',', '.') }} {{ $unitDisplay }}</b>
                                                            </div>
                                                            @if ($hasDiscount)
                                                                <div class="mb-1">
                                                                    <span class="text-muted">Giá vốn:</span> 
                                                                    <s class="text-secondary">{{ number_format($regularPrice, 0, ',', '.') }} {{ $unitDisplay }}</s>
                                                                </div>
                                                                <div class="mb-1">
                                                                    <span class="text-muted">Giá giảm:</span> 
                                                                    <span class="text-danger fw-bold">-{{ number_format($discountAmount, 0, ',', '.') }} {{ $unitDisplay }} ({{ $discountPercent }}%)</span>
                                                                </div>
                                                            @endif
                                                            @if ($item->prices && $item->prices->count() > 0)
                                                                <div class="mt-1">
                                                                    <span class="badge bg-info text-dark" style="font-size: 10px;">{{ $item->prices->count() }} mức giá</span>
                                                                </div>
                                                            @endif
                                                        </div>
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
                                                    <input type="checkbox" id="hot" class="quick_change_value" @checked($item->hot == 1) value="1" value-off="0" data-id="{{ $item->id }}" data-model="{{ get_class($item) }}" data-toggle="toggle" data-on="Bán chạy" data-off="Không" data-onstyle="danger" data-offstyle="light">
                                                    <p class="my-2">{{ $item->updated_at }}</p>
                                                    <input type="checkbox" id="status" class="quick_change_value" @checked($item->status == 1) value="1" value-off="0" data-id="{{ $item->id }}" data-model="{{ get_class($item) }}" data-toggle="toggle" data-on="Công khai" data-off="Bản nháp" data-onstyle="success" data-offstyle="light">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if (false)
                                <div class="float-end">
                                    {!! $products->links() !!}
                                </div>
                            @endif
                        </div> <!-- /.card-body -->

                        {{-- card-footer --}}
                        {{ $products->links('backend.pagination.custom') }}
                    </div><!-- /.card -->
                </div> <!-- /.col -->
            </div> <!-- /.row -->
        </div> <!-- /.container-fluid -->
    </div>
@endsection

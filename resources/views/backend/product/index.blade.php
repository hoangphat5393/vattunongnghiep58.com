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
                                <table class="table table-bordered table-hover list-data align-middle" id="table_index">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width:45px">
                                                <div class="icheck-info d-inline">
                                                    <input type="checkbox" id="selectall" onclick="select_all()">
                                                    <label for="selectall"></label>
                                                </div>
                                            </th>
                                            <th scope="col" class="text-center" style="width:80px">@lang('admin.sort')</th>
                                            <th scope="col">Tên sản phẩm</th>
                                            <th scope="col" class="text-center" style="width:120px">@lang('admin.thumbnail')</th>
                                            <th scope="col" style="min-width: 150px;">Giá</th>
                                            <th scope="col" class="text-center" style="width:130px">@lang('admin.category')</th>
                                            <th scope="col" class="text-center" style="width:100px"><i class="fa-solid fa-fire text-danger me-1"></i> Bán chạy</th>
                                            <th scope="col" class="text-center" style="width:105px"><i class="fa-solid fa-eye text-primary me-1"></i> Trạng thái</th>
                                            <th scope="col" class="text-center" style="width:130px">@lang('admin.created date')</th>
                                            <th scope="col" class="text-center" style="width:110px">Hành động</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse ($products as $item)
                                            <tr>
                                                <td class="text-center">
                                                    <div class="icheck-info d-inline">
                                                        <input type="checkbox" id="product-{{ $item->id }}" name="seq_list[]" value="{{ $item->id }}">
                                                        <label for="product-{{ $item->id }}"></label>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <input type="number" id="sort-{{ $item->id }}" class="text-center form-control form-control-sm quick_change_value mx-auto" style="max-width: 65px;" data-id="{{ $item->id }}" data-column="sort" data-model="{{ get_class($item) }}" value="{{ $item->sort }}" reload-on-change>
                                                </td>
                                                <td>
                                                    <a class="text-decoration-none fw-bold text-dark fs-6" href="{{ route('admin.product.edit', [$item->id]) }}">
                                                        {{ $item->name }}
                                                    </a>
                                                    <div class="mt-1">
                                                        <a class="text-decoration-none text-muted small" href="{{ route('product.detail', [$item->slug, $item->id]) }}" target="_blank" title="Xem trên website">
                                                            <i class="fa-solid fa-arrow-up-right-from-square me-1 text-primary"></i>/product/{{ $item->slug }}-{{ $item->id }}.html
                                                        </a>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="cat-thumb-box">
                                                        @if (!empty($item->image))
                                                            <img src="{{ get_image($item->image) }}" class="cat-thumb-img" alt="{{ $item->name }}" loading="lazy">
                                                        @else
                                                            <div class="cat-thumb-placeholder">
                                                                <i class="fa-regular fa-image fs-5"></i>
                                                            </div>
                                                        @endif
                                                    </div>
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
                                                    @if ($categories && $categories->count() > 0)
                                                        @foreach ($categories as $category)
                                                            <a class="badge bg-light text-primary border text-decoration-none d-inline-block mb-1" target="_blank" href="{{ route('admin.product-category.edit', $category->id) }}">
                                                                <i class="fa-regular fa-folder me-1"></i>{{ $category->name }}
                                                            </a><br>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted small">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="form-check form-switch d-inline-flex align-items-center justify-content-center m-0">
                                                        <input class="form-check-input quick_change_value switch-hot" type="checkbox" role="switch"
                                                            id="hot-{{ $item->id }}" data-column="hot" data-id="{{ $item->id }}"
                                                            data-model="{{ get_class($item) }}" value="1" value-off="0"
                                                            @checked($item->hot == 1) title="{{ $item->hot ? 'Đang bật Bán chạy (Click để tắt)' : 'Đang tắt Bán chạy (Click để bật)' }}"
                                                            style="cursor: pointer; width: 2.3rem; height: 1.25rem;">
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="form-check form-switch d-inline-flex align-items-center justify-content-center m-0">
                                                        <input class="form-check-input quick_change_value switch-status" type="checkbox" role="switch"
                                                            id="status-{{ $item->id }}" data-column="status" data-id="{{ $item->id }}"
                                                            data-model="{{ get_class($item) }}" value="1" value-off="0"
                                                            @checked($item->status == 1) title="{{ $item->status ? 'Đang công khai (Click để chuyển bản nháp)' : 'Đang là bản nháp (Click để công khai)' }}"
                                                            style="cursor: pointer; width: 2.3rem; height: 1.25rem;">
                                                    </div>
                                                </td>
                                                <td class="text-center text-muted" style="font-size: 0.84rem;">
                                                    <div><i class="fa-regular fa-calendar-check me-1 text-secondary"></i>{{ $item->created_at ? $item->created_at->format('d/m/Y') : ($item->updated_at ? $item->updated_at->format('d/m/Y') : '-') }}</div>
                                                    <small class="text-secondary">{{ $item->created_at ? $item->created_at->format('H:i:s') : ($item->updated_at ? $item->updated_at->format('H:i:s') : '') }}</small>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="{{ route('product.detail', [$item->slug, $item->id]) }}" target="_blank" class="btn btn-outline-secondary" title="Xem trên website">
                                                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                                        </a>
                                                        <a href="{{ route('admin.product.edit', [$item->id]) }}" class="btn btn-outline-primary" title="@lang('admin.Edit')">
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-outline-danger" onclick="delete_single_id('product', {{ $item->id }})" title="@lang('admin.Delete')">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center py-4 text-muted">
                                                    <i class="fa-solid fa-box-open fs-2 d-block mb-2"></i>
                                                    Không có sản phẩm nào.
                                                </td>
                                            </tr>
                                        @endforelse
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

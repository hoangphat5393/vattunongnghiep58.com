@extends('backend.layouts.master')
@section('seo')
    @php
        $title_head = 'Đơn hàng';
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

@push('style')
    <style type="text/css">
        .cart-status-1 { color: #fff !important; background-color: #007bff !important; }
        .cart-status-2 { color: #fff !important; background-color: #17a2b8 !important; }
        .cart-status-3 { color: #fff !important; background-color: #dc3545 !important; }
        .cart-status-4 { background-color: #ffc107 !important; }
        .cart-status-5 { background-color: #28a745 !important; color: #fff !important; }
    </style>
@endpush

@section('content')
    {{-- begin::App Content Header --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">{{ $title_head }}</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('admin.home')</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $title_head }}</li>
                    </ol>
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
                    <div class="card card-primary card-outline mb-4">
                        <div class="card-header">
                            <h3 class="card-title">{{ $title_head }} @lang('admin.list')</h3>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                                <div></div>
                                <div>
                                    <form method="GET" action="{{ route('admin.order.index') }}" id="frm-filter-order" class="form-inline">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="search_name" placeholder="Tên khách hàng" value="{{ request('search_name') }}" aria-label="Tìm theo tên">
                                            <button class="btn btn-outline-primary" type="submit">
                                                <i class="fa-regular fa-magnifying-glass"></i> @lang('admin.search')
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center my-4">
                                <div>
                                    <b>@lang('admin.total')</b>: <span class="fw-bold text-red">{{ $total_item ?? 0 }}</span> đơn
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered list-data v-center" id="table_index">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center">Mã đơn hàng</th>
                                            <th scope="col" class="text-center">Tên khách hàng</th>
                                            <th scope="col" class="text-center">Tổng tiền</th>
                                            <th scope="col" class="text-center">Thời gian đặt</th>
                                            <th scope="col" class="text-center">Trạng thái</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($data && $data->count())
                                            @foreach ($data as $item)
                                                <tr>
                                                    <td class="text-center">
                                                        <a class="row-title" href="{{ route('admin.order.detail', $item->cart_id) }}">
                                                            <b>#{{ $item->cart_code ?? $item->cart_id }}</b>
                                                        </a>
                                                    </td>
                                                    <td class="text-center">
                                                        <a class="row-title" href="{{ route('admin.order.detail', $item->cart_id) }}">
                                                            {{ $item->name }}
                                                        </a>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="fw-bold text-danger">{{ number_format($item->cart_total ?? 0, 0, ',', '.') }} đ</span>
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $item->created_at }}
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="mb-2">
                                                            <button type="button" class="btn btn-info btn-sm order-view-detail" data-id="{{ $item->cart_id }}">Chi tiết SP <i class="bi bi-list-task"></i></button>
                                                            <button type="button" class="btn btn-secondary btn-sm order-view-hide" data-id="{{ $item->cart_id }}" style="display: none;">Ẩn <i class="bi bi-list-task"></i></button>
                                                        </div>
                                                        <input type="checkbox" id="cart_payment" class="quick_change_value" @checked(($item->cart_payment ?? 0) == 1) value="1" value-off="0" data-id="{{ $item->cart_id }}" data-model="{{ get_class($item) }}" data-toggle="toggle" data-on="Đã thanh toán" data-off="Chưa thanh toán" data-onstyle="success" data-offstyle="light">
                                                    </td>
                                                </tr>

                                                @if ($item->items()->count() > 0)
                                                    @include('backend.orders.includes.orders_item', ['orders_item' => $item])
                                                @endif
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{ $data->links('backend.pagination.custom') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('.order-view-hide').on('click', function() {
                var id = $(this).data('id');
                $(this).hide();
                $(this).closest('tr').find('.order-view-detail').show();
                $('.parent-id-' + id).hide();
            });
            $('.order-view-detail').on('click', function() {
                var id = $(this).data('id');
                $(this).hide();
                $('.parent-id-' + id).show();
                $(this).closest('tr').find('.order-view-hide').show();
            });
        });
    </script>
@endpush

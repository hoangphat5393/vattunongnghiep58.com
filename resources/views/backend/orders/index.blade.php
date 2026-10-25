@extends('backend.layouts.master')
@section('seo')
    @php
        $title_head = __('admin.orders');
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
                    <div class="card card-primary card-outline mb-4">
                        <div class="card-header">
                            <h3 class="card-title">{{ $title_head }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                                <div>
                                    @include('backend.partials.button_delete', ['type' => 'order'])
                                </div>
                                <div>
                                    <form method="GET" action="{{ route('admin.order.index') }}" id="frm-filter-order" class="d-flex flex-wrap align-items-center gap-2">
                                        <select name="status" class="form-select" style="min-width: 160px;">
                                            <option value="">Tất cả trạng thái</option>
                                            @if (!empty($statusOrder))
                                                @foreach ($statusOrder as $key => $name)
                                                    <option value="{{ $key }}" @selected(request()->filled('status') && request('status') !== 'all' && (string) request('status') === (string) $key)>{{ $name }}</option>
                                                @endforeach
                                            @endif
                                        </select>

                                        <select name="payment_status" class="form-select" style="min-width: 160px;">
                                            <option value="">Tất cả thanh toán</option>
                                            @if (!empty($orderPayment))
                                                @foreach ($orderPayment as $key => $name)
                                                    <option value="{{ $key }}" @selected(request()->filled('payment_status') && request('payment_status') !== 'all' && (string) request('payment_status') === (string) $key)>{{ $name }}</option>
                                                @endforeach
                                            @endif
                                        </select>

                                        <div class="input-group" style="min-width: 250px;">
                                            <input type="text" class="form-control" name="search_name" placeholder="Mã đơn, tên, SĐT khách..." value="{{ request('search_name') }}" aria-label="Mã đơn, tên, SĐT khách">
                                            <button class="btn btn-outline-primary" type="submit">
                                                <i class="fa-solid fa-magnifying-glass"></i> @lang('admin.search')
                                            </button>
                                        </div>

                                        @if (request()->hasAny(['search_name', 'status', 'payment_status']))
                                            <a href="{{ route('admin.order.index') }}" class="btn btn-outline-secondary" title="Xóa bộ lọc">
                                                <i class="fa-solid fa-rotate-left"></i>
                                            </a>
                                        @endif
                                    </form>
                                </div>
                            </div>

                            <div class="my-4 d-flex justify-content-between align-items-center">
                                <div>
                                    <b>@lang('admin.total')</b>: <span class="fw-bold text-red">{{ $total_item ?? 0 }}</span> {{ $title_head }}
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover list-data align-middle" id="table_index">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 45px;">
                                                <div class="icheck-info d-inline">
                                                    <input type="checkbox" id="selectall" onclick="select_all()">
                                                    <label for="selectall"></label>
                                                </div>
                                            </th>
                                            <th scope="col" class="text-center" style="width: 130px;">@lang('admin.order_code')</th>
                                            <th scope="col" style="min-width: 200px;">@lang('admin.customer_name')</th>
                                            <th scope="col" class="text-center" style="width: 140px;">@lang('admin.total_amount')</th>
                                            <th scope="col" class="text-center" style="width: 140px;">Trạng thái đơn</th>
                                            <th scope="col" class="text-center" style="width: 130px;">Thanh toán</th>
                                            <th scope="col" class="text-center" style="width: 130px;">@lang('admin.order_time')</th>
                                            <th scope="col" class="text-center" style="width: 120px;">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data as $item)
                                            <tr>
                                                <td class="text-center">
                                                    <div class="icheck-info d-inline">
                                                        <input type="checkbox" id="order-{{ $item->cart_id }}" name="seq_list[]" value="{{ $item->cart_id }}">
                                                        <label for="order-{{ $item->cart_id }}"></label>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <a class="text-decoration-none fw-bold text-primary fs-6" href="{{ route('admin.order.detail', $item->cart_id) }}">
                                                        #{{ $item->cart_code ?: $item->cart_id }}
                                                    </a>
                                                    <div class="mt-1">
                                                        <span class="badge bg-light text-secondary border small">
                                                            <i class="fa-solid fa-boxes-stacked me-1"></i>{{ $item->items ? $item->items->count() : 0 }} SP
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="fw-bold text-dark fs-6">
                                                        {{ $item->name ?: 'Khách vãng lai' }}
                                                    </div>
                                                    <div class="small mt-1 text-muted">
                                                        @if (!empty($item->phone))
                                                            <div class="d-inline-block me-2">
                                                                <a href="tel:{{ $item->phone }}" class="text-decoration-none text-success">
                                                                    <i class="fa-solid fa-phone me-1"></i>{{ $item->phone }}
                                                                </a>
                                                            </div>
                                                        @endif
                                                        @if (!empty($item->email))
                                                            <div class="d-inline-block">
                                                                <a href="mailto:{{ $item->email }}" class="text-decoration-none text-info">
                                                                    <i class="fa-solid fa-envelope me-1"></i>{{ $item->email }}
                                                                </a>
                                                            </div>
                                                        @endif
                                                        @if (!empty($item->address))
                                                            <div class="text-muted small mt-1">
                                                                <i class="fa-solid fa-location-dot me-1 text-secondary"></i>{{ \Illuminate\Support\Str::limit($item->address, 50) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <span class="fw-bold text-danger fs-6">{{ number_format($item->cart_total ?? 0, 0, ',', '.') }} đ</span>
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        $statusVal = $item->status instanceof \App\Enums\OrderStatus ? $item->status->value : (int) ($item->status ?? 0);
                                                        $statusEnum = \App\Enums\OrderStatus::tryFrom($statusVal);
                                                    @endphp
                                                    <span class="badge {{ $statusEnum ? $statusEnum->badgeClass() : 'bg-secondary text-white' }} px-2 py-1">
                                                        @if ($statusVal == 0)
                                                            <i class="fa-regular fa-clock me-1"></i>
                                                        @elseif ($statusVal == 1)
                                                            <i class="fa-solid fa-arrows-rotate me-1"></i>
                                                        @elseif ($statusVal == 4)
                                                            <i class="fa-solid fa-truck-fast me-1"></i>
                                                        @elseif ($statusVal == 2)
                                                            <i class="fa-solid fa-circle-check me-1"></i>
                                                        @elseif ($statusVal == 3)
                                                            <i class="fa-solid fa-ban me-1"></i>
                                                        @endif
                                                        {{ $statusEnum ? $statusEnum->label() : ($statusOrder[$statusVal] ?? 'Chờ xác nhận') }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        $paymentVal = $item->payment_status instanceof \App\Enums\PaymentStatus ? $item->payment_status->value : (int) ($item->payment_status ?? 0);
                                                    @endphp
                                                    <div class="form-check form-switch d-inline-flex align-items-center justify-content-center m-0">
                                                        <input class="form-check-input quick_change_value switch-status" type="checkbox" role="switch"
                                                            id="payment_status-{{ $item->cart_id }}" data-column="payment_status" data-id="{{ $item->cart_id }}"
                                                            data-model="{{ get_class($item) }}" value="1" value-off="0"
                                                            @checked($paymentVal == 1)
                                                            title="{{ $paymentVal == 1 ? 'Đã thanh toán (Click để đổi)' : 'Chưa thanh toán (Click để đổi)' }}"
                                                            style="cursor: pointer; width: 2.3rem; height: 1.25rem;">
                                                    </div>
                                                    <div class="small mt-1 {{ $paymentVal == 1 ? 'text-success fw-bold' : 'text-muted' }}">
                                                        {{ $paymentVal == 1 ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                                                    </div>
                                                </td>
                                                <td class="text-center text-muted" style="font-size: 0.84rem;">
                                                    @php
                                                        $rawDate = $item->created_at ?: $item->updated_at;
                                                    @endphp
                                                    <div>
                                                        <i class="fa-regular fa-calendar-check me-1 text-secondary"></i>{{ $rawDate ? \Carbon\Carbon::parse($rawDate)->format('d/m/Y') : '-' }}
                                                    </div>
                                                    <small class="text-secondary">{{ $rawDate ? \Carbon\Carbon::parse($rawDate)->format('H:i:s') : '' }}</small>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="{{ route('admin.order.detail', $item->cart_id) }}" class="btn btn-outline-primary" title="Xem chi tiết đơn hàng">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </a>
                                                        @if ($item->items && $item->items->count() > 0)
                                                            <button type="button" class="btn btn-outline-info order-view-toggle" data-id="{{ $item->cart_id }}" title="Xem sản phẩm trong đơn">
                                                                <i class="fa-solid fa-list-check"></i>
                                                            </button>
                                                        @endif
                                                        <button type="button" class="btn btn-outline-danger" onclick="delete_single_id('order', {{ $item->cart_id }})" title="Xóa đơn hàng">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>

                                            @if ($item->items && $item->items->count() > 0)
                                                @include('backend.orders.includes.orders_item', ['orders_item' => $item])
                                            @endif
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4 text-muted">
                                                    <i class="fa-solid fa-cart-shopping fs-2 d-block mb-2 text-secondary"></i>
                                                    Không có đơn hàng nào.
                                                </td>
                                            </tr>
                                        @endforelse
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
            $('.order-view-toggle').on('click', function() {
                var id = $(this).data('id');
                var $target = $('.parent-id-' + id);
                $target.fadeToggle(150);
                $(this).toggleClass('active btn-info btn-outline-info');
            });
        });
    </script>
@endpush

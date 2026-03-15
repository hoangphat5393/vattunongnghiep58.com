@extends('backend.layouts.master')
@section('seo')
    @php
        $title_head = 'Đơn hàng';
        $seo = [
            'title' => 'List Orders | ' . setting_option('seo-title-add'),
            'keywords' => setting_option('seo-keywords-add'),
            'description' => setting_option('seo-description-add'),
            'og_title' => 'List Orders | ' . setting_option('seo-title-add'),
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
        .cart-status-1 {
            color: #fff !important;
            background-color: #007bff !important;
        }

        .cart-status-2 {
            color: #fff !important;
            background-color: #17a2b8 !important;
        }

        .cart-status-3 {
            color: #fff !important;
            background-color: #dc3545 !important;
        }

        .cart-status-4 {
            background-color: #ffc107 !important;
        }

        .cart-status-5 {
            background-color: #28a745 !important;
            color: #fff !important;
        }
    </style>
@endpush

@section('content')
    {{-- Content Header (Page header) --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">List Orders</li>
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
                            <h4 class="card-title">List Orders</h4>
                        </div> <!-- /.card-header -->
                        <div class="card-body">

                            <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between">
                                {{-- @include('backend.partials.button_add_delete', ['type' => 'post', 'route' => route('admin.postCreate')]) --}}
                            </div>

                            <div class="d-flex align-items-center justify-content-between my-4">
                                <div class="fl">
                                    <b>@lang('admin.Total')</b>: <span class="fw-bold text-red">{{ $total_item ?? 0 }}</span> @lang('admin.News')
                                </div>
                                <div class="fr">
                                    {!! $data->links() !!}
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered" id="table_index">
                                    <thead>
                                        <tr>
                                            {{-- <th scope="col" class="text-center"><input type="checkbox" id="selectall" onclick="select_all()"></th> --}}
                                            <th scope="col" class="text-center">Mã đơn hàng</th>
                                            <th scope="col" class="text-center">Tên khách hàng</th>
                                            <th scope="col" class="text-center">Tổng tiền</th>

                                            <th scope="col" class="text-center">Thời gian đặt</th>
                                            <th scope="col" class="text-center">Trạng thái</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($data)
                                            @foreach ($data as $item)
                                                <tr>
                                                    {{-- <td class="text-center"><input type="checkbox" id="{{ $item->id }}" name="seq_list[]" value="{{ $item->id }}"></td> --}}
                                                    <td class="text-center">
                                                        <a class="row-title" href="{{ route('admin.order.detail', [$item->id]) }}">
                                                            <b>{{ $item->id }}</b>
                                                        </a>
                                                    </td>
                                                    <td class="text-center">
                                                        <a class="row-title" href="{{ route('admin.order.detail', [$item->id]) }}">
                                                            {{ $item->email }}
                                                        </a>
                                                    </td>

                                                    <td class="text-center">
                                                        {{-- <span class='b' style='color: red;'>{!! number_format($item->total_price) !!} đ</span> --}}
                                                        <span class='b' style='color: red;'>{{ number_format($item->total_price, 0, ',', '.') }} đ</span>
                                                    </td>

                                                    <td class="text-center">
                                                        {{ $item->created_at }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{-- <span class="badge cart-status-{{ $item->status }}">{{ $statusOrder[$item->status] ?? 'Chờ xác nhận' }}</span> --}}
                                                        {{ $item->updated_at }}
                                                        <br>
                                                        <div>
                                                            <button class="btn btn-info order-view-detail" data-id="{{ $item->id }}">Thông tin <i class="bi bi-list-task"></i></button>
                                                            <button class="btn btn-info order-view-hide" data-id="{{ $item->id }}" style="display: none;">Thông tin <i class="bi bi-list-task"></i></button>
                                                        </div>
                                                        <br>
                                                        <input type="checkbox" id="status" class="quick_change_value" @checked($item->status == 1) value="1" value-off="0" data-id="{{ $item->id }}" data-model="{{ get_class($item) }}" data-toggle="toggle" data-on="Đã thanh toán" data-off="Chưa thanh toán" data-onstyle="success" data-offstyle="light">
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
                            <div class="fr">
                                {!! $data->links() !!}
                            </div>
                        </div> <!-- /.card-body -->
                    </div><!-- /.card -->
                </div> <!-- /.col -->
            </div> <!-- /.row -->
        </div> <!-- /.container-fluid -->
    </div>
@endsection




@push('scripts')
    <script type="text/javascript">
        $(function() {
            $('.order-view-hide').click(function() {
                var id = $(this).data('id');
                $(this).hide();
                $(this).closest('tr').find('.order-view-detail').show();
                $('.parent-id-' + id).hide();
            });
            $('.order-view-detail').click(function() {
                var id = $(this).data('id')
                $(this).hide();
                $('.parent-id-' + id).show();
                $(this).closest('tr').find('.order-view-hide').show();
            });
        });
    </script>
@endpush

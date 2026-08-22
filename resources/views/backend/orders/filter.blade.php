@extends('backend.layouts.master')
@section('seo')
    <?php
    $data_seo = [
        'title' => 'Filter Orders | ' . setting_option('seo-title-add'),
        'keywords' => setting_option('seo-keywords-add'),
        'description' => setting_option('seo-description-add'),
        'og_title' => 'Filter Orders | ' . setting_option('seo-title-add'),
        'og_description' => setting_option('seo-description-add'),
        'og_url' => Request::url(),
        'og_img' => asset('assets/images/logo_seo.png'),
        'current_url' => Request::url(),
        'current_url_amp' => '',
    ];
    $seo = WebService::getSEO($data_seo);
    ?>
    @include('backend.partials.seo')
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0">Filter Orders</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb" class="float-sm-end"><ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('admin.home')</a></li>
                        <li class="breadcrumb-item active">Filter Orders</li>
                    </ol></nav>
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
                            <h3 class="card-title">Filter Orders</h3>
                        </div> <!-- /.card-header -->
                        <div class="card-body">
                            <div class="clearfix">
                                <ul class="nav float-start">
                                    <li class="nav-item">
                                        <a class="btn btn-danger" onclick="delete_id('order')" href="javascript:void(0)"><i class="fas fa-trash"></i> Delete</a>
                                    </li>
                                </ul>
                                <div class="float-end">
                                    <form method="GET" action="{{ route('admin.order.search') }}" id="frm-filter-post" class="form-inline">
                                        <select class="custom-select me-2" name="order_status">
                                            <option value="">Tình trạng đơn hàng</option>
                                            <option value="1" @if ($_GET['order_status'] == 1) selected @endif>Mới đặt</option>
                                            <option value="2" @if ($_GET['order_status'] == 2) selected @endif>Giao J&T</option>
                                            <option value="3" @if ($_GET['order_status'] == 3) selected @endif>Đã hủy</option>
                                            <option value="4" @if ($_GET['order_status'] == 4) selected @endif>Đợi xử lý</option>
                                            <option value="5" @if ($_GET['order_status'] == 5) selected @endif>Liên hệ sau</option>
                                        </select>
                                        <input type="text" class="form-control" value="<?php if (isset($_GET['search_title'])) {
                                            echo $_GET['search_title'];
                                        } ?>" name="search_title" id="search_title" placeholder="Mã đơn hàng">
                                        <button type="submit" class="btn btn-primary ms-2" aria-label="Tìm kiếm">
                                            <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <br />
                            <div class="clearfix">
                                @if (false)
                                    <div class="float-end">
                                        {!! $data_order->links() !!}
                                    </div>
                                @endif
                            </div>
                            <br />
                            <div class="table-responsive">
                                <table class="table table-bordered" id="table_index">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center"><input type="checkbox" id="selectall" onclick="select_all()"></th>
                                            <th scope="col" class="text-center">Mã đơn hàng</th>
                                            <th scope="col" class="text-center">Họ tên</th>
                                            <th scope="col" class="text-center">Thời gian đặt</th>
                                            <th scope="col" class="text-center">Tổng giá trị</th>
                                            <th scope="col" class="text-center">Tình trạng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data_order as $data)
                                            <tr>
                                                <td class="text-center"><input type="checkbox" id="{{ $data->cart_id }}" name="seq_list[]" value="{{ $data->cart_id }}"></td>
                                                <td class="text-center">
                                                    <a class="row-title" href="{{ route('admin.order.detail', [$data->cart_id]) }}">
                                                        <b>{{ $data->cart_code }}</b>
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    <a class="row-title" href="{{ route('admin.order.detail', [$data->cart_id]) }}">
                                                        {{ $data->cart_hoten }}
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    {{ $data->created }}
                                                </td>
                                                <td class="text-center">
                                                    <span class='b' style='color: red;'>{{ number_format($data->cart_total) }} VNĐ</span>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    switch ($data->cart_status) {
                                                        case 1:
                                                            echo "<span class='b' style='color: green;'>Mới đặt</span>";
                                                            break;
                                                        case 2:
                                                            echo "<span class='b' style='color: #ffa500;'>Giao J&T</span>";
                                                            break;
                                                        case 3:
                                                            echo "<span class='b' style='color: red;'>Đã hủy</span>";
                                                            break;
                                                        case 4:
                                                            echo "<span class='b' style='color: #ffb100;'>Đợi xử lý</span>";
                                                            break;
                                                        case 5:
                                                            echo "<span class='b' style='color: #0025db;'>Liên hệ sau</span>";
                                                            break;
                                                        default:
                                                            break;
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div> <!-- /.card-body -->

                        @if (false)
                            <div class="float-end">
                                {!! $data_order->links() !!}
                            </div>
                        @endif

                        {{ $data_order->links('backend.pagination.custom') }}
                    </div><!-- /.card -->
                </div> <!-- /.col -->
            </div> <!-- /.row -->
        </div> <!-- /.container-fluid -->
    </div>
@endsection

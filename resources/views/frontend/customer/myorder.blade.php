@php
    $states = \App\Models\Province::get();

    $avatar = url('theme/images/user-none.png');
    if (auth()->user()->avatar) {
        // $avatar = url('img/users/avatar/' . $user->avatar);
        $avatar = url($user->avatar);
    }
@endphp

@extends('frontend.layouts.master')

@section('seo')
    @include('frontend.layouts.seo', $seo ?? [])
@endsection

@section('content')
    <section class="py-5 my-post position-relative customer">

        <div class="container">
            <div class="breadcrumbs">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active">My Order</li>
                </ol>
            </div>

            <h1 class="text-white">MY ORDERS</h1>
            @include($templatePath . '.customer.includes.header-customer')

            <div class="row justify-content-end">
                <div class="col-lg-3  col-12 mb-4">
                    @include($templatePath . '.customer.includes.sidebar-customer')
                </div>
                <div class="col-lg-9 col-12">
                    <div class="block-content p-4 page-title">
                        <h2 class="mb-3">My orders</h2>

                        <div class="table-responsive">
                            <table class="table  align-middle table-my-orders">
                                <thead>
                                    <tr>
                                        <th scope="col">Id Order</th>
                                        <th scope="col">Game Name</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Status</th>
                                        <th scope="col" width="230" class="text-center">Detail & Complain</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($orders as $data)
                                        <tr class="alternate" data-id="{{ $data->cart_id }}">
                                            <td scope="row" id="column-{{ $data->cart_id }}">
                                                <div class="mb-2">
                                                    <a href="{{ route('customer.myordersdetail', [$data->cart_id]) }}"><b>{{ $data->cart_code }}</b></a>
                                                </div>
                                                <div style="font-size: 12px">{{ $data->created_at }}</div>
                                            </td>
                                            <td class="name column-name">
                                                <a href="{{ route('customer.myordersdetail', [$data->cart_id]) }}">{{ $data->firstname }} {{ $data->lastname }}</a>
                                            </td>
                                            <td>
                                                <span class="price">{!! render_price($data->cart_total) !!}</span>

                                            </td>
                                            <td>
                                                @if ($data->cart_payment == 1)
                                                    <span class="price">{{ $orderPayment[$data->cart_payment] }}</span>
                                                @else
                                                    <span>{{ $orderPayment[$data->cart_payment] }}</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <button class="btn btn-custom order-view-detail">View Detail <i class="fa fa-fw fa-list-alt"></i></button>
                                                <button class="btn btn-custom order-view-hide" style="display: none;">Hide <i class="fa fa-fw fa-list-alt"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('head-style')
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
        }
    </style>
@endpush

@push('scripts')
    <script type="text/javascript">
        jQuery(document).ready(function($) {

            $('.order-view-hide').click(function() {
                var id = $(this).closest('tr').data('id');
                $(this).hide();
                $(this).closest('tr').find('.order-view-detail').show();
                $('.order-id-' + id).hide();
            });
            $('.order-view-detail').click(function() {
                var id = $(this).closest('tr').data('id'),
                    tr_order = $('.order-id-' + id);

                $(this).hide();
                tr_order.show();
                $(this).closest('tr').find('.order-view-hide').show();

                axios({
                        method: "post",
                        url: "/ajax/order-view",
                        data: {
                            id: id
                        },
                    })
                    .then((res) => {
                        if (res.data.view && !tr_order.length) {
                            $(this).closest('tr').after(res.data.view);
                        }
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
            });
        });
    </script>
@endpush

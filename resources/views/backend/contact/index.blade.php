@extends('backend.layouts.master')
@section('seo')
    @php
        $title_head = 'Contact';
        $seo = [
            'title' => $title_head,
            'keywords' => '',
            'description' => '',
            'og_title' => $title_head,
            'og_description' => '',
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
                    <nav aria-label="breadcrumb" class="float-sm-end"><ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $title_head }}</li>
                    </ol></nav>
                </div>
            </div>
        </div>
    </div>
    {{-- end::App Content Header --}}


    <div class="app-content">
        <div class="container-fluid">

            {{-- begin::Row --}}
            <div class="row">
                <div class="col-md-12">

                    {{-- card --}}
                    <div class="mb-4 card card-primary card-outline">

                        {{-- card-header --}}
                        <div class="card-header">
                            <h3 class="card-title">{{ $title_head }} @lang('admin.list')</h3>
                        </div>

                        {{-- card-body --}}
                        <div class="card-body">
                            <div class="d-flex flex-column flex-lg-row justify-content-between">
                                @include('backend.partials.button_delete', ['type' => 'subscription'])
                                <div class="w-lg-50 mt-3 mt-lg-0">
                                    <form method="GET" action="" id="frm-filter-post" class="form-inline">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="name" name="name" placeholder="@lang('admin.name')" aria-label="@lang('admin.Keyword')" aria-describedby="name" value="{{ request('name') }}">
                                            <button class="btn btn-outline-primary" type="submit" id="button-addon2">
                                                <i class="fa-regular fa-magnifying-glass"></i> @lang('admin.search')
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="my-4 d-flex justify-content-between align-items-center">
                                <div>
                                    <b>@lang('admin.total')</b>: <span class="fw-bold text-red">{{ $total_item ?? 0 }}</span> {{ $title_head }}
                                </div>
                            </div>

                            <table class="table table-bordered list-data v-center">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width:50px">
                                            <div class="icheck-info d-inline">
                                                <input type="checkbox" id="selectall" onclick="select_all()">
                                                <label for="selectall"></label>
                                            </div>
                                        </th>
                                        <th style="width: 10px">#</th>
                                        <th class="text-center">@lang('admin.name')</th>
                                        <th class="text-center">@lang('admin.type')</th>
                                        <th class="text-center">@lang('admin.Created at')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr class="align-middle">
                                            <td class="text-center">
                                                <div class="icheck-info d-inline">
                                                    <input type="checkbox" id="{{ $item->id }}" name="seq_list[]" value="{{ $item->id }}">
                                                    <label for="{{ $item->id }}"></label>
                                                </div>
                                            </td>
                                            <td>{{ $item->id }}.</td>
                                            <td>
                                                <a class="row-title fw-bold" href="{{ route('admin.contact.edit', $item->id) }}">
                                                    {{ $item->name }}
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                {{ $item->type }}
                                            </td>
                                            <td class="text-center">
                                                {{ $item->updated_at }}
                                                {{-- <br>
                                                    <input type="checkbox" id="status" class="quick_change_value" @checked($item->status == 1) value="1" value-off="0" data-id="{{ $item->id }}" data-model="{{ get_class($item) }}" data-toggle="toggle" data-on="Công khai" data-off="Bản nháp"
                                                        data-onstyle="success" data-offstyle="light"> --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- card-footer --}}
                        {{ $data->links('backend.pagination.custom') }}
                    </div>
                    {{-- end::card --}}
                </div>
            </div>
            {{-- end::Row --}}
        </div>
    </div>
    {{-- end::App Content --}}
@endsection

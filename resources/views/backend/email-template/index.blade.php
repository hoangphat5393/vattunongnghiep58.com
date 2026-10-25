@extends('backend.layouts.master')
@section('seo')
    @php
        $lc = app()->getLocale();
        $title_head = __('admin.email template');
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

    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    {{-- card --}}
                    <div class="card card-primary card-outline mb-4">
                        {{-- header --}}
                        <div class="card-header">
                            <h3 class="card-title">{{ $title_head }}</h3>
                        </div>

                        {{-- card-body --}}
                        <div class="card-body">
                            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                                <div>
                                    @include('backend.partials.button_add_delete', ['type' => 'email_template', 'route' => route('admin.email-template.create')])
                                </div>
                                <div>
                                    <form method="GET" action="" id="frm-filter-email" class="d-flex align-items-center gap-2">
                                        <div class="input-group" style="min-width: 260px;">
                                            <input type="text" class="form-control" id="keyword" name="keyword" placeholder="Tìm tên mẫu, mã code..." value="{{ request('keyword', request('name')) }}">
                                            <button class="btn btn-outline-primary" type="submit" id="button-search">
                                                <i class="fa-solid fa-magnifying-glass"></i> @lang('admin.search')
                                            </button>
                                        </div>
                                        @if (request()->hasAny(['keyword', 'name']))
                                            <a href="{{ route('admin.email-template.index') }}" class="btn btn-outline-secondary" title="Xóa bộ lọc">
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
                                            <th scope="col" class="text-center" style="width: 55px;">#</th>
                                            <th scope="col">Tên mẫu Email</th>
                                            <th scope="col" class="text-center" style="width: 180px;">@lang('admin.code')</th>
                                            <th scope="col" class="text-center" style="width: 140px;">Nhóm</th>
                                            <th scope="col" class="text-center" style="width: 110px;">Trạng thái</th>
                                            <th scope="col" class="text-center" style="width: 130px;">@lang('admin.created date')</th>
                                            <th scope="col" class="text-center" style="width: 110px;">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data as $item)
                                            <tr>
                                                <td class="text-center">
                                                    <div class="icheck-info d-inline">
                                                        <input type="checkbox" id="email-{{ $item->id }}" name="seq_list[]" value="{{ $item->id }}">
                                                        <label for="email-{{ $item->id }}"></label>
                                                    </div>
                                                </td>
                                                <td class="text-center text-muted fw-bold">{{ $item->id }}</td>
                                                <td>
                                                    <a class="text-decoration-none fw-bold text-dark fs-6" href="{{ route('admin.email-template.edit', [$item->id]) }}">
                                                        <i class="fa-regular fa-envelope text-primary me-2"></i>{{ $item->name }}
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-light text-dark border font-monospace fs-7">
                                                        <i class="fa-solid fa-code me-1 text-secondary"></i>{{ $item->code }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-secondary-subtle text-secondary border px-2 py-1">
                                                        {{ $item->group ?: 'Mặc định' }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="form-check form-switch d-inline-flex align-items-center justify-content-center m-0">
                                                        <input class="form-check-input quick_change_value switch-status" type="checkbox" role="switch"
                                                            id="status-{{ $item->id }}" data-column="status" data-id="{{ $item->id }}"
                                                            data-model="{{ get_class($item) }}" value="1" value-off="0"
                                                            @checked($item->status == 1) title="{{ $item->status ? 'Đang kích hoạt (Click để tắt)' : 'Đang tắt (Click để kích hoạt)' }}"
                                                            style="cursor: pointer; width: 2.3rem; height: 1.25rem;">
                                                    </div>
                                                    <div class="small mt-1 text-muted">
                                                        {{ $item->status ? 'Kích hoạt' : 'Tắt' }}
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
                                                        <a href="{{ route('admin.email-template.edit', [$item->id]) }}" class="btn btn-outline-primary" title="@lang('admin.Edit')">
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-outline-danger" onclick="delete_single_id('email_template', {{ $item->id }})" title="@lang('admin.Delete')">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4 text-muted">
                                                    <i class="fa-solid fa-envelopes-bulk fs-2 d-block mb-2 text-secondary"></i>
                                                    Không có mẫu email nào.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- card-footer --}}
                        {{ $data->links('backend.pagination.custom') }}

                    </div>
                    {{-- end::card --}}
                </div>
            </div>
        </div>
    </div>
@endsection

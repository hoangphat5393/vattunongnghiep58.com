@extends('backend.layouts.master')
@section('seo')
    @php
        $title_head = __('admin.news');
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
                                @include('backend.partials.button_add_delete', ['type' => 'post', 'route' => route('admin.post.create')])
                                <div class="mt-3 mt-lg-0">
                                    <form method="GET" action="" id="frm-filter-post" class="form-inline">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="search_name" id="search_name" placeholder="@lang('admin.keyword')" value="{{ request('search_name') }}">
                                            <button type="submit" class="btn btn-outline-primary" aria-label="@lang('admin.search')">
                                                <i class="fa-regular fa-magnifying-glass"></i> @lang('admin.search')
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="my-4 d-flex justify-content-between align-items-center">
                                <div>
                                    <b>@lang('admin.total')</b>: <span class="fw-bold text-red">{{ $total_item ?? 0 }}</span> @lang('admin.news')
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
                                            <th class="text-center" style="width:80px">@lang('admin.sort')</th>
                                            <th>Tiêu đề bài viết</th>
                                            <th class="text-center" style="width:120px">@lang('admin.thumbnail')</th>
                                            <th class="text-center" style="width:110px">@lang('admin.created by')</th>
                                            <th class="text-center" style="width:105px"><i class="fa-solid fa-eye text-primary me-1"></i> Trạng thái</th>
                                            <th class="text-center" style="width:130px">@lang('admin.created date')</th>
                                            <th class="text-center" style="width:110px">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data as $item)
                                            <tr>
                                                <td class="text-center">
                                                    <div class="icheck-info d-inline">
                                                        <input type="checkbox" id="post-{{ $item->id }}" name="seq_list[]" value="{{ $item->id }}">
                                                        <label for="post-{{ $item->id }}"></label>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <input type="number" id="sort-{{ $item->id }}" class="form-control form-control-sm quick_change_value text-center mx-auto" style="max-width: 65px;" data-id="{{ $item->id }}" data-column="sort" data-model="{{ get_class($item) }}" value="{{ $item->sort }}" reload-on-change>
                                                </td>
                                                <td>
                                                    <a class="text-decoration-none fw-bold text-dark fs-6" href="{{ route('admin.post.edit', $item->id) }}">
                                                        <i class="fa-regular fa-newspaper text-primary me-1"></i>
                                                        {{ $item->name }}
                                                    </a>
                                                    <div class="mt-1">
                                                        <a class="text-decoration-none text-muted small" href="{{ route('news.detail', [$item->slug, $item->id]) }}" target="_blank" title="Xem trên website">
                                                            <i class="fa-solid fa-arrow-up-right-from-square me-1 text-primary"></i>/news/{{ $item->slug }}-{{ $item->id }}.html
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
                                                <td class="text-center">
                                                    @if ($item->user)
                                                        <span class="badge bg-light text-dark border">
                                                            <i class="fa-regular fa-user me-1 text-secondary"></i>{{ $item->user->name ?? $item->user->username }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-light text-secondary border">
                                                            <i class="fa-solid fa-user-shield me-1"></i>Admin
                                                        </span>
                                                    @endif
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
                                                        <a href="{{ route('news.detail', [$item->slug, $item->id]) }}" target="_blank" class="btn btn-outline-secondary" title="Xem trên website">
                                                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                                        </a>
                                                        <a href="{{ route('admin.post.edit', $item->id) }}" class="btn btn-outline-primary" title="@lang('admin.Edit')">
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-outline-danger" onclick="delete_single_id('post', {{ $item->id }})" title="@lang('admin.Delete')">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4 text-muted">
                                                    <i class="fa-regular fa-newspaper fs-2 d-block mb-2"></i>
                                                    Không có bài viết nào.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if (false)
                                <div class="float-end">
                                    {!! $data->links() !!}
                                </div>
                            @endif
                        </div> <!-- /.card-body -->

                        {{-- card-footer --}}
                        {{ $data->links('backend.pagination.custom') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

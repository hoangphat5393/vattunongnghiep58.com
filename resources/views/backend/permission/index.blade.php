@extends('backend.layouts.master')
@section('seo')
    @php
        $title_head = __('admin.List permission');
        $seo = [
            'title' => $title_head . ' | ' . setting_option('seo-title-add'),
            'keywords' => setting_option('seo-keywords-add'),
            'description' => setting_option('seo-description-add'),
            'og_title' => $title_head . ' | ' . setting_option('seo-title-add'),
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
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h1 class="mb-0"><i class="fa-solid fa-key me-2 text-primary fs-4"></i><span class="fs-4 fw-bold">{{ $title_head }}</span></h1>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb" class="float-sm-end">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house me-1"></i>@lang('admin.home')</a></li>
                            <li class="breadcrumb-item active">{{ $title_head }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline shadow-sm mb-4">
                        <div class="card-header bg-white py-3 border-bottom">
                            <div class="row g-3 align-items-center justify-content-between">
                                <div class="col-12 col-md-auto d-flex flex-wrap gap-2">
                                    <a class="btn btn-primary" href="{{ route('admin.permission.create') }}">
                                        <i class="fa-solid fa-plus me-1"></i> @lang('admin.create_new')
                                    </a>
                                    <button type="button" class="btn btn-outline-danger" onclick="delete_id('permission')">
                                        <i class="fa-solid fa-trash me-1"></i> @lang('admin.Delete')
                                    </button>
                                </div>
                                <div class="col-12 col-md-5 col-lg-4">
                                    <form method="GET" action="{{ route('admin.permission.index') }}" class="m-0">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="name" placeholder="Tìm tên quyền, slug, URI..." value="{{ request('name') }}">
                                            <button class="btn btn-primary" type="submit" title="@lang('admin.search')">
                                                <i class="fa-solid fa-magnifying-glass"></i>
                                            </button>
                                            @if (request('name'))
                                                <a href="{{ route('admin.permission.index') }}" class="btn btn-outline-secondary" title="Đặt lại">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="px-3 py-2 bg-light border-bottom d-flex align-items-center justify-content-between small text-muted">
                                <div>
                                    <i class="fa-solid fa-layer-group me-1"></i>@lang('admin.total'):
                                    <span class="badge bg-primary-subtle text-primary fw-bold">{{ $total_item ?? 0 }}</span> quyền
                                </div>
                                @if (request('name'))
                                    <div>
                                        Kết quả tìm kiếm cho: <span class="fw-bold text-dark">"{{ request('name') }}"</span>
                                    </div>
                                @endif
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" id="table_index">
                                    <thead class="table-light text-uppercase fs-7 text-secondary">
                                        <tr>
                                            <th class="text-center" style="width: 40px;">
                                                <div class="form-check d-inline-block">
                                                    <input class="form-check-input" type="checkbox" id="selectall" onclick="select_all()">
                                                </div>
                                            </th>
                                            <th style="width: 70px;">ID</th>
                                            <th><i class="fa-solid fa-shield-halved text-primary me-1"></i> @lang('admin.permission_name')</th>
                                            <th><i class="fa-solid fa-code text-secondary me-1"></i> @lang('admin.slug')</th>
                                            <th><i class="fa-solid fa-network-wired text-info me-1"></i> @lang('admin.http.path')</th>
                                            <th class="text-end pe-3" style="width: 140px;">@lang('admin.action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($permissions as $data)
                                            <tr>
                                                <td class="text-center">
                                                    <div class="form-check d-inline-block">
                                                        <input class="form-check-input" type="checkbox" id="{{ $data->id }}" name="seq_list[]" value="{{ $data->id }}">
                                                    </div>
                                                </td>
                                                <td class="text-muted small fw-semibold">#{{ $data->id }}</td>
                                                <td>
                                                    <a href="{{ route('admin.permission.edit', $data->id) }}" class="fw-bold text-decoration-none text-primary">
                                                        {{ $data->name }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <code class="px-2 py-1 bg-light rounded text-dark border font-monospace small">{{ $data->slug }}</code>
                                                </td>
                                                <td>
                                                    @if ($data->http_uri)
                                                        <div class="d-flex flex-wrap gap-1 align-items-center">
                                                            @foreach (explode(',', $data->http_uri) as $uriItem)
                                                                @php
                                                                    $parts = explode('::', $uriItem, 2);
                                                                    $method = $parts[0] ?? 'ANY';
                                                                    $path = $parts[1] ?? '';
                                                                    $methodClass = match ($method) {
                                                                        'ANY' => 'bg-secondary-subtle text-secondary border border-secondary-subtle',
                                                                        'GET' => 'bg-success-subtle text-success border border-success-subtle',
                                                                        'POST' => 'bg-warning-subtle text-warning border border-warning-subtle',
                                                                        'PUT', 'PATCH' => 'bg-info-subtle text-info border border-info-subtle',
                                                                        'DELETE' => 'bg-danger-subtle text-danger border border-danger-subtle',
                                                                        default => 'bg-primary-subtle text-primary border border-primary-subtle'
                                                                    };
                                                                @endphp
                                                                <span class="d-inline-flex align-items-center bg-body-tertiary border rounded px-2 py-1 small">
                                                                    <span class="badge {{ $methodClass }} me-1 font-monospace" style="font-size: 0.75rem;">{{ $method }}</span>
                                                                    <span class="font-monospace text-muted small">{{ $path }}</span>
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <span class="text-muted fst-italic small">--</span>
                                                    @endif
                                                </td>
                                                <td class="text-end pe-3">
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('admin.permission.edit', $data->id) }}" class="btn btn-outline-primary" title="@lang('admin.edit')">
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </a>
                                                        <form action="{{ route('admin.permission.destroy', $data->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa quyền này?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger" title="@lang('admin.delete')">
                                                                <i class="fa-solid fa-trash-can"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-5 text-muted">
                                                    <div class="my-3">
                                                        <i class="fa-regular fa-folder-open fa-3x mb-2 text-secondary opacity-50"></i>
                                                        <p class="mb-0 fw-semibold">Không tìm thấy quyền nào</p>
                                                        <small class="text-muted">Vui lòng thử lại với từ khóa khác hoặc bấm nút thêm quyền mới</small>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if ($permissions->hasPages())
                            <div class="card-footer bg-white border-top py-3">
                                {{ $permissions->links('backend.pagination.custom') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        function select_all() {
            var check = $('#selectall').is(':checked');
            $('input[name="seq_list[]"]').prop('checked', check);
        }
    </script>
@endpush

@extends('backend.layouts.master')
@section('seo')
    @php
        $title_head = __('admin.roles');
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
                    <h1 class="mb-0"><i class="fa-solid fa-user-shield me-2 text-primary fs-4"></i><span class="fs-4 fw-bold">{{ $title_head }}</span></h1>
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
                                    <a class="btn btn-primary" href="{{ route('admin.role.create') }}">
                                        <i class="fa-solid fa-plus me-1"></i> @lang('admin.create_new')
                                    </a>
                                    <button type="button" class="btn btn-outline-danger" onclick="delete_id('role')">
                                        <i class="fa-solid fa-trash me-1"></i> @lang('admin.Delete')
                                    </button>
                                </div>
                                <div class="col-12 col-md-5 col-lg-4">
                                    <form method="GET" action="{{ route('admin.role.index') }}" class="m-0">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="name" placeholder="Tìm tên vai trò, slug..." value="{{ request('name') }}">
                                            <button class="btn btn-primary" type="submit" title="@lang('admin.search')">
                                                <i class="fa-solid fa-magnifying-glass"></i>
                                            </button>
                                            @if (request('name'))
                                                <a href="{{ route('admin.role.index') }}" class="btn btn-outline-secondary" title="Đặt lại">
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
                                    <span class="badge bg-primary-subtle text-primary fw-bold">{{ $total_item ?? 0 }}</span> vai trò
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
                                            <th><i class="fa-solid fa-user-tag text-primary me-1"></i> @lang('admin.role_name')</th>
                                            <th><i class="fa-solid fa-code text-secondary me-1"></i> @lang('admin.slug')</th>
                                            <th><i class="fa-solid fa-shield-halved text-success me-1"></i> @lang('admin.permission')</th>
                                            <th class="text-end pe-3" style="width: 140px;">@lang('admin.action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($roles as $data)
                                            <tr>
                                                <td class="text-center">
                                                    <div class="form-check d-inline-block">
                                                        <input class="form-check-input" type="checkbox" id="{{ $data->id }}" name="seq_list[]" value="{{ $data->id }}">
                                                    </div>
                                                </td>
                                                <td class="text-muted small fw-semibold">#{{ $data->id }}</td>
                                                <td>
                                                    <a href="{{ route('admin.role.edit', $data->id) }}" class="fw-bold text-decoration-none text-primary">
                                                        {{ $data->name }}
                                                    </a>
                                                    @if (!empty($data->name_en))
                                                        <span class="text-muted small ms-1">({{ $data->name_en }})</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <code class="px-2 py-1 bg-light rounded text-dark border font-monospace small">{{ $data->slug }}</code>
                                                </td>
                                                <td>
                                                    @if ($data->permissions->count())
                                                        <div class="d-flex align-items-start gap-2">
                                                            <span class="badge bg-success-subtle text-success border border-success-subtle flex-shrink-0" style="font-size: 0.75rem;">
                                                                <i class="fa-solid fa-key me-1"></i>{{ $data->permissions->count() }} quyền
                                                            </span>
                                                            <div class="d-flex flex-wrap gap-1" style="max-height: 60px; overflow-y: auto;">
                                                                @foreach ($data->permissions as $p)
                                                                    <span class="badge bg-light text-secondary border font-monospace" style="font-size: 0.72rem;">{{ $p->name }}</span>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @else
                                                        <span class="text-muted fst-italic small">Chưa gán quyền</span>
                                                    @endif
                                                </td>
                                                <td class="text-end pe-3">
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('admin.role.edit', $data->id) }}" class="btn btn-outline-primary" title="@lang('admin.edit')">
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </a>
                                                        <form action="{{ route('admin.role.destroy', $data->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa vai trò này?');">
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
                                                        <p class="mb-0 fw-semibold">Không tìm thấy vai trò nào</p>
                                                        <small class="text-muted">Vui lòng thử lại với từ khóa khác hoặc bấm nút thêm vai trò mới</small>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if ($roles->hasPages())
                            <div class="card-footer bg-white border-top py-3">
                                {{ $roles->links('backend.pagination.custom') }}
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

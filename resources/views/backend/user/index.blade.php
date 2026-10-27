@extends('backend.layouts.master')
@section('seo')
    @php
        $title_head = __('admin.users');
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
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h1 class="mb-0"><i class="fa-solid fa-users-gear me-2 text-primary fs-4"></i><span class="fs-4 fw-bold">{{ $title_head }}</span></h1>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb" class="float-sm-end">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house me-1"></i>@lang('admin.dashboard')</a></li>
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
                                    <a class="btn btn-primary" href="{{ route('admin.user.create') }}">
                                        <i class="fa-solid fa-user-plus me-1"></i> @lang('admin.create_new')
                                    </a>
                                    <button type="button" class="btn btn-outline-danger" onclick="delete_id('user')">
                                        <i class="fa-solid fa-trash me-1"></i> @lang('admin.Delete')
                                    </button>
                                </div>
                                <div class="col-12 col-md-5 col-lg-4">
                                    <form method="GET" action="{{ route('admin.user.index') }}" class="m-0" id="frm-filter-user">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="name" placeholder="Tìm tên hoặc email..." value="{{ request('name') ?: request('search_name') }}">
                                            <button class="btn btn-primary" type="submit" title="@lang('admin.search')">
                                                <i class="fa-solid fa-magnifying-glass"></i>
                                            </button>
                                            @if (request('name') || request('search_name'))
                                                <a href="{{ route('admin.user.index') }}" class="btn btn-outline-secondary" title="Đặt lại">
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
                                    <span class="badge bg-primary-subtle text-primary fw-bold">{{ $total_item ?? 0 }}</span> @lang('admin.users')
                                </div>
                                @if (request('name') || request('search_name'))
                                    <div>
                                        Kết quả tìm kiếm cho: <span class="fw-bold text-dark">"{{ request('name') ?: request('search_name') }}"</span>
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
                                            <th><i class="fa-solid fa-user me-1 text-primary"></i> Họ tên / Tên đăng nhập</th>
                                            <th><i class="fa-regular fa-envelope me-1 text-secondary"></i> @lang('admin.email')</th>
                                            <th><i class="fa-solid fa-user-shield me-1 text-success"></i> @lang('admin.roles')</th>
                                            <th><i class="fa-regular fa-calendar-days me-1 text-info"></i> Ngày tham gia</th>
                                            <th class="text-end pe-3" style="width: 140px;">@lang('admin.action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($users as $item)
                                            <tr>
                                                <td class="text-center">
                                                    <div class="form-check d-inline-block">
                                                        <input class="form-check-input" type="checkbox" id="{{ $item->id }}" name="seq_list[]" value="{{ $item->id }}">
                                                    </div>
                                                </td>
                                                <td class="text-muted small fw-semibold">#{{ $item->id }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold me-2 shadow-sm flex-shrink-0"
                                                             style="width: 36px; height: 36px; font-size: 0.95rem; background: linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%);">
                                                            {{ mb_strtoupper(mb_substr($item->name ?? $item->username ?? 'U', 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <a href="{{ route('admin.user.edit', $item->id) }}" class="fw-bold text-decoration-none text-primary d-block">
                                                                {{ $item->name ?: $item->username }}
                                                            </a>
                                                            <small class="text-muted font-monospace"><i class="fa-regular fa-user me-1"></i>{{ $item->username }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-secondary small font-monospace">
                                                        <i class="fa-regular fa-envelope me-1 text-muted"></i>{{ $item->email }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($item->roles->count())
                                                        <div class="d-flex flex-wrap gap-1">
                                                            @foreach ($item->roles as $role)
                                                                @php
                                                                    $isSuper = Str::contains(Str::lower($role->slug ?? $role->name), ['admin', 'super']);
                                                                    $badgeClass = $isSuper
                                                                        ? 'bg-danger-subtle text-danger border border-danger-subtle'
                                                                        : 'bg-primary-subtle text-primary border border-primary-subtle';
                                                                @endphp
                                                                <span class="badge {{ $badgeClass }}">
                                                                    <i class="fa-solid fa-shield-halved me-1"></i>{{ $role->name }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">
                                                            Chưa phân vai trò
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="small text-muted">
                                                        <i class="fa-regular fa-clock me-1 text-muted"></i>{{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '--' }}
                                                    </span>
                                                </td>
                                                <td class="text-end pe-3">
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('admin.user.edit', $item->id) }}" class="btn btn-outline-primary" title="@lang('admin.edit')">
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </a>
                                                        @if (Auth::guard('admin')->id() != $item->id)
                                                            <form action="{{ route('admin.user.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa tài khoản này?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-outline-danger" title="@lang('admin.delete')">
                                                                    <i class="fa-solid fa-trash-can"></i>
                                                                </button>
                                                            </form>
                                                        @else
                                                            <button type="button" class="btn btn-outline-secondary disabled" title="Không thể tự xóa chính mình">
                                                                <i class="fa-solid fa-lock"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-5 text-muted">
                                                    <div class="my-3">
                                                        <i class="fa-solid fa-user-xmark fa-3x mb-2 text-secondary opacity-50"></i>
                                                        <p class="mb-0 fw-semibold">Không tìm thấy thành viên nào</p>
                                                        <small class="text-muted">Vui lòng thử lại với tên hoặc email khác</small>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if ($users->hasPages())
                            <div class="card-footer bg-white border-top py-3">
                                {{ $users->links('backend.pagination.custom') }}
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

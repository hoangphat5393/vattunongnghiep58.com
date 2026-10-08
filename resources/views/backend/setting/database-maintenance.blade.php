@extends('backend.layouts.master')

@section('seo')
    @php
        $title_head = 'Bảo trì CSDL';
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
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0"><i class="fa-light fa-database me-2"></i>{{ $title_head }}</h1>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb" class="float-sm-end">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.theme-option') }}">Cài đặt</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Bảo trì CSDL</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa-light fa-circle-check me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa-light fa-triangle-exclamation me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="alert alert-info border-info mb-4" role="alert">
                <h5 class="alert-heading mb-2"><i class="fa-light fa-shield-halved me-2"></i>Nguyên tắc vận hành an toàn:</h5>
                <ul class="mb-0 ps-3">
                    <li><strong>Bảng đang có dữ liệu:</strong> Hệ thống tự động đồng bộ ID tiếp theo bằng <code>Max(ID) + 1</code>. Tuyệt đối không reset về 1 để tránh xung đột khóa chính (Duplicate Key).</li>
                    <li><strong>Bảng hoàn toàn rỗng:</strong> Hệ thống cho phép đưa ID tự tăng về <code>1</code> nguyên bản tinh khôi (như sau khi vừa migrate).</li>
                    <li>Thao tác này tách biệt độc lập, không làm ảnh hưởng đến hiệu năng thêm/xóa bài viết thông thường.</li>
                </ul>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 text-primary">
                        <i class="fa-light fa-table me-2"></i>Trạng thái ID của các bảng dữ liệu
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>STT</th>
                                    <th>Tên Bảng (Table)</th>
                                    <th>Khóa chính</th>
                                    <th class="text-center">Số bản ghi</th>
                                    <th class="text-center">Max ID</th>
                                    <th class="text-center">ID kế tiếp</th>
                                    <th class="text-center">Trạng thái</th>
                                    <th class="text-end pe-3">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stats as $index => $item)
                                    <tr id="row-{{ $item['table'] }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong class="text-dark"><code>{{ $item['table'] }}</code></strong>
                                            @if ($item['sequence_name'])
                                                <div class="small text-muted">{{ $item['sequence_name'] }}</div>
                                            @endif
                                        </td>
                                        <td><code>{{ $item['pk'] }}</code></td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary px-2 py-1">{{ number_format($item['total_rows']) }}</span>
                                        </td>
                                        <td class="text-center fw-bold">{{ $item['max_id'] }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-primary px-2 py-1" id="next-id-{{ $item['table'] }}">{{ $item['next_id'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if ($item['can_reset_to_one'])
                                                <span class="badge bg-success-subtle text-success border border-success">Bảng rỗng (Sẵn sàng về 1)</span>
                                            @else
                                                <span class="badge bg-light text-dark border">Đang hoạt động</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-3">
                                            <button type="button" 
                                                class="btn btn-sm {{ $item['can_reset_to_one'] ? 'btn-outline-success' : 'btn-outline-primary' }} btn-reset-seq"
                                                data-table="{{ $item['table'] }}"
                                                data-empty="{{ $item['can_reset_to_one'] ? '1' : '0' }}"
                                                data-rows="{{ $item['total_rows'] }}"
                                                data-max="{{ $item['max_id'] }}">
                                                <i class="fa-light {{ $item['can_reset_to_one'] ? 'fa-rotate-left' : 'fa-arrows-rotate' }} me-1"></i>
                                                {{ $item['can_reset_to_one'] ? 'Reset ID về 1' : 'Đồng bộ ID' }}
                                            </button>
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

    {{-- Modal Xác nhận bảo trì --}}
    <div class="modal fade" id="modalConfirmSequence" tabindex="-1" aria-labelledby="modalConfirmLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="frmResetSequence" action="{{ route('admin.database-maintenance.reset') }}" method="POST">
                    @csrf
                    <input type="hidden" name="table" id="modal-table-input" value="">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalConfirmLabel"><i class="fa-light fa-triangle-exclamation text-warning me-2"></i>Xác nhận thao tác</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modalConfirmBody">
                        Bạn có chắc chắn muốn thực hiện thao tác này cho bảng dữ liệu?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy bỏ</button>
                        <button type="submit" class="btn btn-primary" id="btnConfirmSubmit">Thực hiện ngay</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('modalConfirmSequence');
    const modal = new bootstrap.Modal(modalEl);
    const tableInput = document.getElementById('modal-table-input');
    const modalBody = document.getElementById('modalConfirmBody');

    document.querySelectorAll('.btn-reset-seq').forEach(btn => {
        btn.addEventListener('click', function () {
            const table = this.getAttribute('data-table');
            const isEmpty = this.getAttribute('data-empty') === '1';
            const rows = this.getAttribute('data-rows');
            const maxId = this.getAttribute('data-max');

            tableInput.value = table;

            if (isEmpty) {
                modalBody.innerHTML = `Bảng <strong><code>${table}</code></strong> hiện không có dữ liệu.<br><br>Hệ thống sẽ <strong>reset ID tự tăng về 1</strong>. Bản ghi mới tiếp theo sẽ nhận <code>ID = 1</code>.<br><br>Bạn có muốn tiếp tục?`;
            } else {
                modalBody.innerHTML = `Bảng <strong><code>${table}</code></strong> hiện có <strong>${rows}</strong> dòng (Max ID = <strong>${maxId}</strong>).<br><br>Hệ thống sẽ đồng bộ ID để bản ghi tiếp theo nhận ID <strong>${parseInt(maxId) + 1}</strong> an toàn, tránh trùng lặp khóa chính.<br><br>Bạn có muốn tiếp tục?`;
            }

            modal.show();
        });
    });
});
</script>
@endpush

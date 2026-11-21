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

            <div class="card shadow-sm border-0 mb-4" id="dbExportCard">
                <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <h5 class="card-title mb-0 text-success">
                        <i class="fa-light fa-file-export me-2"></i>Xuất Database đa định dạng (MySQL / PostgreSQL / MongoDB)
                    </h5>
                    <span class="badge bg-light text-dark border">1-Click Export &amp; Download</span>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        @php
                            $formatMeta = [
                                'mysql' => [
                                    'title' => 'MySQL / MariaDB (.sql)',
                                    'desc' => 'Đồng bộ sang MySQL (chuẩn InnoDB, utf8mb4, JSONB, AUTO_INCREMENT) để import phpMyAdmin / HeidiSQL.',
                                    'icon' => 'fa-database text-primary',
                                ],
                                'pgsql' => [
                                    'title' => 'PostgreSQL (.sql)',
                                    'desc' => 'Xuất trực tiếp bằng pg_dump (Plain UTF-8 SQL) để khôi phục nhanh lên PostgreSQL / DBeaver / pgAdmin.',
                                    'icon' => 'fa-server text-info',
                                ],
                                'mongodb' => [
                                    'title' => 'MongoDB (.zip — JSON)',
                                    'desc' => 'Xuất toàn bộ bảng thành các file .json chuẩn MongoDB Compass & kèm script mongoimport tự động.',
                                    'icon' => 'fa-leaf text-success',
                                ],
                            ];
                        @endphp

                        @foreach ($formatMeta as $fmtKey => $fmtInfo)
                            @php
                                $st = $exportStatuses[$fmtKey] ?? [];
                                $isReady = (bool) ($st['ready'] ?? false);
                                $exists = (bool) ($st['dump_exists'] ?? false);
                            @endphp
                            <div class="col-md-4">
                                <label class="db-export-option card h-100 border {{ $fmtKey === 'mysql' ? 'border-success bg-success-subtle bg-opacity-10' : 'border-secondary-subtle' }} p-3 mb-0"
                                    style="cursor: pointer;"
                                    data-format="{{ $fmtKey }}"
                                    data-ready="{{ $isReady ? '1' : '0' }}"
                                    data-exists="{{ $exists ? '1' : '0' }}"
                                    data-download-url="{{ $st['download_url'] ?? route('admin.database-maintenance.download', ['format' => $fmtKey]) }}">
                                    <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input db-export-radio"
                                                type="radio"
                                                name="db_export_format"
                                                value="{{ $fmtKey }}"
                                                {{ $fmtKey === 'mysql' ? 'checked' : '' }}>
                                            <span class="fw-bold text-dark">
                                                <i class="fa-light {{ $fmtInfo['icon'] }} me-1"></i>{{ $fmtInfo['title'] }}
                                            </span>
                                        </div>
                                        <span class="badge {{ $isReady ? 'bg-success-subtle text-success border border-success' : 'bg-danger-subtle text-danger border border-danger' }}">
                                            {{ $isReady ? 'Sẵn sàng' : 'Chưa sẵn sàng' }}
                                        </span>
                                    </div>
                                    <p class="small text-muted mb-2">{{ $fmtInfo['desc'] }}</p>
                                    <div class="mt-auto pt-2 border-top small text-secondary" id="export-meta-{{ $fmtKey }}">
                                        @if ($exists)
                                            <i class="fa-light fa-clock me-1"></i>{{ $st['updated_at'] }} • <strong>{{ $st['size_human'] }}</strong>
                                        @else
                                            <i class="fa-light fa-circle-info me-1"></i>Chưa có bản xuất ({{ $st['status_text'] ?? '' }})
                                        @endif
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 pt-2 border-top">
                        <div class="small text-muted" id="dbExportSelectedHint">
                            Định dạng đang chọn: <strong class="text-dark">MySQL / MariaDB (.sql)</strong>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button"
                                id="btnExportDatabase"
                                class="btn btn-success"
                                data-url="{{ route('admin.database-maintenance.export') }}"
                                @if (!($exportStatuses['mysql']['ready'] ?? false)) disabled @endif>
                                <i class="fa-light fa-cloud-arrow-down me-1"></i>
                                <span class="btn-label">Xuất &amp; Tải Database</span>
                            </button>
                            <a href="{{ $exportStatuses['mysql']['download_url'] ?? route('admin.database-maintenance.download', ['format' => 'mysql']) }}"
                                id="btnDownloadLastExport"
                                class="btn btn-outline-primary {{ ($exportStatuses['mysql']['dump_exists'] ?? false) ? '' : 'disabled' }}">
                                <i class="fa-light fa-download me-1"></i>Tải bản gần nhất
                            </a>
                        </div>
                    </div>
                </div>
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

    const exportOptions = document.querySelectorAll('.db-export-option');
    const btnExportDatabase = document.getElementById('btnExportDatabase');
    const btnDownloadLastExport = document.getElementById('btnDownloadLastExport');
    const selectedHint = document.getElementById('dbExportSelectedHint');

    const formatLabels = {
        mysql: 'MySQL / MariaDB (.sql)',
        pgsql: 'PostgreSQL (.sql)',
        mongodb: 'MongoDB (.zip — JSON)',
    };

    const getSelectedFormat = () => {
        const checked = document.querySelector('.db-export-radio:checked');
        return checked ? checked.value : 'mysql';
    };

    const syncExportButtons = () => {
        const fmt = getSelectedFormat();
        exportOptions.forEach((card) => {
            const isSelected = card.getAttribute('data-format') === fmt;
            card.classList.toggle('border-success', isSelected);
            card.classList.toggle('bg-success-subtle', isSelected);
            card.classList.toggle('bg-opacity-10', isSelected);
            card.classList.toggle('border-secondary-subtle', !isSelected);

            if (isSelected) {
                const isReady = card.getAttribute('data-ready') === '1';
                const exists = card.getAttribute('data-exists') === '1';
                const dlUrl = card.getAttribute('data-download-url');

                if (btnExportDatabase) {
                    btnExportDatabase.disabled = !isReady;
                }
                if (btnDownloadLastExport && dlUrl) {
                    btnDownloadLastExport.href = dlUrl;
                    btnDownloadLastExport.classList.toggle('disabled', !exists);
                }
                if (selectedHint) {
                    selectedHint.innerHTML = `Định dạng đang chọn: <strong class="text-dark">${formatLabels[fmt] || fmt}</strong>`;
                }
            }
        });
    };

    document.querySelectorAll('.db-export-radio').forEach((radio) => {
        radio.addEventListener('change', syncExportButtons);
    });
    syncExportButtons();

    if (btnExportDatabase) {
        btnExportDatabase.addEventListener('click', async function () {
            const format = getSelectedFormat();
            const url = this.getAttribute('data-url');
            const labelEl = this.querySelector('.btn-label');
            const originalText = labelEl ? labelEl.textContent : '';
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                || document.querySelector('input[name="_token"]')?.value
                || '';

            this.disabled = true;
            if (labelEl) {
                labelEl.textContent = `Đang xuất ${format.toUpperCase()}...`;
            }

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({ format }),
                });

                const payload = await response.json();

                if (!response.ok || payload.status !== 'success') {
                    throw new Error(payload.message || 'Xuất database thất bại.');
                }

                const metaEl = document.getElementById(`export-meta-${format}`);
                if (metaEl && payload.data) {
                    metaEl.innerHTML = `<i class="fa-light fa-clock me-1"></i>${payload.data.updated_at} • <strong>${payload.data.size_human}</strong>`;
                }

                const activeCard = document.querySelector(`.db-export-option[data-format="${format}"]`);
                if (activeCard) {
                    activeCard.setAttribute('data-exists', '1');
                }
                syncExportButtons();

                if (window.Swal) {
                    window.Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: payload.message,
                        showConfirmButton: false,
                        timer: 3500,
                        timerProgressBar: true,
                    });
                }

                if (payload.data && payload.data.download_url) {
                    window.location.href = payload.data.download_url;
                }
            } catch (error) {
                if (window.Swal) {
                    window.Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: error.message || 'Không thể xuất database.',
                        showConfirmButton: false,
                        timer: 4500,
                        timerProgressBar: true,
                    });
                } else {
                    alert(error.message || 'Không thể xuất database.');
                }
            } finally {
                if (labelEl) {
                    labelEl.textContent = originalText;
                }
                syncExportButtons();
            }
        });
    }
});
</script>
@endpush

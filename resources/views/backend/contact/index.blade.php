@extends('backend.layouts.master')
@section('seo')
    @php
        $title_head = __('admin.contact');
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
                    <div class="mb-4 card card-primary card-outline">
                        {{-- header --}}
                        <div class="card-header">
                            <h3 class="card-title">{{ $title_head }}</h3>
                        </div>

                        {{-- body --}}
                        <div class="card-body">
                            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                                <div>
                                    @include('backend.partials.button_delete', ['type' => 'contact'])
                                </div>
                                <div>
                                    <form method="GET" action="" id="frm-filter-contact" class="d-flex flex-wrap align-items-center gap-2">
                                        <select name="type" class="form-select" style="min-width: 170px;">
                                            <option value="">Tất cả phân loại</option>
                                            <option value="contact" @selected(request('type') == 'contact')>Liên hệ (Contact)</option>
                                            <option value="subscription" @selected(request('type') == 'subscription')>Đăng ký nhận tin</option>
                                        </select>
                                        <div class="input-group" style="min-width: 260px;">
                                            <input type="text" class="form-control" id="keyword" name="keyword" placeholder="Tìm họ tên, email, SĐT..." value="{{ request('keyword', request('name')) }}">
                                            <button class="btn btn-outline-primary" type="submit" id="button-search">
                                                <i class="fa-solid fa-magnifying-glass"></i> @lang('admin.search')
                                            </button>
                                        </div>
                                        @if (request()->hasAny(['type', 'keyword', 'name']))
                                            <a href="{{ route('admin.contact.index') }}" class="btn btn-outline-secondary" title="Xóa bộ lọc">
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
                                            <th class="text-center" style="width:45px">
                                                <div class="icheck-info d-inline">
                                                    <input type="checkbox" id="selectall" onclick="select_all()">
                                                    <label for="selectall"></label>
                                                </div>
                                            </th>
                                            <th scope="col" class="text-center" style="width:55px">#</th>
                                            <th scope="col" style="min-width:200px">Người gửi / Khách hàng</th>
                                            <th scope="col" style="width: 260px; max-width: 280px;">Nội dung liên hệ</th>
                                            <th scope="col" class="text-center" style="width:140px">@lang('admin.type')</th>
                                            <th scope="col" class="text-center" style="width:130px">@lang('admin.created date')</th>
                                            <th scope="col" class="text-center" style="width:110px">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data as $item)
                                            <tr>
                                                <td class="text-center">
                                                    <div class="icheck-info d-inline">
                                                        <input type="checkbox" id="contact-{{ $item->id }}" name="seq_list[]" value="{{ $item->id }}">
                                                        <label for="contact-{{ $item->id }}"></label>
                                                    </div>
                                                </td>
                                                <td class="text-center text-muted fw-bold">{{ $item->id }}</td>
                                                <td>
                                                    <div class="d-flex align-items-start gap-2">
                                                        <div class="contact-avatar mt-1">
                                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-circle p-2">
                                                                <i class="fa-solid fa-user fs-6"></i>
                                                            </span>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <a class="text-decoration-none fw-bold text-dark fs-6" href="{{ route('admin.contact.edit', $item->id) }}">
                                                                {{ $item->name ?: 'Không tên' }}
                                                            </a>
                                                            <div class="small mt-1 text-muted">
                                                                @if (!empty($item->phone))
                                                                    <div class="d-inline-block me-3">
                                                                        <a href="tel:{{ $item->phone }}" class="text-decoration-none text-success">
                                                                            <i class="fa-solid fa-phone me-1"></i>{{ $item->phone }}
                                                                        </a>
                                                                    </div>
                                                                @endif
                                                                @if (!empty($item->email))
                                                                    <div class="d-inline-block">
                                                                        <a href="mailto:{{ $item->email }}" class="text-decoration-none text-info">
                                                                            <i class="fa-solid fa-envelope me-1"></i>{{ $item->email }}
                                                                        </a>
                                                                    </div>
                                                                @endif
                                                                @if (!empty($item->address))
                                                                    <div class="text-muted small mt-1">
                                                                        <i class="fa-solid fa-location-dot me-1 text-secondary"></i>{{ $item->address }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td style="max-width: 280px;">
                                                    @if (!empty($item->content))
                                                        @php
                                                            $plainContent = trim(strip_tags($item->content));
                                                        @endphp
                                                        <div class="contact-content-preview text-body" title="{{ $plainContent }}" style="max-width: 260px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; word-break: break-all; overflow-wrap: anywhere; line-height: 1.45; font-size: 0.88rem;">
                                                            {{ $plainContent }}
                                                        </div>
                                                        @if (mb_strlen($plainContent) > 70)
                                                            <a href="javascript:void(0)" class="text-decoration-none small text-primary view-contact-msg mt-1 d-inline-block" data-name="{{ $item->name ?: 'Khách hàng' }}" data-content="{{ e($plainContent) }}">
                                                                <i class="fa-regular fa-comment-dots me-1"></i>Xem thêm
                                                            </a>
                                                        @endif
                                                    @else
                                                        <span class="text-muted small">— Không có nội dung —</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($item->type === 'subscription')
                                                        <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1">
                                                            <i class="fa-regular fa-bell me-1"></i>Đăng ký tin
                                                        </span>
                                                    @elseif ($item->type === 'contact')
                                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                                            <i class="fa-regular fa-paper-plane me-1"></i>Liên hệ
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary-subtle text-secondary border px-2 py-1">
                                                            {{ $item->type ?: 'Mặc định' }}
                                                        </span>
                                                    @endif
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
                                                        <a href="{{ route('admin.contact.edit', $item->id) }}" class="btn btn-outline-primary" title="Xem chi tiết">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-outline-danger" onclick="delete_single_id('contact', {{ $item->id }})" title="@lang('admin.Delete')">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-4 text-muted">
                                                    <i class="fa-regular fa-envelope-open fs-2 d-block mb-2 text-secondary"></i>
                                                    Không có thông tin liên hệ nào.
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
    {{-- end::App Content --}}

    {{-- Modal xem nhanh nội dung liên hệ --}}
    <div class="modal fade" id="contactMsgModal" tabindex="-1" aria-labelledby="contactMsgModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow border-0">
                <div class="modal-header bg-light py-2 px-3">
                    <h5 class="modal-title fs-6 fw-bold" id="contactMsgModalLabel">
                        <i class="fa-regular fa-envelope-open text-primary me-2"></i>Nội dung liên hệ
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="fw-bold mb-2 text-dark small" id="modalContactSender"></div>
                    <div class="p-3 bg-light rounded border text-body text-break" id="modalContactBody" style="white-space: pre-wrap; font-size: 0.9rem; max-height: 350px; overflow-y: auto; line-height: 1.5;"></div>
                </div>
                <div class="modal-footer py-2 px-3 bg-white">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $(document).on('click', '.view-contact-msg', function() {
                var name = $(this).data('name');
                var content = $(this).data('content');
                $('#modalContactSender').html('<i class="fa-solid fa-user me-1 text-primary"></i> <b>Người gửi:</b> ' + $('<div>').text(name).html());
                $('#modalContactBody').text(content);
                var modal = new bootstrap.Modal(document.getElementById('contactMsgModal'));
                modal.show();
            });
        });
    </script>
@endpush

@extends('backend.layouts.master')
@section('seo')
    @php
        $title_head = __('admin.album_list');
        $seo = [
            'title' => $title_head . ' | ' . setting_option('seo-title-add'),
            'keywords' => setting_option('seo-keywords-add'),
            'description' => setting_option('seo-description-add'),
            'og_title' => $title_head,
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
    {{-- begin::App Content Header --}}
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0">{{ $title_head }}</h1>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb" class="float-sm-end"><ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('admin.home')</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $title_head }}</li>
                    </ol></nav>
                </div>
            </div>
        </div>
    </div>
    {{-- end::App Content Header --}}

    {{-- begin::App Content --}}
    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline mb-4">

                        <div class="card-header">
                            <h3 class="card-title">{{ $title_head }}</h3>
                        </div>

                        <div class="card-body">

                            <div class="d-flex flex-column flex-lg-row justify-content-between">
                                @include('backend.partials.button_add_delete', ['type' => 'album', 'route' => route('admin.album.create')])

                                <div class="mt-3 mt-lg-0">
                                    <form method="GET" action="" id="frm-filter-post" class="form-inline">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="name" name="name" placeholder="Tìm tên album..." aria-label="Tìm tên album" value="{{ request('name') }}">
                                            <button class="btn btn-outline-primary" type="submit" id="button-addon2" aria-label="@lang('admin.search')">
                                                <i class="fa-solid fa-magnifying-glass"></i> @lang('admin.search')
                                            </button>
                                            @if (request()->hasAny(['name', 'keyword']))
                                                <a href="{{ route('admin.album.index') }}" class="btn btn-outline-secondary" title="Xóa bộ lọc">
                                                    <i class="fa-solid fa-rotate-left"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="my-4 d-flex justify-content-between align-items-center">
                                <div>
                                    <b>@lang('admin.total')</b>: <span class="fw-bold text-red">{{ $total_item ?? 0 }}</span> Album
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
                                            <th class="text-center" style="width:80px">@lang('admin.priority')</th>
                                            <th class="text-center" style="width:110px">Ảnh bìa</th>
                                            <th scope="col">Tên Album</th>
                                            <th class="text-center" style="width:230px">Mã nhúng (Shortcode)</th>
                                            <th class="text-center" style="width:105px"><i class="fa-solid fa-eye text-primary me-1"></i> Trạng thái</th>
                                            <th class="text-center" style="width:130px">@lang('admin.created date')</th>
                                            <th class="text-center" style="width:100px">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($album as $item)
                                            @php
                                                $firstItem = $item->items->first();
                                                $thumb = $firstItem?->image;
                                                $count = $item->items_count ?? $item->items->count();
                                                $rawDate = $item->updated_at ?: $item->created_at;
                                                $shortcode = '[Album id="' . $item->id . '" items="0"]';
                                            @endphp
                                            <tr>
                                                <td class="text-center">
                                                    <div class="icheck-info d-inline">
                                                        <input type="checkbox" id="album-{{ $item->id }}" name="seq_list[]" value="{{ $item->id }}">
                                                        <label for="album-{{ $item->id }}"></label>
                                                    </div>
                                                </td>

                                                <td class="text-center">
                                                    <input type="number" id="sort-{{ $item->id }}" class="form-control form-control-sm quick_change_value text-center mx-auto" style="max-width: 65px;" data-id="{{ $item->id }}" data-column="sort" data-model="{{ get_class($item) }}" value="{{ $item->sort }}" reload-on-change>
                                                </td>

                                                <td class="text-center" style="width: 110px; min-width: 110px; max-width: 110px;">
                                                    <div class="cat-thumb-box position-relative" style="width: 90px; height: 60px; max-width: 90px; max-height: 60px; overflow: hidden; display: inline-block;">
                                                        @if (!empty($thumb))
                                                            <img src="{{ get_image($thumb) }}" class="cat-thumb-img" alt="{{ $item->name }}" loading="lazy" style="width: 90px; height: 60px; max-width: 90px; max-height: 60px; object-fit: cover; display: block;">
                                                        @else
                                                            <div class="cat-thumb-placeholder" style="width: 90px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                                                <i class="fa-regular fa-images fs-5"></i>
                                                            </div>
                                                        @endif
                                                        @if ($count > 0)
                                                            <span class="badge bg-dark bg-opacity-75 text-white position-absolute bottom-0 end-0 m-1 px-1 py-0" style="font-size: 0.68rem;" title="{{ $count }} hình ảnh">
                                                                <i class="fa-regular fa-image me-1"></i>{{ $count }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </td>

                                                <td>
                                                    <a class="text-decoration-none fw-bold text-dark fs-6" href="{{ route('admin.album.edit', $item->id) }}">
                                                        <i class="fa-regular fa-folder-open text-warning me-1"></i>
                                                        {{ $item->name }}
                                                    </a>
                                                    <div class="small text-muted mt-1">
                                                        <span class="badge bg-light text-secondary border me-1">
                                                            <i class="fa-regular fa-images me-1 text-primary"></i>{{ $count }} ảnh
                                                        </span>
                                                        <span class="text-muted small">ID: #{{ $item->id }}</span>
                                                    </div>
                                                </td>

                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control font-monospace bg-light" value="{{ $shortcode }}" readonly id="sc-{{ $item->id }}" style="font-size: 0.78rem;">
                                                        <button class="btn btn-outline-secondary" type="button" onclick="copyShortcode(this, '{{ $shortcode }}')" title="Sao chép mã ngắn">
                                                            <i class="fa-regular fa-copy"></i>
                                                        </button>
                                                    </div>
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
                                                    <div>
                                                        <i class="fa-regular fa-calendar-check me-1 text-secondary"></i>{{ $rawDate ? \Carbon\Carbon::parse($rawDate)->format('d/m/Y') : '-' }}
                                                    </div>
                                                    <small class="text-secondary">{{ $rawDate ? \Carbon\Carbon::parse($rawDate)->format('H:i:s') : '' }}</small>
                                                </td>

                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <a href="{{ route('admin.album.edit', $item->id) }}" class="btn btn-outline-primary" title="@lang('admin.Edit')">
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-outline-danger" onclick="delete_single_id('album', {{ $item->id }})" title="@lang('admin.Delete')">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4 text-muted">
                                                    <i class="fa-regular fa-images fs-2 d-block mb-2 text-secondary"></i>
                                                    Không có album nào.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                        </div> <!-- /.card-body -->

                        {{-- card-footer --}}
                        {{ $album->links('backend.pagination.custom') }}
                    </div><!-- /.card -->
                </div> <!-- /.col -->
            </div> <!-- /.row -->
        </div> <!-- /.container-fluid -->
    </div>
@endsection

@push('scripts')
<script>
function copyShortcode(btn, code) {
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(code).then(function() {
            showCopySuccess(btn);
        }).catch(function() {
            fallbackCopy(btn, code);
        });
    } else {
        fallbackCopy(btn, code);
    }
}

function fallbackCopy(btn, code) {
    const tempInput = document.createElement('input');
    tempInput.value = code;
    document.body.appendChild(tempInput);
    tempInput.select();
    try {
        document.execCommand('copy');
        showCopySuccess(btn);
    } catch (e) {
        prompt('Sao chép mã ngắn:', code);
    }
    document.body.removeChild(tempInput);
}

function showCopySuccess(btn) {
    const originalHtml = btn.innerHTML;
    btn.innerHTML = '<i class="fa-solid fa-check text-success"></i>';
    btn.classList.add('border-success');
    setTimeout(function() {
        btn.innerHTML = originalHtml;
        btn.classList.remove('border-success');
    }, 1500);
}
</script>
@endpush

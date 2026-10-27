@php
    if (isset($album_item)) {
        extract($album_item->getAttributes());
    }
@endphp

<form id="form-editSlider" action="" method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="album_id" value="{{ $parent ?? 0 }}">

    {{-- Alert hiển thị lỗi form validation --}}
    <div class="alert alert-danger modal-error-alert d-none mb-3" role="alert">
        <div class="d-flex align-items-start">
            <i class="fa-solid fa-circle-exclamation me-2 fs-5 flex-shrink-0 mt-1"></i>
            <div class="modal-error-content small"></div>
        </div>
    </div>

    {{-- Tab Lang --}}
    <ul class="nav nav-tabs d-none" id="tabLang" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="vi-tab" data-bs-toggle="tab" href="#vi-child" role="tab" aria-controls="vi-child" aria-selected="true">@lang('admin.Vietnamese')</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="en-tab" data-bs-toggle="tab" href="#en-child" role="tab" aria-controls="en-child" aria-selected="false">@lang('admin.English')</a>
        </li>
    </ul>

    {{-- VI --}}
    <div class="tab-content">
        <div class="tab-pane fade show active" id="vi-child" role="tabpanel" aria-labelledby="vi-tab">

            <div class="mb-3">
                <label class="form-label fw-semibold text-dark">@lang('admin.image')</label>
                <div class="d-flex align-items-center gap-3">
                    <div class="position-relative album-img-preview-box" style="width: 130px; height: 90px; border: 2px dashed #cbd5e1; border-radius: 8px; overflow: hidden; background: #f8fafc; display: flex; align-items: center; justify-content: center; cursor: pointer;" title="Nhấn để chọn ảnh" onclick="chooseAlbumImage('src-img-vi', 'show-img-vi')">
                        <input type="hidden" name="image" id="src-img-vi" value="{{ $image ?? '' }}">
                        <img src="{{ get_image($image ?? '') }}" id="show-img-vi" class="show-img-vi src-img-vi" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.onerror=null;this.src='{{ asset('assets/images/placeholder.png') }}';">
                    </div>
                    <div>
                        <button type="button" class="btn btn-sm btn-outline-primary mb-2 d-block" onclick="chooseAlbumImage('src-img-vi', 'show-img-vi')">
                            <i class="fa-regular fa-image me-1"></i> Chọn ảnh
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger d-block" onclick="clearAlbumImage('src-img-vi', 'show-img-vi')">
                            <i class="fa-solid fa-trash-can me-1"></i> Xóa ảnh
                        </button>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-dark" for="link">@lang('admin.link')</label>
                <input type="text" class="form-control" id="link" name="link" value="{{ $link ?? '#' }}">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-dark" for="link_name">@lang('admin.link name')</label>
                <input type="text" class="form-control" id="link_name" name="link_name" value="{{ $link_name ?? '' }}">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-dark" for="name">
                    @lang('admin.title') <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $name ?? '' }}" placeholder="@lang('admin.title')">
                <div class="invalid-feedback">Vui lòng nhập tiêu đề.</div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-dark" for="sub_name">@lang('admin.sub title')</label>
                <input type="text" class="form-control" id="sub_name" name="sub_name" value="{{ $sub_name ?? '' }}" placeholder="@lang('admin.sub title')">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-dark" for="description">@lang('admin.description')</label>
                <textarea id="description" class="form-control" name="description">{!! $description ?? '' !!}</textarea>
            </div>
        </div>

        {{-- EN --}}
        <div class="tab-pane fade" id="en-child" role="tabpanel" aria-labelledby="en-tab">

            <div class="mb-3">
                <label class="form-label fw-semibold text-dark" for="name_en">@lang('admin.image')</label>
                <div class="d-flex align-items-center gap-3">
                    <div class="position-relative album-img-preview-box" style="width: 130px; height: 90px; border: 2px dashed #cbd5e1; border-radius: 8px; overflow: hidden; background: #f8fafc; display: flex; align-items: center; justify-content: center; cursor: pointer;" title="Nhấn để chọn ảnh" onclick="chooseAlbumImage('src-img-en', 'show-img-en')">
                        <input type="hidden" name="image_en" id="src-img-en" value="{{ $image_en ?? '' }}">
                        <img src="{{ get_image($image_en ?? '') }}" id="show-img-en" class="show-img-en src-img-en" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.onerror=null;this.src='{{ asset('assets/images/placeholder.png') }}';">
                    </div>
                    <div>
                        <button type="button" class="btn btn-sm btn-outline-primary mb-2 d-block" onclick="chooseAlbumImage('src-img-en', 'show-img-en')">
                            <i class="fa-regular fa-image me-1"></i> Chọn ảnh
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger d-block" onclick="clearAlbumImage('src-img-en', 'show-img-en')">
                            <i class="fa-solid fa-trash-can me-1"></i> Xóa ảnh
                        </button>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-dark" for="link_en">@lang('admin.link')</label>
                <input type="text" class="form-control" id="link_en" name="link_en" value="{{ $link_en ?? '#' }}">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-dark" for="link_name_en">@lang('admin.link name')</label>
                <input type="text" class="form-control" id="link_name_en" name="link_name_en" value="{{ $link_name_en ?? '' }}">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-dark" for="name_en">
                    @lang('admin.title') <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control" id="name_en" name="name_en" placeholder="@lang('admin.title')" value="{{ $name_en ?? '' }}">
                <div class="invalid-feedback">Vui lòng nhập tiêu đề.</div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-dark" for="sub_name_en">@lang('admin.sub title')</label>
                <input type="text" class="form-control" id="sub_name_en" name="sub_name_en" value="{{ $sub_name_en ?? '' }}" placeholder="@lang('admin.sub title')">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-dark" for="description_en">@lang('admin.description')</label>
                <textarea id="description_en" class="form-control" name="description_en">{!! $description_en ?? '' !!}</textarea>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold text-dark" for="sort">@lang('admin.sort')</label>
        <input id="sort" type="text" name="sort" class="form-control" value="{{ $sort ?? 0 }}">
    </div>
</form>

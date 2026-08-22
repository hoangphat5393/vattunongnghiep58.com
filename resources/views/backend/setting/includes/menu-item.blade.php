@php
    /** @var \App\Models\Backend\MenuItems $m */
    $currentUrl = $currentUrl ?? url()->current();
@endphp
<li id="menu-item-{{ $m->id }}" class="menu-item menu-item-depth-{{ $m->depth }} menu-item-page menu-item-edit-inactive pending">
    <dl class="menu-item-bar mb-0">
        <dt class="menu-item-handle">
            <span class="item-title">
                <span class="menu-item-title">
                    <span id="menutitletemp_{{ $m->id }}">{{ $m->label }}</span>
                    <span class="visually-hidden">|{{ $m->id }}|</span>
                </span>
                @if ($m->depth > 0)
                    <span class="is-submenu">Mục con</span>
                @endif
            </span>
            <span class="item-controls">
                <a class="item-edit" id="edit-{{ $m->id }}" title="Chỉnh sửa mục menu" href="{{ $currentUrl }}?edit-menu-item={{ $m->id }}#menu-item-settings-{{ $m->id }}">Chỉnh sửa</a>
            </span>
        </dt>
    </dl>

    <div class="menu-item-settings" id="menu-item-settings-{{ $m->id }}">
        <input type="hidden" class="menu-item-data-db-id" value="{{ $m->id }}">
        <input type="hidden" class="menu-item-data-parent-id" value="{{ $m->parent }}">
        <input type="hidden" class="edit-menu-item-id" name="menuid_{{ $m->id }}" value="{{ $m->id }}">

        <div class="mb-3">
            <label for="idlabelmenu_{{ $m->id }}" class="form-label">Nhãn hiển thị</label>
            <input type="text" id="idlabelmenu_{{ $m->id }}" class="form-control edit-menu-item-title" name="idlabelmenu_{{ $m->id }}" value="{{ $m->label }}">
        </div>

        <div class="mb-3 menu-item-image-field d-flex align-items-start gap-2">
            <div class="image-box-container">
                <div class="custom-image-box image-box">
                    <input type="hidden" value="{{ $m->image }}" id="icon-{{ $m->id }}" name="image_menu_{{ $m->id }}" class="image-data">
                    <img src="{{ get_image($m->image) }}" alt="" class="icon-{{ $m->id }} preview_image">
                    <div class="image-box-actions">
                        <a class="ckfinder-popup" id="image-{{ $m->id }}" data="icon-{{ $m->id }}" data-show="icon-{{ $m->id }}">Chọn hình ảnh</a>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-danger btn-sm remove_menu_image" data-id="{{ $m->id }}" title="Xóa hình ảnh">
                <i class="fa-solid fa-xmark" aria-hidden="true"></i>
            </button>
        </div>

        <div class="mb-3">
            <label for="clases_menu_{{ $m->id }}" class="form-label">Lớp CSS (Class)</label>
            <input type="text" id="clases_menu_{{ $m->id }}" class="form-control edit-menu-item-classes" value="{{ $m->class }}">
        </div>

        <div class="mb-3">
            <label for="slug_menu_{{ $m->id }}" class="form-label">Đường dẫn (Slug)</label>
            <input type="text" id="slug_menu_{{ $m->id }}" class="form-control edit-menu-item-slug" value="{{ $m->slug }}">
        </div>

        <div class="mb-3">
            <label for="url_menu_{{ $m->id }}" class="form-label">Đường dẫn (URL)</label>
            <input type="text" id="url_menu_{{ $m->id }}" class="form-control edit-menu-item-url" value="{{ $m->link }}">
        </div>

        @if (!empty($roles))
            <div class="mb-3">
                <label for="role_menu_{{ $m->id }}" class="form-label">Quyền truy cập</label>
                <select id="role_menu_{{ $m->id }}" class="form-select edit-menu-item-role" name="role_menu_[{{ $m->id }}]">
                    <option value="0">Tất cả quyền</option>
                    @foreach ($roles as $role)
                        <option @selected($role->id == $m->role_id) value="{{ $role->{$role_pk} }}">{{ ucwords($role->{$role_title_field}) }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="menu-item-actions d-flex flex-wrap align-items-center gap-2 pt-2 border-top">
            <button type="button" class="btn btn-link btn-sm text-danger px-0 item-delete" id="delete-{{ $m->id }}">Xóa mục này</button>
            <button type="button" class="btn btn-link btn-sm text-secondary px-0 item-cancel hide-if-no-js" id="cancel-{{ $m->id }}">Hủy bỏ</button>
            <button type="button" onclick="getmenus()" class="btn btn-primary btn-sm updatemenu" id="update-{{ $m->id }}">
                <i class="fa-solid fa-check me-1" aria-hidden="true"></i>Cập nhật mục
            </button>
        </div>
    </div>
    <ul class="menu-item-transport list-unstyled mb-0"></ul>
</li>

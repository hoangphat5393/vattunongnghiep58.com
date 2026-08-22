@php
    $currentUrl = url()->current();
    $hasSelectedMenu = !empty($indmenu);
@endphp

<div id="hwpwrap" class="admin-menu-builder">
    <div class="nav-menus-php js menu-max-depth-0">
        <div class="manage-menus">
            <form method="get" action="{{ $currentUrl }}" class="row g-2 align-items-center">
                <label for="menu" class="col-lg-auto col-form-label fw-semibold mb-0">Chọn menu bạn muốn chỉnh sửa:</label>
                <div class="col-lg-4 col-xl-3">
                    <select id="menu" name="menu" class="form-select">
                        <option value="0">Select menu</option>
                        @foreach ($menulist as $item)
                            <option value="{{ $item->id }}" @selected((int) request('menu') === $item->id)>{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-auto">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fa-solid fa-check me-1" aria-hidden="true"></i>Chọn
                    </button>
                </div>
                <div class="col-lg-auto">
                    <a class="btn btn-link px-0" href="{{ $currentUrl }}?menu=0">
                        <i class="fa-solid fa-plus me-1" aria-hidden="true"></i>Tạo menu mới
                    </a>
                </div>
            </form>
        </div>

        <div id="nav-menus-frame" class="menu-builder-frame @if ($hasSelectedMenu) has-selected-menu @else is-create-menu @endif">
            @if ($hasSelectedMenu)
                <div id="menu-settings-column">
                    <form id="nav-menu-meta" class="nav-menu-meta" method="post" action="#" onsubmit="return false;">
                        <div id="side-sortables" class="accordion-container">
                            <ul class="outer-border">
                                <li class="control-section accordion-section open" id="add-custom-link">
                                    <h3 class="accordion-section-title hndle" tabindex="0">
                                        Liên Kết Tùy Chỉnh
                                        <span class="screen-reader-text">Nhấn Enter để mở rộng</span>
                                    </h3>
                                    <div class="accordion-section-content">
                                        <div class="inside">
                                            <div class="customlinkdiv">
                                                <label class="form-label howto" for="custom-menu-item-name">
                                                    <span class="d-block mb-1">Nhãn hiển thị</span>
                                                    <input id="custom-menu-item-name" name="label" type="text" class="form-control menu-item-textbox" title="Nhãn menu" placeholder="Tên nhãn menu">
                                                </label>
                                                <label class="form-label howto" for="custom-menu-item-slug">
                                                    <span class="d-block mb-1">Đường dẫn (Slug)</span>
                                                    <input id="custom-menu-item-slug" name="slug" type="text" class="form-control menu-item-textbox" placeholder="Slug">
                                                </label>
                                                <label class="form-label howto" for="custom-menu-item-url">
                                                    <span class="d-block mb-1">Đường dẫn (URL)</span>
                                                    <input id="custom-menu-item-url" name="url" type="text" class="form-control menu-item-textbox" placeholder="Url">
                                                </label>
                                                <p class="button-controls mb-0">
                                                    <button type="button" class="btn btn-outline-primary btn-sm add_custom_menu">
                                                        <i class="fa-solid fa-plus me-1" aria-hidden="true"></i>Thêm vào menu
                                                    </button>
                                                    <span class="spinner" id="spincustomu"></span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li class="control-section accordion-section" id="add-menu-pages">
                                    <h3 class="accordion-section-title hndle" tabindex="0">
                                        Trang Tĩnh
                                        <span class="screen-reader-text">Nhấn Enter để mở rộng</span>
                                    </h3>
                                    <div class="accordion-section-content">
                                        <div class="inside">
                                            <div class="customlinkdiv">
                                                @include('backend.setting.includes.page_items')
                                            </div>
                                            <p class="button-controls mb-0">
                                                <button type="button" class="btn btn-outline-primary btn-sm add_menu_item">
                                                    <i class="fa-solid fa-plus me-1" aria-hidden="true"></i>Thêm vào menu
                                                </button>
                                                <span class="spinner" id="spincustomu-pages"></span>
                                            </p>
                                        </div>
                                    </div>
                                </li>

                                <li class="control-section accordion-section" id="add-menu-post-categories">
                                    <h3 class="accordion-section-title hndle" tabindex="0">
                                        Danh Mục Bài Viết
                                        <span class="screen-reader-text">Nhấn Enter để mở rộng</span>
                                    </h3>
                                    <div class="accordion-section-content">
                                        <div class="inside">
                                            <div class="customlinkdiv">
                                                @include('backend.setting.includes.category_items', [
                                                    'type' => 'post',
                                                    'parent_id' => 0,
                                                    'categories' => $categoryTree ?? collect(),
                                                    'childrenMap' => $childrenMap ?? collect(),
                                                ])
                                            </div>
                                            <p class="button-controls mb-0">
                                                <button type="button" class="btn btn-outline-primary btn-sm add_menu_item">
                                                    <i class="fa-solid fa-plus me-1" aria-hidden="true"></i>Thêm vào menu
                                                </button>
                                                <span class="spinner" id="spincustomu-categories"></span>
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </form>
                </div>
            @endif

            <div id="menu-management-liquid">
                <div id="menu-management">
                    <form id="update-nav-menu" action="#" method="post" onsubmit="return false;">
                        <div class="menu-edit border rounded overflow-hidden">
                            <div id="nav-menu-header" class="menu-edit-toolbar">
                                <div class="row g-2 align-items-end">
                                    <label class="menu-name-label col-12 col-xl mb-0" for="menu-name">
                                        <span class="form-label fw-semibold mb-1">Tên Menu</span>
                                        <input name="menu-name" id="menu-name" type="text" class="form-control menu-name menu-item-textbox" title="Nhập tên menu" value="{{ $indmenu->name ?? '' }}">
                                        <input type="hidden" id="idmenu" value="{{ $indmenu->id ?? '' }}">
                                    </label>
                                    <div class="col-12 col-xl-auto d-flex flex-wrap align-items-center gap-2">
                                        @if ($hasSelectedMenu)
                                            <button type="button" onclick="replicate_menu_id('menuwp','{{ $indmenu->id }}')" class="btn btn-outline-secondary menu-copy">
                                                <i class="fa-regular fa-copy me-1" aria-hidden="true"></i>Sao chép
                                            </button>
                                            <button type="button" onclick="getmenus()" class="btn btn-primary menu-save">
                                                <i class="fa-solid fa-floppy-disk me-1" aria-hidden="true"></i>Lưu Menu
                                            </button>
                                            <span class="spinner" id="spincustomu2"></span>
                                        @else
                                            <button type="button" onclick="createnewmenu()" class="btn btn-primary menu-save">
                                                <i class="fa-solid fa-plus me-1" aria-hidden="true"></i>Tạo Menu
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div id="post-body" class="p-3">
                                @if ($hasSelectedMenu)
                                    <h4 class="h5 mb-2">Cấu Trúc Menu</h4>
                                    <div class="drag-instructions alert alert-info py-2 mb-3">
                                        <p class="mb-0 small">Kéo thả sắp xếp thứ tự các mục theo ý muốn. Nhấp vào nút Chỉnh sửa ở mỗi mục để tùy chỉnh chi tiết.</p>
                                    </div>
                                @else
                                    <h4 class="h5 mb-2">Khởi Tạo Menu</h4>
                                    <div class="drag-instructions alert alert-secondary py-2 mb-3">
                                        <p class="mb-0 small">Vui lòng nhập tên menu và chọn &quot;Tạo Menu&quot;.</p>
                                    </div>
                                @endif

                                <ul id="menu-to-edit" class="menu list-unstyled ui-sortable mb-0">
                                    @isset($menus)
                                        @foreach ($menus as $m)
                                            @include('backend.setting.includes.menu-item', [
                                                'm' => $m,
                                                'currentUrl' => $currentUrl,
                                                'roles' => $roles ?? null,
                                                'role_pk' => $role_pk ?? 'id',
                                                'role_title_field' => $role_title_field ?? 'name',
                                            ])
                                        @endforeach
                                    @endisset
                                </ul>
                            </div>

                            <div id="nav-menu-footer" class="menu-edit-toolbar">
                                <div class="d-flex flex-column flex-lg-row gap-3 justify-content-between align-items-lg-center">
                                    @if ($hasSelectedMenu)
                                        <div class="d-flex flex-wrap gap-2">
                                            <button type="button" class="btn btn-outline-danger btn-sm menu-delete" onclick="deletemenu()">
                                                <i class="fa-solid fa-trash-can me-1" aria-hidden="true"></i>Xóa menu
                                            </button>
                                            <button type="button" onclick="delete_menu_id()" class="btn btn-outline-danger btn-sm menu-delete">
                                                <i class="fa-solid fa-trash me-1" aria-hidden="true"></i>Xóa menu &amp; tất cả mục con
                                            </button>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <button type="button" onclick="getmenus()" class="btn btn-primary menu-save">
                                                <i class="fa-solid fa-floppy-disk me-1" aria-hidden="true"></i>Lưu Menu
                                            </button>
                                            <span class="spinner" id="spincustomu2-footer"></span>
                                        </div>
                                    @else
                                        <button type="button" onclick="createnewmenu()" class="btn btn-primary menu-save">
                                            <i class="fa-solid fa-plus me-1" aria-hidden="true"></i>Tạo Menu
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

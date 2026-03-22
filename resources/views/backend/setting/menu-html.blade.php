@php
    $currentUrl = url()->current();
@endphp

<div id="hwpwrap">
    <div class="custom-wp-admin wp-admin wp-core-ui js menu-max-depth-0 nav-menus-php auto-fold admin-bar">
        <div id="wpwrap">
            <div id="wpcontent">
                <div id="wpbody">
                    <div id="wpbody-content">
                        <div class="wrap">
                            <div class="manage-menus">
                                <form method="get" action="{{ $currentUrl }}">
                                    <label for="menu" class="selected-menu">Select the menu you want to edit:</label>
                                    <select name="menu" class="">
                                        <option value="0">Select menu</option>
                                        @if ($menulist->count() > 0)
                                            @foreach ($menulist as $item)
                                                <option value="{{ $item->id }}" @if (request('menu') == $item->id) selected @endif>{{ $item->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <span class="submit-btn">
                                        <input type="submit" class="button-secondary" value="Choose">
                                    </span>
                                    <span class="add-new-menu-action"> or <a href="{{ $currentUrl }}?action=edit&menu=0">Create new menu</a>. </span>
                                </form>
                            </div>

                            <div id="nav-menus-frame">
                                @if (request()->has('menu') && !empty(request()->input('menu')))
                                    <div id="menu-settings-column" class="metabox-holder">

                                        <div class="clearfix"></div>

                                        <form id="nav-menu-meta" action="" class="nav-menu-meta" method="post" enctype="multipart/form-data">
                                            <div id="side-sortables" class="accordion-container">
                                                <ul class="outer-border">
                                                    <li class="control-section accordion-section open add-page" id="add-customlink">
                                                        <h3 class="accordion-section-title hndle" tabindex="0">
                                                            Custom Link <span class="screen-reader-text">Press return or enter to expand</span>
                                                        </h3>
                                                        <div class="accordion-section-content ">
                                                            <div class="inside">
                                                                <div class="customlinkdiv" id="customlinkdiv">

                                                                    {{-- <p id="menu-item-url-wrap">
                                                                        <label class="col-form-label howto" for="custom-menu-item-url">
                                                                            <span>URL</span>&nbsp;&nbsp;&nbsp;
                                                                            <input id="custom-menu-item-url" name="url" type="text" class="form-control menu-item-textbox" placeholder="url">
                                                                        </label>
                                                                    </p> --}}

                                                                    {{-- <p id="menu-item-name-wrap">
                                                                        <label class="col-form-label howto" for="custom-menu-item-name"> <span>Label</span>&nbsp;
                                                                            <input id="custom-menu-item-name" name="label" type="text" class="form-control regular-text menu-item-textbox input-with-default-title" title="Label menu">
                                                                        </label>
                                                                    </p> --}}

                                                                    <label class="col-form-label howto" for="custom-menu-item-name">
                                                                        <span>Label</span>&nbsp;
                                                                        <input id="custom-menu-item-name" name="label" type="text" class="form-control regular-text menu-item-textbox input-with-default-title" title="Label menu">
                                                                    </label>

                                                                    <label class="col-form-label howto" for="custom-menu-item-slug">
                                                                        <span>Slug</span>&nbsp;&nbsp;&nbsp;
                                                                        <input id="custom-menu-item-slug" name="url" type="text" class="form-control menu-item-textbox" placeholder="Slug">
                                                                    </label>

                                                                    <label class="col-form-label howto" for="custom-menu-item-url">
                                                                        <span>URL</span>&nbsp;&nbsp;&nbsp;
                                                                        <input id="custom-menu-item-url" name="url" type="text" class="form-control menu-item-textbox" placeholder="Url">
                                                                    </label>

                                                                    {{-- @if (!empty($roles))
                                                                        <p id="menu-item-role_id-wrap">
                                                                            <label class="howto" for="custom-menu-item-name"> <span>Role</span>&nbsp;
                                                                                <select id="custom-menu-item-role" name="role">
                                                                                    <option value="0">Select Role</option>
                                                                                    @foreach ($roles as $role)
                                                                                        <option value="{{ $role->$role_pk }}">{{ ucfirst($role->$role_title_field) }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </label>
                                                                        </p>
                                                                    @endif --}}

                                                                    {{-- <p class="button-controls">
                                                                        <a href="#" onclick="addcustommenu()" class="button-secondary submit-add-to-menu right">Add menu item</a>
                                                                        <span class="spinner" id="spincustomu"></span>
                                                                    </p> --}}

                                                                    <p class="button-controls">
                                                                        <a href="#" class="button-secondary submit-add-to-menu right add_custom_menu">Add menu item</a>
                                                                        <span class="spinner" id="spincustomu"></span>
                                                                    </p>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>

                                                    {{-- Page list --}}
                                                    <li class="control-section accordion-section add-page" id="add-page">
                                                        <h3 class="accordion-section-title hndle" tabindex="0"> Page <span class="screen-reader-text">Press return or enter to expand</span></h3>
                                                        <div class="accordion-section-content ">
                                                            <div class="inside">
                                                                <div class="customlinkdiv" id="customlinkdiv">
                                                                    @include('backend.setting.includes.page_items')
                                                                </div>
                                                                <p class="button-controls">
                                                                    <a href="#" class="button-secondary submit-add-to-menu right add_menu_item">Add menu item</a>
                                                                    <span class="spinner" id="spincustomu"></span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </li>

                                                    {{-- Work --}}
                                                    {{-- <li class="control-section accordion-section add-page" id="add-page">
                                                        <h3 class="accordion-section-title hndle" tabindex="0"> Work <span class="screen-reader-text">Press return or enter to expand</span></h3>
                                                        <div class="accordion-section-content ">
                                                            <div class="inside">
                                                                <div class="customlinkdiv" id="customlinkdiv">
                                                                    @include('backend.setting.includes.works_items')
                                                                </div>
                                                                <p class="button-controls">
                                                                    <a href="#" class="button-secondary submit-add-to-menu right add_menu_item">Add menu item</a>
                                                                    <span class="spinner" id="spincustomu"></span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </li> --}}

                                                    {{-- Category list (Post) --}}
                                                    <li class="control-section accordion-section add-page" id="add-page">
                                                        <h3 class="accordion-section-title hndle" tabindex="0"> Post category <span class="screen-reader-text">Press return or enter to expand</span></h3>
                                                        <div class="accordion-section-content ">
                                                            <div class="inside">
                                                                <div class="customlinkdiv" id="customlinkdiv">
                                                                    @include('backend.setting.includes.category_items', ['type' => 'post'])
                                                                </div>
                                                                <p class="button-controls">
                                                                    <a href="#" class="button-secondary submit-add-to-menu right add_menu_item">Add menu item</a>
                                                                    <span class="spinner" id="spincustomu"></span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </li>

                                                    {{-- <li class="control-section accordion-section add-page" id="add-page">
                                                        <h3 class="accordion-section-title hndle" tabindex="0"> Video category <span class="screen-reader-text">Press return or enter to expand</span></h3>
                                                        <div class="accordion-section-content ">
                                                            <div class="inside">
                                                                <div class="customlinkdiv" id="customlinkdiv">
                                                                    @include('admin.setting.includes.category_items', ['type' => 'video'])
                                                                </div>
                                                                <p class="button-controls">
                                                                    <a href="#" class="button-secondary submit-add-to-menu right add_menu_item">Add menu item</a>
                                                                    <span class="spinner" id="spincustomu"></span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </li> --}}

                                                </ul>
                                            </div>
                                        </form>
                                    </div>
                                @endif

                                <div id="menu-management-liquid">
                                    <div id="menu-management">
                                        <form id="update-nav-menu" action="" method="post" enctype="multipart/form-data">
                                            <div class="menu-edit">
                                                <div id="nav-menu-header">
                                                    <div class="major-publishing-actions d-flex justify-content-between">

                                                        <label class="menu-name-label howto open-label mb-0" for="menu-name">
                                                            <span>Name</span>
                                                            <input name="menu-name" id="menu-name" type="text" class="menu-name regular-text menu-item-textbox" title="Enter menu name" value="@if ($indmenu) {{ $indmenu->name }} @endif">
                                                            <input type="hidden" id="idmenu" value="{{ $indmenu->id ?? '' }}" />
                                                        </label>

                                                        <div class="d-flex justify-content-end">
                                                            {{-- <div class="me-auto p-2 bd-highlight">Flex item</div> --}}
                                                            @if ($indmenu)
                                                                <div class="publishing-action">
                                                                    <a href="javascript:void(0)" onclick="replicate_menu_id('menuwp','{{ $indmenu->id }}')" name="save_menu" id="save_menu_header" class="button button-primary menu-copy me-3">
                                                                        @lang('admin.Copy')
                                                                    </a>
                                                                </div>
                                                            @endif

                                                            @if (request()->has('action'))
                                                                <div class="publishing-action">
                                                                    <a onclick="createnewmenu()" name="save_menu" id="save_menu_header" class="button button-primary menu-save">Create menu</a>
                                                                </div>
                                                            @elseif(request()->has('menu'))
                                                                <div class="publishing-action">
                                                                    <a onclick="getmenus()" name="save_menu" id="save_menu_header" class="button button-primary menu-save">Save menu</a>
                                                                    <span class="spinner" id="spincustomu2"></span>
                                                                </div>
                                                            @else
                                                                <div class="publishing-action">
                                                                    <a onclick="createnewmenu()" name="save_menu" id="save_menu_header" class="button button-primary menu-save">Create menu</a>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="post-body">
                                                    <div id="post-body-content">
                                                        @if (request()->has('menu'))
                                                            <h3>Menu Structure</h3>
                                                            <div class="drag-instructions post-body-plain">
                                                                <p>
                                                                    Place each item in the order you prefer. Click on the arrow to the right of the item to display more configuration options.
                                                                </p>
                                                            </div>
                                                        @else
                                                            <h3>Menu Creation</h3>
                                                            <div class="drag-instructions post-body-plain">
                                                                <p>
                                                                    Please enter the name and select "Create menu" button
                                                                </p>
                                                            </div>
                                                        @endif

                                                        <ul id="menu-to-edit" class="menu ui-sortable">
                                                            @if (isset($menus))
                                                                @foreach ($menus as $m)
                                                                    <li id="menu-item-{{ $m->id }}" class="menu-item menu-item-depth-{{ $m->depth }} menu-item-page menu-item-edit-inactive pending" style="display: list-item;">
                                                                        <dl class="menu-item-bar">
                                                                            <dt class="menu-item-handle">
                                                                                <span class="item-title">
                                                                                    <span class="menu-item-title"> <span id="menutitletemp_{{ $m->id }}">{{ $m->label }}</span>
                                                                                        <span style="color: transparent;">|{{ $m->id }}|</span> </span> <span class="is-submenu" style="@if ($m->depth == 0) display: none; @endif">Subelement
                                                                                    </span>
                                                                                </span>

                                                                                <span class="item-controls">
                                                                                    <span class="item-type">Link</span>
                                                                                    <span class="item-order hide-if-js">
                                                                                        <a href="{{ $currentUrl }}?action=move-up-menu-item&menu-item={{ $m->id }}&_wpnonce=8b3eb7ac44" class="item-move-up">
                                                                                            <abbr title="Move Up">↑</abbr></a> | <a href="{{ $currentUrl }}?action=move-down-menu-item&menu-item={{ $m->id }}&_wpnonce=8b3eb7ac44" class="item-move-down">
                                                                                            <abbr title="Move Down">↓</abbr>
                                                                                        </a>
                                                                                    </span>
                                                                                    <a class="item-edit" id="edit-{{ $m->id }}" title="" href="{{ $currentUrl }}?edit-menu-item={{ $m->id }}#menu-item-settings-{{ $m->id }}"></a>
                                                                                </span>
                                                                            </dt>
                                                                        </dl>

                                                                        <div class="menu-item-settings" id="menu-item-settings-{{ $m->id }}">
                                                                            <input type="hidden" class="edit-menu-item-id" name="menuid_{{ $m->id }}" value="{{ $m->id }}" />
                                                                            <label for="edit-menu-item-title-{{ $m->id }}" class="col-form-label"> Label
                                                                                <br>
                                                                                <input type="text" id="idlabelmenu_{{ $m->id }}" class="form-control widefat edit-menu-item-title" name="idlabelmenu_{{ $m->id }}" value="{{ $m->label }}">
                                                                            </label>
                                                                            <br>
                                                                            <div class="form-group form-group-image-box">
                                                                                <div class="image-box-container">
                                                                                    <div class="custom-image-box image-box">
                                                                                        <input type="hidden" value="{{ $m->image }}" id="icon-{{ $m->id }}" name="image_menu_{{ $m->id }}" class="image-data">
                                                                                        <img src="{{ get_image($m->image) }}" class="icon-{{ $m->id }} preview_image">
                                                                                        <div class="image-box-actions">
                                                                                            <a class="ckfinder-popup" id="image-{{ $m->id }}" data="icon-{{ $m->id }}" data-show="icon-{{ $m->id }}">Choose image</a>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <button type="button" class="btn btn-danger align-self-center ms-3 remove_menu_image" data-id="{{ $m->id }}">
                                                                                    <i class="fa-solid fa-xmark fa-lg"></i>
                                                                                </button>
                                                                            </div>

                                                                            <label for="edit-menu-item-classes-{{ $m->id }}" class="col-form-label"> Class CSS
                                                                                <br>
                                                                                <input type="text" id="clases_menu_{{ $m->id }}" class="form-control widefat code edit-menu-item-classes" value="{{ $m->class }}">
                                                                            </label>

                                                                            <br>

                                                                            <label for="edit-menu-item-classes-{{ $m->id }}" class="col-form-label"> Slug
                                                                                <br>
                                                                                <input type="text" id="slug_menu_{{ $m->id }}" class="form-control widefat code edit-menu-item-slug" value="{{ $m->slug }}">
                                                                            </label>

                                                                            <br>

                                                                            <label for="edit-menu-item-url-{{ $m->id }}" class="col-form-label"> Url
                                                                                <br>
                                                                                <input type="text" id="url_menu_{{ $m->id }}" class="form-control widefat code edit-menu-item-url" value="{{ $m->link }}">
                                                                            </label>

                                                                            @if (!empty($roles))
                                                                                <p class="field-css-role description description-wide">
                                                                                    <label for="edit-menu-item-role-{{ $m->id }}"> Role
                                                                                        <br>
                                                                                        <select id="role_menu_{{ $m->id }}" class="widefat code edit-menu-item-role" name="role_menu_[{{ $m->id }}]">
                                                                                            <option value="0">Select Role</option>
                                                                                            @foreach ($roles as $role)
                                                                                                <option @if ($role->id == $m->role_id) selected @endif value="{{ $role->$role_pk }}">{{ ucwords($role->$role_title_field) }}</option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </label>
                                                                                </p>
                                                                            @endif

                                                                            <p class="field-move hide-if-no-js description description-wide">
                                                                                <label>
                                                                                    <span>Move</span>
                                                                                    <a href="{{ $currentUrl }}" class="menus-move-up" style="display: none;">Move up</a>
                                                                                    <a href="{{ $currentUrl }}" class="menus-move-down" title="Mover uno abajo" style="display: inline;">Move Down</a>
                                                                                    <a href="{{ $currentUrl }}" class="menus-move-left" style="display: none;"></a>
                                                                                    <a href="{{ $currentUrl }}" class="menus-move-right" style="display: none;"></a>
                                                                                    <a href="{{ $currentUrl }}" class="menus-move-top" style="display: none;">Top</a>
                                                                                </label>
                                                                            </p>

                                                                            <div class="menu-item-actions description-wide submitbox">
                                                                                <a class="item-delete submitdelete deletion" id="delete-{{ $m->id }}" href="{{ $currentUrl }}?action=delete-menu-item&menu-item={{ $m->id }}&_wpnonce=2844002501">Delete</a>
                                                                                <span class="meta-sep hide-if-no-js"> | </span>
                                                                                <a class="item-cancel submitcancel hide-if-no-js button-secondary" id="cancel-{{ $m->id }}" href="{{ $currentUrl }}?edit-menu-item={{ $m->id }}&cancel=1424297719#menu-item-settings-{{ $m->id }}">Cancel</a>
                                                                                <span class="meta-sep hide-if-no-js"> | </span>
                                                                                <a onclick="getmenus()" class="button button-primary updatemenu" id="update-{{ $m->id }}" href="javascript:void(0)">Update item</a>
                                                                            </div>
                                                                        </div>
                                                                        <ul class="menu-item-transport"></ul>
                                                                    </li>
                                                                @endforeach
                                                            @endif
                                                        </ul>
                                                        <div class="menu-settings"></div>
                                                    </div>
                                                </div>

                                                <div id="nav-menu-footer" class="">
                                                    <div class="major-publishing-actions d-flex justify-content-between">
                                                        @if (request()->has('action'))
                                                            <div class="publishing-action">
                                                                <a onclick="createnewmenu()" name="save_menu" id="save_menu_header" class="button button-primary menu-save">Create menu</a>
                                                            </div>
                                                        @elseif(request()->has('menu'))
                                                            <div class="">
                                                                <span class="delete-action">
                                                                    <a class="submitdelete deletion menu-delete" onclick="deletemenu()" href="javascript:void(9)">Delete menu</a>
                                                                </span>
                                                                <span class="delete-action">
                                                                    <a href="javascript:void(0)" onclick="delete_menu_id()" class="submitdelete deletion menu-delete ms-3">Delete menu & items</a>
                                                                </span>
                                                            </div>
                                                            <div class="publishing-action">
                                                                <a onclick="getmenus()" name="save_menu" id="save_menu_header" class="button button-primary menu-save">Save menu</a>
                                                                <span class="spinner" id="spincustomu2"></span>
                                                            </div>
                                                        @else
                                                            <div class="publishing-action">
                                                                <a onclick="createnewmenu()" name="save_menu" id="save_menu_header" class="button button-primary menu-save">Create menu</a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

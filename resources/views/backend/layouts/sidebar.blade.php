@php

    $segment_check = Request::segment(2);

    $segment_check3 = Request::segment(3);

    $menus = \App\Models\Backend\AdminMenu::getListVisible();

    // dd($menus);

    $AdminMenu = new App\Models\Backend\AdminMenu();

    $user = Auth::guard('admin')->user();

    if ($user) {
        $user_role = $user->roles()->first();
    }

    // Hiển thị danh sách route names

    // dd($routeNames);

    // dd(Route::getRoutes()->toArray());

    $routeNames = collect(Route::getRoutes())
        ->filter(function ($route) {
            return str_starts_with($route->uri(), 'admin'); // Lọc route có prefix 'admin'
        })

        ->map(function ($route) {
            return $route->getName(); // Lấy tên route
        })

        ->filter()

        ->values()

        ->toArray(); // Loại bỏ null và chuyển thành mảng

    // Hiển thị danh sách route names

    // dd($routeNames);

@endphp





@if ($user)

    <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">



        {{-- begin::Sidebar Brand --}}

        <div class="sidebar-brand">

            {{-- begin::Brand Link --}}

            <a href="{{ route('admin.dashboard') }}" class="brand-link">

                {{-- begin::Brand Image --}}

                <img src="{{ get_image(setting_option('logo')) }}" alt="AdminLTE Logo" class="brand-image opacity-75 shadow" />

                {{-- begin::Brand Text --}}

                <span class="brand-text fw-light">{{ setting_option('webtitle') ?: 'Admin' }}</span>

            </a>

        </div>

        {{-- end::Sidebar Brand --}}





        {{-- begin::Sidebar Wrapper --}}

        <div class="sidebar-wrapper">

            <nav class="mt-2" aria-label="Main navigation">



                {{-- begin::Sidebar Menu --}}

                <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">



                    <li class="nav-item">

                        <a href="{{ route('admin.dashboard') }}" class="nav-link">

                            {{-- <i class="nav-icon bi bi-grip-horizontal"></i> --}}

                            <i class="nav-icon bi bi-speedometer"></i>

                            {{-- <i class="nav-icon fas fa-tachometer-alt"></i> --}}

                            <p>{!! __('admin.dashboard') !!}</p>

                        </a>

                    </li>



                    {{-- <li class="nav-item">

                        <a href="./generate/theme.html" class="nav-link">

                            <i class="nav-icon bi bi-palette"></i>

                            <p>Theme Generate</p>

                        </a>

                    </li> --}}



                    <li class="nav-item">

                        <a href="{{ route('index') }}" target="_blank" class="nav-link">

                            <i class="nav-icon fas fa-home"></i>

                            <p>{!! __('admin.home') !!}</p>

                        </a>

                    </li>


                    @if (count($menus))

                        {{-- Level 0 --}}

                        @foreach ($menus[0] as $level0)
                            {{-- LEvel 1 --}}

                            @if (!empty($menus[$level0->id]) && $level0->hidden == 0)
                                @php
                                    $currentUrl = url()->current();

                                    $isMenuOpen = collect($menus[$level0->id])->contains(function ($level1) use ($AdminMenu, $currentUrl) {
                                        if (!$level1->uri || !Route::has($level1->uri)) {
                                            return false;
                                        }

                                        return $AdminMenu::checkUrlIsChild($currentUrl, route($level1->uri));
                                    });
                                @endphp

                                <li class="nav-item {{ $isMenuOpen ? 'menu-open' : '' }}">

                                    <a href="#" class="nav-link {{ $isMenuOpen ? 'active' : '' }}">

                                        <i class="nav-icon {{ $level0->icon }}"></i>

                                        <p>

                                            {!! __($level0->title) !!} <i class="nav-arrow bi bi-chevron-right"></i>

                                        </p>

                                    </a>

                                    <ul class="nav nav-treeview">

                                        @foreach ($menus[$level0->id] as $level1)
                                            <li class="nav-item">

                                                @php
                                                    $level1Url = $level1->uri && Route::has($level1->uri) ? route($level1->uri) : '#';
                                                    $level1Active = $level1Url !== '#' && $AdminMenu::checkUrlIsChild($currentUrl, $level1Url);
                                                @endphp

                                                <a href="{{ $level1Url }}" class="nav-link {{ $level1Active ? 'active' : '' }}">

                                                    <i class="nav-icon {{ $level1->icon }}"></i>

                                                    <p>{!! __($level1->title) !!}</p>

                                                </a>

                                            </li>
                                        @endforeach

                                    </ul>

                                </li>
                            @else
                                @if ($level0->hidden == 0)
                                    <li class="nav-item">

                                        <a href="{{ $level0->uri ? route($level0->uri) : '#' }}" class="nav-link {{ $AdminMenu::checkUrlIsChild(url()->current(), route($level0->uri)) ? 'active' : '' }}">

                                            <i class="nav-icon {{ $level0->icon }}"></i>

                                            <p>{!! __($level0->title) !!}</p>

                                        </a>

                                    </li>
                                @endif
                            @endif

                            {{-- LEvel 1  --}}
                        @endforeach

                        {{-- Level 0 --}}

                    @endif

                    <li class="nav-header">SETTING</li>

                    @php

                        // dd(Route::currentRouteName());

                        $route_active = ['admin.user.index', 'admin.role.index', 'admin.permission.index'];

                    @endphp

                    @if ($user && $user->isAdministrator())
                        <li class="nav-item {{ in_array(Route::currentRouteName(), $route_active) ? 'menu-open' : '' }}">

                            <a href="#" class="nav-link">

                                <i class="nav-icon fas fa-user-friends"></i>

                                <p>

                                    @lang('admin.user') <i class="nav-arrow bi bi-chevron-right"></i>

                                </p>

                            </a>

                            <ul class="nav nav-treeview">

                                <li class="nav-item">

                                    <a href="{{ route('admin.user.index') }}" class="nav-link {{ Route::currentRouteName() == 'admin.user.index' ? 'active' : '' }}">

                                        <i class="nav-icon fas fa-angle-right"></i>

                                        <p> @lang('admin.user') </p>

                                    </a>

                                </li>

                                <li class="nav-item">

                                    <a href="{{ route('admin.role.index') }}" class="nav-link {{ Route::currentRouteName() == 'admin.role.index' ? 'active' : '' }}">

                                        <i class="nav-icon fas fa-angle-right"></i>

                                        <p>@lang('admin.role')</p>

                                    </a>

                                </li>

                                <li class="nav-item">

                                    <a href="{{ route('admin.permission.index') }}" class="nav-link {{ Route::currentRouteName() == 'admin.permission.index' ? 'active' : '' }}">

                                        <i class="nav-icon fas fa-angle-right"></i>

                                        <p>@lang('admin.permission')</p>

                                    </a>

                                </li>

                            </ul>

                        </li>
                    @endif



                    @php
                        $route_active = ['admin.theme-option', 'admin.css.get', 'admin.cache.clear'];
                    @endphp


                    <li class="nav-item {{ in_array(Route::currentRouteName(), $route_active) ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fa-light fa-gear"></i>
                            <p>
                                @lang('admin.setting') <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>

                        </a>

                        <ul class="nav nav-treeview">



                            <li class="nav-item">

                                <a href="{{ route('admin.admin-menu.index') }}" class="nav-link {{ Route::currentRouteName() == 'admin.css.get' ? 'active' : '' }}">

                                    <i class="nav-icon fas fa-angle-right"></i>

                                    <p>@lang('admin.sidebar_menu')</p>

                                </a>

                            </li>



                            <li class="nav-item">

                                <a href="{{ route('admin.theme-option') }}" class="nav-link {{ Route::currentRouteName() == 'admin.theme-option' ? 'active' : '' }}">

                                    <i class="nav-icon fas fa-angle-right"></i>

                                    <p>@lang('admin.theme_option')</p>

                                </a>

                            </li>



                            @if ($user && $user->isAdministrator())
                                <li class="nav-item">

                                    <a href="{{ route('admin.css.get') }}" class="nav-link {{ Route::currentRouteName() == 'admin.css.get' ? 'active' : '' }}">

                                        <i class="nav-icon fas fa-angle-right"></i>

                                        <p>@lang('admin.theme_css')</p>

                                    </a>

                                </li>
                            @endif

                            <li class="nav-item">
                                <form action="{{ route('admin.cache.clear') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
                                        <i class="nav-icon fas fa-angle-right"></i>
                                        <p>Xóa cache</p>
                                    </button>
                                </form>
                            </li>

                        </ul>

                    </li>



                    <li class="nav-item">

                        <a href="{{ route('admin.change-password') }}" class="nav-link">

                            <i class="nav-icon fa fa-user" aria-hidden="true"></i>

                            <p>@lang('admin.account')</p>

                        </a>

                    </li>

                    <li class="nav-item">

                        <form action="{{ route('admin.logout') }}" method="POST" class="mb-0">

                            @csrf

                            <button type="submit" class="nav-link border-0 bg-transparent text-start w-100">

                                <i class="nav-icon fas fa-sign-out-alt"></i>

                                <p>@lang('admin.logout')</p>

                            </button>

                        </form>

                    </li>



                </ul>

            </nav>

            {{-- /.sidebar-menu --}}

        </div>

        {{-- /.sidebar --}}

    </aside>

@endif

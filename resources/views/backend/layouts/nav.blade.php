@php
    $adminUser = Auth::guard('admin')->user();
    $adminName = $adminUser?->fullname ?: $adminUser?->name ?: $adminUser?->email ?: 'Admin';
    $adminAvatar = $adminUser && !empty($adminUser->avatar) ? get_image($adminUser->avatar) : asset('assets/admin/assets/img/user2-160x160.jpg');
@endphp

<nav class="app-header navbar navbar-expand bg-body">

    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button" aria-label="Thu gọn menu">
                    <i class="bi bi-list" aria-hidden="true"></i>
                </a>
            </li>
            <li class="nav-item d-none d-md-block">
                <a href="{{ route('index') }}" class="nav-link" target="_blank" rel="noopener">
                    <i class="bi bi-box-arrow-up-right me-1" aria-hidden="true"></i>
                    Xem website
                </a>
            </li>
        </ul>

        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link" href="#" data-lte-toggle="fullscreen" aria-label="Toàn màn hình">
                    <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen" aria-hidden="true"></i>
                    <i data-lte-icon="minimize" class="bi bi-fullscreen-exit d-none" aria-hidden="true"></i>
                </a>
            </li>

            {{-- Color mode (AdminLTE 4.1 ColorMode via data-bs-theme-value) --}}
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" id="bd-theme" aria-label="Chế độ sáng/tối" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-sun-fill" data-lte-theme-icon="light" aria-hidden="true"></i>
                    <i class="bi bi-moon-fill d-none" data-lte-theme-icon="dark" aria-hidden="true"></i>
                    <i class="bi bi-circle-half d-none" data-lte-theme-icon="auto" aria-hidden="true"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="bd-theme" style="--bs-dropdown-min-width: 8rem">
                    <li>
                        <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
                            <i class="bi bi-sun-fill me-2" aria-hidden="true"></i>
                            Sáng
                            <i class="bi bi-check-lg ms-auto d-none" aria-hidden="true"></i>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
                            <i class="bi bi-moon-fill me-2" aria-hidden="true"></i>
                            Tối
                            <i class="bi bi-check-lg ms-auto d-none" aria-hidden="true"></i>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto" aria-pressed="true">
                            <i class="bi bi-circle-half me-2" aria-hidden="true"></i>
                            Tự động
                            <i class="bi bi-check-lg ms-auto d-none" aria-hidden="true"></i>
                        </button>
                    </li>
                </ul>
            </li>

            @if ($adminUser)
                <li class="nav-item dropdown user-menu">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ $adminAvatar }}" class="user-image rounded-circle shadow" alt="" />
                        <span class="d-none d-md-inline">{{ $adminName }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                        <li class="user-header text-bg-primary">
                            <img src="{{ $adminAvatar }}" class="rounded-circle shadow" alt="" />
                            <p>
                                {{ $adminName }}
                                <small>{{ $adminUser->email }}</small>
                            </p>
                        </li>
                        <li class="user-footer d-flex justify-content-between align-items-center px-3 py-2">
                            <a href="{{ route('admin.change-password') }}" class="btn btn-outline-secondary btn-sm">Đổi mật khẩu</a>
                            <form action="{{ route('admin.logout') }}" method="POST" class="d-inline mb-0">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm">Đăng xuất</button>
                            </form>
                        </li>
                    </ul>
                </li>
            @endif
        </ul>
    </div>

</nav>

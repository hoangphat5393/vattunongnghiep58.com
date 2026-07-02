<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('backend.partials.admin-theme-init')

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="light dark">
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)">
    <title>Đăng nhập quản trị — {{ setting_option('webtitle') }}</title>

    <link rel="icon" type="image/png" sizes="16x16" href="{{ get_image(setting_option('favicon_16')) }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous" media="print" onload="this.media='all'" />
    <link rel="stylesheet" href="{{ asset('assets/admin/css/index.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('assets/fontawesome_pro/css/all.min.css') }}">
    <link rel="preload" href="{{ asset('assets/admin/css/adminlte.min.css') }}" as="style" />
    <link rel="stylesheet" href="{{ asset('assets/admin/css/adminlte.min.css') }}?ver={{ config('app.asset_version', '1') }}">
</head>

<body class="login-page bg-body-secondary">
    <main class="login-box">
        <div class="card card-outline card-primary shadow-sm">
            <div class="card-header">
                <a href="{{ route('admin.login') }}" class="link-dark text-center d-block text-decoration-none">
                    <h1 class="mb-0 fs-4"><b>{{ setting_option('webtitle') ?: 'Admin' }}</b></h1>
                </a>
            </div>
            <div class="card-body login-card-body">
                <p class="login-box-msg">Đăng nhập để tiếp tục phiên làm việc</p>

                <form action="{{ route('admin.login') }}" method="POST">
                    @csrf
                    <div class="input-group mb-3">
                        <div class="form-floating flex-grow-1">
                            <input type="text" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="" required autofocus autocomplete="username">
                            <label for="email">Email hoặc tên đăng nhập</label>
                        </div>
                        <span class="input-group-text"><i class="fa-solid fa-envelope" aria-hidden="true"></i></span>
                    </div>
                    @error('email')
                        <div class="text-danger small mb-2">{{ $message }}</div>
                    @enderror

                    <div class="input-group mb-3">
                        <div class="form-floating flex-grow-1">
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="" required autocomplete="current-password">
                            <label for="password">{{ __('Password') }}</label>
                        </div>
                        <span class="input-group-text btn-show-pass" role="button" tabindex="0" title="Hiện/ẩn mật khẩu" aria-label="Hiện hoặc ẩn mật khẩu"><i class="fa-solid fa-eye" aria-hidden="true"></i></span>
                    </div>
                    @error('password')
                        <div class="text-danger small mb-2">{{ $message }}</div>
                    @enderror

                    <div class="row">
                        <div class="col-8 d-inline-flex align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">{{ __('Remember me') }}</label>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">{{ __('Login') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/adminlte.min.js') }}"></script>
    <script>
        $(function() {
            $('.btn-show-pass').on('click keydown', function(e) {
                if (e.type === 'keydown' && e.key !== 'Enter' && e.key !== ' ') {
                    return;
                }
                e.preventDefault();
                var $input = $(this).closest('.input-group').find('input[name="password"]');
                var $icon = $(this).find('i');
                if ($input.attr('type') === 'password') {
                    $input.attr('type', 'text');
                    $icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    $input.attr('type', 'password');
                    $icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
        });
    </script>
</body>

</html>

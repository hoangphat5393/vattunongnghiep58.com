<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="light dark">
    <title>Đăng nhập quản trị — {{ setting_option('webtitle') }}</title>

    <link rel="icon" type="image/png" sizes="16x16" href="{{ get_image(setting_option('favicon_16')) }}">

    <link rel="stylesheet" href="{{ asset('assets/admin/css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fontawesome_pro/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/adminlte.min.css') }}?ver={{ config('app.asset_version', '1') }}">
</head>

{{-- AdminLTE v4 login v2 pattern (new-admin-ui/dist/examples/login-v2.html), Font Awesome icons --}}

<body class="login-page bg-body-secondary">
    <div class="login-box">
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
                        <span class="input-group-text btn-show-pass" role="button" tabindex="0" title="Hiện/ẩn mật khẩu"><i class="fa-solid fa-eye" aria-hidden="true"></i></span>
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
    </div>

    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/adminlte.min.js') }}"></script>
    <script>
        $(function() {
            $('.btn-show-pass').on('click', function() {
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

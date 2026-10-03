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

    <style>
        body.login-page {
            min-height: 100vh;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.72) 0%, rgba(20, 50, 30, 0.62) 50%, rgba(15, 23, 42, 0.82) 100%),
                        url('{{ asset("assets/admin/images/login-bg.jpg") }}') no-repeat center center fixed !important;
            background-size: cover !important;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
        }

        body.login-page::before {
            content: '';
            position: fixed;
            inset: 0;
            backdrop-filter: blur(3px);
            -webkit-backdrop-filter: blur(3px);
            pointer-events: none;
            z-index: 0;
        }

        .login-box {
            width: 100%;
            max-width: 450px;
            position: relative;
            z-index: 1;
        }

        .login-box .card {
            border-radius: 1.25rem;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.35);
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45), 0 0 0 1px rgba(255, 255, 255, 0.2);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .login-box .card::before {
            content: '';
            display: block;
            height: 4px;
            width: 100%;
            background: linear-gradient(90deg, #16a34a, #22c55e, #eab308);
        }

        [data-bs-theme="dark"] .login-box .card {
            background: rgba(20, 35, 25, 0.92);
            border-color: rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
        }

        .login-box .card-header {
            background: transparent;
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
            padding: 1.75rem 1.5rem 1.25rem;
            text-align: center;
        }

        [data-bs-theme="dark"] .login-box .card-header {
            border-bottom-color: rgba(255, 255, 255, 0.08);
        }

        .login-logo-img {
            max-height: 72px;
            width: auto;
            object-fit: contain;
            margin-bottom: 0.75rem;
            filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.15));
            transition: transform 0.25s ease;
        }

        .login-logo-img:hover {
            transform: scale(1.04);
        }

        .login-title {
            font-weight: 700;
            color: #0f172a;
            letter-spacing: 0.3px;
        }

        [data-bs-theme="dark"] .login-title {
            color: #f8fafc;
        }

        .login-box-msg {
            color: #64748b;
            font-size: 0.925rem;
            margin-bottom: 1.25rem;
        }

        [data-bs-theme="dark"] .login-box-msg {
            color: #94a3b8;
        }

        .login-box .form-control {
            border-radius: 0.75rem;
            border: 1px solid #cbd5e1;
            background-color: rgba(255, 255, 255, 0.95);
            transition: all 0.2s ease;
        }

        .login-box .form-control:focus {
            border-color: #16a34a;
            box-shadow: 0 0 0 4px rgba(22, 163, 74, 0.18);
            background-color: #ffffff;
        }

        [data-bs-theme="dark"] .login-box .form-control {
            background-color: rgba(15, 23, 42, 0.6);
            border-color: #475569;
            color: #f8fafc;
        }

        [data-bs-theme="dark"] .login-box .form-control:focus {
            background-color: rgba(15, 23, 42, 0.9);
            border-color: #22c55e;
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.2);
        }

        .login-box .input-group .form-floating:first-child .form-control {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            border-top-left-radius: 0.75rem;
            border-bottom-left-radius: 0.75rem;
        }

        .login-box .input-group > .input-group-text:last-child {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            border-top-right-radius: 0.75rem;
            border-bottom-right-radius: 0.75rem;
            border: 1px solid #cbd5e1;
            border-left: 0;
            background: rgba(241, 245, 249, 0.9);
            color: #64748b;
        }

        [data-bs-theme="dark"] .login-box .input-group > .input-group-text:last-child {
            background: rgba(30, 41, 59, 0.8);
            border-color: #475569;
            color: #94a3b8;
        }

        .login-box .btn-primary {
            border-radius: 0.75rem;
            padding: 0.65rem 1.25rem;
            font-weight: 600;
            letter-spacing: 0.3px;
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            border: none;
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.35);
            transition: all 0.2s ease;
        }

        .login-box .btn-primary:hover {
            background: linear-gradient(135deg, #15803d 0%, #166534 100%);
            box-shadow: 0 6px 16px rgba(22, 163, 74, 0.45);
            transform: translateY(-1px);
        }

        .login-box .btn-primary:active {
            transform: translateY(0);
        }

        .login-footer-links {
            margin-top: 1.5rem;
            text-align: center;
        }

        .login-footer-links a {
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            font-size: 0.875rem;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.6);
            transition: color 0.2s ease;
        }

        .login-footer-links a:hover {
            color: #ffffff;
            text-decoration: underline;
        }

        .login-copyright {
            color: rgba(255, 255, 255, 0.65);
            font-size: 0.8rem;
            margin-top: 0.5rem;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.6);
        }
    </style>
</head>

<body class="login-page">
    <main class="login-box">
        <div class="card shadow-lg">
            <div class="card-header border-0 pb-0">
                <a href="{{ route('admin.login') }}" class="text-decoration-none d-block">
                    @if(setting_option('logo'))
                        <img src="{{ get_image(setting_option('logo')) }}" alt="{{ setting_option('webtitle') ?: 'Vật Tư Nông Nghiệp 58' }}" class="login-logo-img">
                    @endif
                    <h1 class="mb-0 fs-4 login-title"><b>{{ setting_option('webtitle') ?: 'Vật Tư Nông Nghiệp 58' }}</b></h1>
                </a>
            </div>
            <div class="card-body login-card-body pt-3">
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

                    <div class="row align-items-center mt-4">
                        <div class="col-7">
                            <div class="form-check mb-0">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">{{ __('Remember me') }}</label>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">{{ __('Login') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="login-footer-links">
            <div>
                <a href="{{ url('/') }}">
                    <i class="fa-solid fa-arrow-left me-1"></i> Quay lại trang chủ
                </a>
            </div>
            <div class="login-copyright">
                &copy; {{ date('Y') }} {{ setting_option('webtitle') ?: 'Cửa Hàng Vật Tư Nông Nghiệp 58' }}. All rights reserved.
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

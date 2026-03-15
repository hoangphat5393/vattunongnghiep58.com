<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" href="{{ asset('assets/login/images/icons/favicon.ico') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/login/vendor/bootstrap/css/bootstrap.min.css') }}">

    <!-- Font Awesome 6.4.2 -->
    <link rel="stylesheet" href="{{ asset('assets/fontawesome_pro/css/all.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/login/fonts/Linearicons-Free-v1.0.0/icon-font.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugin/animate/animate.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/login/vendor/css-hamburgers/hamburgers.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/login/vendor/animsition/css/animsition.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/login/vendor/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/login/vendor/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/login/css/util.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/login/css/main.css') }}">
    <style>
        .btn-show-pass {
            font-size: 18px;
            color: #999999;
            display: flex;
            align-items: center;
            position: absolute;
            height: 100%;
            top: 0;
            right: 0;
            padding: 0 25px;
            cursor: pointer;
            transition: all 0.4s;
            z-index: 10;
        }

        .btn-show-pass:hover {
            color: #d41872;
        }

        .btn-show-pass.active {
            color: #d41872;
        }

        /* Adjust input padding to prevent text overlap */
        input[name="password"] {
            padding-right: 60px !important;
        }
    </style>
</head>

<body>

    <div class="limiter">
        <div class="container-login100" style="background-image: url('{{ asset('assets/login/images/bg-01.jpg') }}');">
            <div class="wrap-login100 p-t-30 p-b-50">
                <span class="login100-form-title p-b-41">
                    Admin Login
                </span>
                <form action="{{ route('admin.login') }}" method="POST" class="login100-form validate-form p-b-33 p-t-5">
                    @csrf
                    <div class="wrap-input100 validate-input" data-validate="Enter username">
                        <input class="input100 @error('email') is-invalid @enderror" type="text" name="email" id="email" placeholder="Username or Email" required autofocus>
                        <span class="focus-input100" data-placeholder="&#xe82a;"></span>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Enter password">
                        <input class="input100 @error('password') is-invalid @enderror" type="password" id="password" name="password" placeholder="Password" required autocomplete="current-password">
                        <span class="focus-input100" data-placeholder="&#xe80f;"></span>
                        <span class="btn-show-pass">
                            <i class="fa-solid fa-eye"></i>
                        </span>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="container-login100-form-btn m-t-32">
                        <button type="submit" class="login100-form-btn">
                            Login
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <div id="dropDownSelect1"></div>

    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/login/vendor/animsition/js/animsition.min.js') }}"></script>
    <script src="{{ asset('assets/login/vendor/bootstrap/js/popper.js') }}"></script>
    <script src="{{ asset('assets/login/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/login/vendor/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/login/vendor/daterangepicker/moment.min.js') }}"></script>
    <script src="{{ asset('assets/login/vendor/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/login/vendor/countdowntime/countdowntime.js') }}"></script>
    <script src="{{ asset('assets/login/js/main.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.btn-show-pass').on('click', function() {
                var input = $(this).siblings('input');
                var icon = $(this).find('i');
                if (input.attr('type') == 'password') {
                    input.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                    $(this).addClass('active');
                } else {
                    input.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                    $(this).removeClass('active');
                }
            });
        });
    </script>

</body>

</html>

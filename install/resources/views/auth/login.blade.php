@php
    $appSetting = App\Models\AppSetting::first();
@endphp
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Fav icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ $appSetting?->websiteFaviconPath ?? asset('web/logo.png') }}">
    <!-- custome css -->
    <link rel="stylesheet" href="{{ asset('web/css/login.css') }}">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('web/css/bootstrap.css') }}">
    <!-- Font awesome -->
    <link rel="stylesheet" href="{{ asset('web/css/all.min.css') }}">
    <title>{{ config('app.name') }} Log In</title>
</head>

<body>

    <!-- Login-Section -->
    <section class="login-section">
        <form role="form" class="pui-form pt-md-5" id="loginform" method="POST" action="{{ route('login') }}"> @csrf
            <div class="card loginCard">
                <div class="logo-section">
                    <img src="{{ $appSetting?->websiteLogoPath ?? asset('web/logo.png') }}" alt="" width="100%">
                </div>
                <div class="card-body">
                    <div class="page-content text-center">
                        <h2 class="pageTitle mb-3">Dashboard Login</h2>
                        <p class="pagePera">Hay, Enter your details to get login to your account</p>
                    </div>

                    <div class="form-outline form-white mb-4">
                        <input type="text" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" placeholder="Email">
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-outline form-white mb-3">
                        <div class="position-relative passwordInput">
                            <input type="password" name="password" id="password"
                                class="form-control py-2 @error('password') is-invalid @enderror"
                                placeholder="Password">
                            <span class="eye" onclick="showHidePassword()">
                                <i class="far fa-eye-slash fa-eye" id="togglePassword"></i>
                            </span>
                        </div>
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    @if (config('app.env') == 'local')
                    <button onclick="copy()" class="btn btn-sm btn-primary bgBlue w-100" type="button">
                        Click to copy credentials
                    </button>
                    @endif

                    <style>
                        .bgBlue {
                            background: #2c9de4;
                            color: #fff !important;
                        }
                    </style>

                    <button type="submit" class="btn loginButton" type="submit">Login</button>

                </div>
            </div>
        </form>
    </section>
    <!--End-Login-Section -->

    <script src="{{ asset('web/js/jquery.min.js') }}"></script>
    <script>
        function showHidePassword() {
            const toggle = document.getElementById("togglePassword");
            const password = document.getElementById("password");

            // toggle the type attribute
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            // toggle the icon
            toggle.classList.toggle("fa-eye-slash");
        }

        function copy() {
            $('#email').val('root@laundrymart.com');
            $('#password').val('secret@123');
        }
    </script>

</body>

</html>

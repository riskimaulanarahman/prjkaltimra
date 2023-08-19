<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ appName() }}</title>
    <meta name="description" content="@yield('meta_description', appName())">
    <meta name="author" content="@yield('meta_author', 'Honda Balikpapan')">
    <link rel="shortcut icon" href="{{ asset('https://cdn.iconscout.com/icon/free/png-256/free-honda-motor-3441262-2874711.png') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('https://cdn.iconscout.com/icon/free/png-256/free-honda-motor-3441262-2874711.png') }}" type="image/x-icon">
    @yield('meta')

    @stack('before-styles')
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="{{ '/css/frontend.css' }}" rel="stylesheet">
    <style>
        html, body {
            background-color:#fafcfe;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }

        /* Gaya untuk memusatkan tombol-tombol */
        .center-links {
            text-align: center;
        }

        .center-links button {
    display: inline-block;
    margin: 10px;
    padding: 10px 20px;
    /* background-color: transparent; */
    border: none;
    cursor: pointer;
    position: relative;
    text-align: center;
}

.button-icon {
    display: block;
    max-width: 50px; /* Sesuaikan ukuran maksimum gambar */
    margin: 0 auto 5px;
}

.button-text {
    display: block;
    color: white;
}

/* Warna latar belakang tombol */
.admin-button {
    background-color: #3498db; /* Ganti dengan warna yang diinginkan */
}

.pusat-button {
    background-color: #e74c3c; /* Ganti dengan warna yang diinginkan */
}

.cabang-button {
    background-color: #2ecc71; /* Ganti dengan warna yang diinginkan */
}
    </style>
    @stack('after-styles')
</head>
<body>
    {{-- @include('includes.partials.read-only')
    @include('includes.partials.logged-in-as') --}}
    @include('includes.partials.announcements')

    <div id="app" class="flex-center position-ref full-height">
        {{-- <div class="top-right links">
            @auth
                @if ($logged_in_user->isUser())
                    <a href="{{ route('frontend.user.dashboard') }}">@lang('Dashboard')</a>
                @endif

                <a href="{{ route('frontend.user.account') }}">@lang('Account')</a>
            @else
                <a href="{{ route('frontend.auth.login') }}">@lang('Login')</a>

                @if (config('boilerplate.access.user.registration'))
                    <a href="{{ route('frontend.auth.register') }}">@lang('Register')</a>
                @endif
            @endauth
        </div> --}}
        <!--top-right-->

        <div class="content">
            {{-- @include('includes.partials.messages') --}}

            {{-- <div class="title m-b-md">
                <example-component></example-component>
            </div> --}}
            <!--title-->

            <!-- Memusatkan tombol-tombol -->
            <div class="center-links">
                <a href="/login"><button class="admin-button">
                    <img src="{{ asset('img/logo/logo-astra-putih.png') }}" alt="Logo Astra" class="button-icon">
                    <br>
                    <span class="button-text">Admin</span>
                </button></a>
                <a href="/main/login"><button class="pusat-button">
                    <img src="{{ asset('img/logo/logo-astra-putih.png') }}" alt="Logo Astra" class="button-icon">
                    <br>
                    <span class="button-text">Pusat</span>
                </button></a>
                <a href="/cabang/login"><button class="cabang-button">
                    <img src="{{ asset('img/logo/logo-astra-putih.png') }}" alt="Logo Astra" class="button-icon">
                    <br>
                    <span class="button-text">Cabang</span>
                </button></a>
            </div><!--center-links -->
        </div><!--content-->
    </div><!--app-->

    @stack('before-scripts')
    <script src="{{ '/js/manifest.js' }}"></script>
    <script src="{{ '/js/vendor.js' }}"></script>
    <script src="{{ '/js/frontend.js' }}"></script>
    @stack('after-scripts')
</body>
</html>

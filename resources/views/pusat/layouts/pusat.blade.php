<!doctype html>
<html lang="{{ htmlLang() }}" @langrtl dir="rtl" @endlangrtl>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ appName() }} Main Dealer | @yield('title')</title>
    <meta name="description" content="@yield('meta_description', appName())">
    <meta name="author" content="@yield('meta_author', 'Honda Balikpapan')">

    {{--  auto refresh, g perlu di git commit / push --}}
    {{-- <meta http-equiv="refresh" content="10"> --}}

    @yield('meta')

    @stack('before-styles')
    <link href="{{ '/css/backend.css' }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn3.devexpress.com/jslib/23.1.4/css/dx.light.css">


    {{-- <livewire:styles /> --}}
    @stack('after-styles')
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

 
    <!-- DevExtreme library -->

    <link href='https://api.mapbox.com/mapbox.js/v3.3.1/mapbox.css' rel='stylesheet' />
    <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-locatecontrol/v0.43.0/L.Control.Locate.mapbox.css' rel='stylesheet' />
    <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-locatecontrol/v0.43.0/css/font-awesome.min.css' rel='stylesheet' />


    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/fontawesome.min.js" integrity="sha512-5qbIAL4qJ/FSsWfIq5Pd0qbqoZpk5NcUVeAAREV2Li4EKzyJDEGlADHhHOSSCw0tHP7z3Q4hNHJXa81P92borQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <script type="text/javascript" src="https://cdn3.devexpress.com/jslib/23.1.4/js/dx.all.js"></script>

    <style>
        .c-sidebar {
            background-color: #29363d;
        }

        .c-sidebar .c-sidebar-nav-link.c-active .c-sidebar-nav-icon {
            color: #ED1B25;
        }
        .select2 {
        width:100%!important;
        }
        .select2-container .select2-selection--single {
            height: 35px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 33px;
        }
        .zoom {
            position: relative;
        }
        .zoom:hover {
            -ms-transform: scale(5); /* IE 9 */
            -webkit-transform: scale(5); /* Safari 3-8 */
            transform: scale(5);
            z-index: 999;
        }
    </style>
    <style>
        #mapid { height: 700px; }
        #loading {
            position: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            opacity: 0.7;
            background-color: #fff;
            z-index: 99;
        }

        #loading-image {
            z-index: 100;
        }
    </style>

</head>
<body class="c-app">
    @include('pusat.includes.sidebar')

    <div class="c-wrapper c-fixed-components">
        @include('pusat.includes.header')

        <div id="loading">
            <div class="text-center">
                <div id="loading-image" class="spinner-border text-danger" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
        <div class="c-body">
            <main class="c-main">
                <div class="container-fluid">
                    <div class="fade-in">
                        @include('includes.partials.messages')
                        @yield('content')
                    </div><!--fade-in-->
                </div><!--container-fluid-->
            </main>
        </div><!--c-body-->

        @include('pusat.includes.footer')
    </div><!--c-wrapper-->

    @stack('before-scripts')
    <script src="{{ '/js/manifest.js' }}"></script>
    <script src="{{ '/js/vendor.js' }}"></script>
    <script src="{{ '/js/backend.js' }}"></script>
    {{-- <livewire:scripts /> --}}
    @stack('after-scripts')
    <script>
        window.addEventListener( "pageshow", function ( event ) {
        var historyTraversal = event.persisted ||
                                ( typeof window.performance != "undefined" &&
                                    window.performance.navigation.type === 2 );
        if ( historyTraversal ) {
            // Handle page restore.
            window.location.reload();
        }
        });
    </script>
    <script>
        $(window).on('load', function () {
          $('#loading').hide();
        })
    </script>
</body>
</html>

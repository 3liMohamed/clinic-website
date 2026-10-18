<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $getDir() }}">

<head>
    <meta charset="utf-8">

    {{-- Responsive --}}
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Browser Compatibility --}}
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    {{-- CSRF --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO --}}
    <title>@setting('site:name', config('app.name')), {{ $title }}</title>

    <meta name="description" content="{{ $description }}">

    <meta name="keywords" content="{{ $keywords }}">


    <meta name="author" content="Ali Mohamed">
    <meta name="copyright" content="2026 &copy; All rights reserved. Designed & Developed by Ali Mohamed">


    {{-- Robots --}}
    <meta name="robots" content="index, follow">

    {{-- Canonical --}}
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph --}}
    <meta property="og:type" content="{{ $ogType }}">

    <meta property="og:title" content="{{ $title }}">

    <meta property="og:description" content="{{ $description }}">

    <meta property="og:image" content="{{ $image }}">

    <meta property="og:url" content="{{ url()->current() }}">

    <meta property="og:site_name" content="@setting('site:name', config('app.name'))">

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">

    <meta name="twitter:title" content="{{ $title }}">

    <meta name="twitter:description" content="{{ $description }}">

    <meta name="twitter:image" content="{{ $image }}">

    {{-- Favicon --}}
    <link rel="shortcut icon" href="@settingAsset('logo:favicon', 'default.png')" type="image/png">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Poppins:200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap"
        rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="@asset('assets/css/bootstrap.min.css')" />
    <!-- Nice Select CSS -->
    <link rel="stylesheet" href="@asset('assets/css/nice-select.css')" />
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="@asset('assets/css/font-awesome.min.css')" />
    <!-- icofont CSS -->
    {{-- <link rel="stylesheet" href="@asset('assets/css/icofont.css')" /> --}}
    <link rel="stylesheet" href="@asset('assets/css/icofont.min.css')" />
    <!-- Slicknav -->
    <link rel="stylesheet" href="@asset('assets/css/slicknav.min.css')" />
    <!-- Owl Carousel CSS -->
    <link rel="stylesheet" href="@asset('assets/css/owl-carousel.css')" />
    <!-- Datepicker CSS -->
    <link rel="stylesheet" href="@asset('assets/css/datepicker.css')" />
    <!-- Animate CSS -->
    <link rel="stylesheet" href="@asset('assets/css/animate.min.css')" />
    <!-- Magnific Popup CSS -->
    <link rel="stylesheet" href="@asset('assets/css/magnific-popup.css')" />

    <!-- Mediplus CSS -->
    <link rel="stylesheet" href="@asset('assets/css/normalize.css')" />
    <link rel="stylesheet" href="@asset('assets/css/style.css')" />
    <link rel="stylesheet" href="@asset('assets/css/responsive.css')" />

    <!-- Color CSS -->
    <link rel="stylesheet" href="@asset('assets/css/color/color1.css')" />

</head>

<body class="{{ $getDir() }}">


    @include('layouts.loader')

    <x-layouts.header />

    {{ $slot }}

    <x-layouts.footer />



    {{-- JS --}}

    <!-- jquery Min JS -->
    <script src="@asset('assets/js/jquery.min.js')"></script>
    <!-- jquery Migrate JS -->
    <script src="@asset('assets/js/jquery-migrate.js')"></script>
    <!-- Easing JS -->
    <script src="@asset('assets/js/easing.js')"></script>
    <!-- Color JS -->
    <script src="@asset('assets/js/colors.js')"></script>
    <!-- Popper JS -->
    <script src="@asset('assets/js/popper.min.js')"></script>
    <!-- Bootstrap JS -->
    <script src="@asset('assets/js/bootstrap.min.js')"></script>
    <!-- Bootstrap Datepicker JS -->
    <script src="@asset('assets/js/bootstrap-datepicker.js')"></script>
    <!-- Jquery Nav JS -->
    <script src="@asset('assets/js/jquery.nav.js')"></script>
    <!-- Slicknav JS -->
    <script src="@asset('assets/js/slicknav.min.js')"></script>
    <!-- ScrollUp JS -->
    <script src="@asset('assets/js/jquery.scrollUp.min.js')"></script>
    <!-- Niceselect JS -->
    <script src="@asset('assets/js/niceselect.js')"></script>
    <!-- Tilt Jquery JS -->
    <script src="@asset('assets/js/tilt.jquery.min.js')"></script>
    <!-- Owl Carousel JS -->
    <script src="@asset('assets/js/owl-carousel.js')"></script>
    <!-- counterup JS -->
    <script src="@asset('assets/js/jquery.counterup.min.js')"></script>
    <script src="@asset('assets/js/waypoints.min.js')"></script>
    <!-- Steller JS -->
    <script src="@asset('assets/js/steller.js')"></script>
    <!-- Wow JS -->
    <script src="@asset('assets/js/wow.min.js')"></script>
    <!-- Magnific Popup JS -->
    <script src="@asset('assets/js/jquery.magnific-popup.min.js')"></script>
    <!-- Main JS -->
    <script src="@asset('assets/js/main.js')"></script>

</body>

</html>

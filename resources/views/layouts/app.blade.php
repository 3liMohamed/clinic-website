<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $getDir }}">

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

</head>

<body>

    <div class="page-wrapper dark-layout">

        {{-- @include('layouts.loader') --}}

        <x-layouts.header />

        {{ $slot }}

        @include('layouts.footer')

    </div>



    {{-- JS --}}

</body>

</html>

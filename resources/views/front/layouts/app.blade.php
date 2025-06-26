<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from quomodothemes.website/html/shopus/ by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 15 Nov 2023 07:46:51 GMT -->

<head>
    <meta charset="utf-8" />
    <meta name="keywords"
        content="ShopUS, bootstrap-5, bootstrap, sass, css, HTML Template, HTML,html, bootstrap template, free template, figma, web design, web development,front end, bootstrap datepicker, bootstrap timepicker, javascript, ecommerce template" />
    <meta name="description" content="{{ $setting->meta_description }}"/>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="{{ $setting->getFaviconAttribute() }}" />

    <title> {{ $setting->site_name }} :: @yield('title') </title>

    <link rel="stylesheet" href="{{ asset('assets/front/assets/css/swiper10-bundle.min.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/front/assets/css/bootstrap-5.3.2.min.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/front/assets/css/nouislider.min.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/front/assets/css/aos-3.0.0.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/front/assets/css/style.css') }}" />
</head>

<body>
    @include('front.layouts.header')

    @yield('content')
    @include('front.layouts.footer')

    <script src="{{ asset('assets/front/assets/js/jquery_3.7.1.min.js') }}"></script>

    <script src="{{ asset('assets/front/assets/js/bootstrap_5.3.2.bundle.min.js') }}"></script>

    <script src="{{ asset('assets/front/assets/js/nouislider.min.js') }}"></script>

    <script src="{{ asset('assets/front/assets/js/aos-3.0.0.js') }}"></script>

    <script src="{{ asset('assets/front/assets/js/swiper10-bundle.min.js') }}"></script>

    <script src="{{ asset('assets/front/assets/js/shopus.js') }}"></script>
    @yield('js')
</body>

</html>

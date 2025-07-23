<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="rtl">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content=" {{ $setting->meta_description }} ">
    <meta name="author" content="Mohamed Ramadan +201011642731">
    <title> @yield('title')
    </title>
    <link rel="apple-touch-icon" href="{{ asset($setting->favicon) }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset($setting->favicon) }}">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Quicksand:300,400,500,700"
        rel="stylesheet">
    <link href="https://maxcdn.icons8.com/fonts/line-awesome/1.1/css/line-awesome.min.css" rel="stylesheet">
    <!-- BEGIN VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/') }}/css-rtl/vendors.css">
    <!-- END VENDOR CSS-->
    <!-- BEGIN MODERN CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/') }}/css-rtl/app.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/') }}/css-rtl/custom-rtl.css">
    <!-- END MODERN CSS-->
    <!-- BEGIN Page Level CSS-->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/admin/') }}/css-rtl/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/') }}/css-rtl/core/colors/palette-gradient.css">
    <!-- END Page Level CSS-->

    <!-- BEGIN Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/') }}/style-rtl.css">

    <!-------- File Input --------->
    <link rel="stylesheet" href="{{ asset('vendor/fileinput/css/fileinput.css') }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <!-------- End File Input ---------->
    @yield('css')
    @toastifyCss
    <!-- END Custom CSS-->
</head>

<body class="vertical-layout vertical-menu 2-columns menu-expanded fixed-navbar" data-open="click"
    data-menu="vertical-menu" data-col="2-columns">
    <!-- fixed-top-->

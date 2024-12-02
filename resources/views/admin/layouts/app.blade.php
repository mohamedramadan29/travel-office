<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="rtl">

@include('admin.layouts._head_scripts')

<body class="vertical-layout vertical-menu-modern 2-columns menu-expanded fixed-navbar" data-open="click"
    data-menu="vertical-menu-modern" data-col="2-columns">
    <!-- fixed-top-->
    @include('admin.layouts._header')
    <!-- ////////////////////////////////////////////////////////////////////////////-->
    @include('admin.layouts._sidebar')
    @yield('content')
    <!-- ////////////////////////////////////////////////////////////////////////////-->
    @include('admin.layouts._footer')
    @include('admin.layouts._footer_scripts')
</body>

</html>

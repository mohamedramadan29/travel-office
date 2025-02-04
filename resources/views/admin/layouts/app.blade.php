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
    <!---------------------------- Success Failes MEssages  ------------------>
    @if (Session::has('Success_message'))
        @php
            toastify()->success(\Illuminate\Support\Facades\Session::get('Success_message'));
        @endphp
    @endif
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            @php
                toastify()->error($error);
            @endphp
        @endforeach
    @endif
    <!-- ////////////////////////////////////////////////////////////////////////////-->
    @include('admin.layouts._footer')
    @include('admin.layouts._footer_scripts')
</body>

</html>

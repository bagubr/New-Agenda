<!DOCTYPE html>
<html lang="en"> <!--begin::Head-->

<head>
    @include('templates.css')
</head> <!--end::Head--> <!--begin::Body-->
@stack('css')
<body class="layout-fixed sidebar-mini sidebar-collapse bg-body-tertiary"> <!--begin::App Wrapper-->
    <div class="app-wrapper"> <!--begin::Header-->
        @include('templates.navbar')
        @include('templates.sidebar')
        @yield('content')
        @include('templates.footer')
    </div> <!--end::App Wrapper--> <!--begin::Script--> <!--begin::Third Party Plugin(OverlayScrollbars)-->
    @include('templates.js')
</body><!--end::Body-->
@stack('js')
</html>
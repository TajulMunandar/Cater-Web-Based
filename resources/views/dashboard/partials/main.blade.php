<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catat Meter Perumda Tirta Pase</title>
    @include('dashboard.partials.head')
</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <!-- Sidebar Start -->
        @include('dashboard.partials.sidebar')
        <!--  Sidebar End -->
        <!--  Main wrapper -->
        <div class="body-wrapper">
            <!--  Header Start -->
            @include('dashboard.partials.navbar')
            <!--  Header End -->
            <div class="container-fluid" data-aos="fade-up" data-aos-offset="300" data-aos-easing="ease-in-sine">
                @yield('content')
            </div>
        </div>
    </div>
    @include('dashboard.partials.script')
    <script>
        AOS.init();
    </script>
    @stack('script')
</body>

</html>

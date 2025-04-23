<!doctype html>
<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catat Meter Perumda Tirta Pase</title>
    @include('dashboard.partials.head')
    @stack('head')
</head>

<body>
    <div class="toast toast-onload align-items-center text-bg-primary border-0" role="alert" aria-live="assertive"
        aria-atomic="true">
        <div class="toast-body hstack align-items-start gap-6">
            <i class="ti ti-alert-circle fs-6"></i>
            <div>
                <h5 class="text-white fs-3 mb-1">Welcome</h5>
                <h6 class="text-white fs-2 mb-0">Semoga Harimu Menyenangkan!!!</h6>
            </div>
            <button type="button" class="btn-close btn-close-white fs-2 m-0 ms-auto shadow-none"
                data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
    <div class="preloader">
        <img src="{{ asset('assets/images/logos/logo.png') }}" alt="loader" class="lds-ripple img-fluid" />
    </div>
    <!--  Body Wrapper -->
    <div id="main-wrapper">
        <!-- Sidebar Start -->
        @include('dashboard.partials.sidebar')
        <!--  Sidebar End -->
        <div class="page-wrapper">
            <!--  Header Start -->
            @include('dashboard.partials.navbar')
            <!--  Header End -->
            @include('dashboard.partials.sidebar2')
            <div class="body-wrapper">
                <!--  Main wrapper -->
                <div class="container-fluid">
                    @include('dashboard.partials.banner')
                    <div class="px-3">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="dark-transparent sidebartoggler"></div>

    @include('dashboard.partials.script')
    @stack('script')

</body>

</html>

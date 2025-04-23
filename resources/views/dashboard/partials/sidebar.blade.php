<aside class="left-sidebar with-vertical">
    <!-- Sidebar scroll-->
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="/" class="text-nowrap logo-img">
                <img src="{{ asset('assets/images/logos/logo.png') }}" class="dark-logo" alt="Logo-Dark" width="50%">
                <img src="{{ asset('assets/images/logos/logo.png') }}" class="light-logo" alt="Logo-light"
                    width="50%" />
            </a>
            <a href="javascript:void(0)" class="sidebartoggler ms-auto text-decoration-none fs-5 d-block d-xl-none">
                <i class="ti ti-x"></i>
            </a>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar>
            <ul id="sidebarnav">
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Home</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->is('/') ? 'active' : '' }}" href="/"
                        aria-expanded="false">
                        <span>
                            <i class="ti ti-layout-dashboard"></i>
                        </span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->is('cater*') ? 'active' : '' }}" href="/cater/index"
                        aria-expanded="false">
                        <span>
                            <i class="ti ti-target"></i>
                        </span>
                        <span class="hide-menu">Catat Meter</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->is('rekap*') ? 'active' : '' }}" href="/rekap/index"
                        aria-expanded="false">
                        <span>
                            <i class="ti ti-dashboard"></i>
                        </span>
                        <span class="hide-menu">Data Rekap</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Data Master</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->is('pelanggan*') ? 'active' : '' }}" href="/pelanggan/index"
                        aria-expanded="false">
                        <span>
                            <i class="ti ti-users"></i>
                        </span>
                        <span class="hide-menu">Pelanggan</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->is('settings*') ? 'active' : '' }}" href="/settings/wilayah"
                        aria-expanded="false">
                        <span>
                            <i class="ti ti-settings"></i>
                        </span>
                        <span class="hide-menu">Setting</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->is('info') ? 'active' : '' }}" href="/info"
                        aria-expanded="false">
                        <span>
                            <i class="ti ti-help"></i>
                        </span>
                        <span class="hide-menu">Info</span>
                    </a>
                </li>

            </ul>

        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>

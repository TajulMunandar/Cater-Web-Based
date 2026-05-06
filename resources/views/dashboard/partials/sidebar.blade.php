<aside class="left-sidebar with-vertical">
    <!-- Sidebar scroll-->
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="/" class="text-nowrap logo-img">
                <img src="{{ asset('assets/images/logo.png') }}" class="dark-logo" alt="Logo-Dark" width="230px">
                <img src="{{ asset('assets/images/logo.png') }}" class="light-logo" alt="Logo-light" width="230px" />
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
                    <a class="sidebar-link {{ request()->is('cater*') ? 'active' : '' }}" href="{{ route('cater.index') }}"
                        aria-expanded="false">
                        <span>
                            <i class="ti ti-target"></i>
                        </span>
                        <span class="hide-menu">Catat Meter</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->is('cater/tidak-terdaftar*') ? 'active' : '' }}" href="{{ route('cater.tidak-terdaftar') }}"
                        aria-expanded="false">
                        <span>
                            <i class="ti ti-alert-circle"></i>
                        </span>
                        <span class="hide-menu">Catat Meter Tidak Terdaftar</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->is('cater/urutan*') ? 'active' : '' }}" href="{{ route('cater.urutan') }}"
                        aria-expanded="false">
                        <span>
                            <i class="ti ti-list-numbers"></i>
                        </span>
                        <span class="hide-menu">Urutan Catat Meter</span>
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
                    <a class="sidebar-link {{ request()->is('pelanggan*') ? 'active' : '' }}" href="/pelanggan"
                        aria-expanded="false">
                        <span>
                            <i class="ti ti-users"></i>
                        </span>
                        <span class="hide-menu">Pelanggan</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->is('settings/wilayah*') ? 'active' : '' }}" href="/settings/wilayah"
                        aria-expanded="false">
                        <span>
                            <i class="ti ti-map"></i>
                        </span>
                        <span class="hide-menu">Wilayah</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->is('settings/golongan*') ? 'active' : '' }}" href="/settings/golongan"
                        aria-expanded="false">
                        <span>
                            <i class="ti ti-tag"></i>
                        </span>
                        <span class="hide-menu">Golongan</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->is('settings/kondisi*') ? 'active' : '' }}" href="/settings/kondisi"
                        aria-expanded="false">
                        <span>
                            <i class="ti ti-gauge"></i>
                        </span>
                        <span class="hide-menu">Kondisi</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->is('settings/petugas*') ? 'active' : '' }}" href="/settings/petugas"
                        aria-expanded="false">
                        <span>
                            <i class="ti ti-user"></i>
                        </span>
                        <span class="hide-menu">Petugas</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Lainnya</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link {{ request()->is('info') ? 'active' : '' }}" href="/info"
                        aria-expanded="false">
                        <span>
                            <i class="ti ti-info-circle"></i>
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

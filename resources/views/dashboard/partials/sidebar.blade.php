<aside class="left-sidebar" style="background:var(--bg-sidebar);border-right:1px solid var(--color-border);min-height:100vh;position:fixed;top:0;left:0;z-index:1050;display:flex;flex-direction:column;">
    <div class="sidebar-header d-flex align-items-center px-4" style="height:64px;border-bottom:1px solid var(--color-border-light);flex-shrink:0;gap:10px;">
        <a href="/" class="d-flex align-items-center gap-2 text-decoration-none overflow-hidden">
            <img id="sidebarLogo" src="{{ asset('assets/images/logo_full.png') }}" alt="Logo" height="32" class="flex-shrink-0" style="transition:all 0.3s ease;">
        </a>
    </div>

    <nav class="flex-grow-1 overflow-auto px-3 py-3" style="scrollbar-width:thin;">
        <ul class="sidebar-nav list-unstyled mb-0 d-flex flex-column gap-1">
            <li class="sidebar-label" style="font-size:0.65rem;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:var(--color-text-muted);padding:0.5rem 0.75rem 0.25rem;">Home</li>
            <li>
                <a href="/" class="sidebar-link d-flex align-items-center gap-3 text-decoration-none px-3 py-2 rounded-3 {{ request()->is('/') ? 'active' : '' }}"
                   style="font-size:0.85rem;font-weight:500;color:var(--color-text-secondary);transition:all var(--transition);{{ request()->is('/') ? 'background:var(--color-primary-light);color:var(--color-primary);font-weight:600;' : '' }}"
                   onmouseover="this.style.background='var(--color-border-light)'" onmouseout="this.style.background='{{ request()->is('/') ? 'var(--color-primary-light)' : 'transparent' }}'"
                   data-bs-toggle="tooltip" data-bs-placement="right" title="Dashboard">
                    <i class="ti ti-layout-dashboard" style="font-size:1.15rem;width:20px;text-align:center;flex-shrink:0;"></i>
                    <span class="link-text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('cater.index') }}" class="sidebar-link d-flex align-items-center gap-3 text-decoration-none px-3 py-2 rounded-3 {{ request()->is('cater*') ? 'active' : '' }}"
                   style="font-size:0.85rem;font-weight:500;color:var(--color-text-secondary);transition:all var(--transition);"
                   onmouseover="this.style.background='var(--color-border-light)'" onmouseout="this.style.background='transparent'"
                   data-bs-toggle="tooltip" data-bs-placement="right" title="Catat Meter">
                    <i class="ti ti-target" style="font-size:1.15rem;width:20px;text-align:center;flex-shrink:0;"></i>
                    <span class="link-text">Catat Meter</span>
                </a>
            </li>
            <li>
                <a href="{{ route('rekap.index') }}" class="sidebar-link d-flex align-items-center gap-3 text-decoration-none px-3 py-2 rounded-3 {{ request()->is('rekap*') ? 'active' : '' }}"
                   style="font-size:0.85rem;font-weight:500;color:var(--color-text-secondary);transition:all var(--transition);"
                   onmouseover="this.style.background='var(--color-border-light)'" onmouseout="this.style.background='transparent'"
                   data-bs-toggle="tooltip" data-bs-placement="right" title="Data Rekap">
                    <i class="ti ti-dashboard" style="font-size:1.15rem;width:20px;text-align:center;flex-shrink:0;"></i>
                    <span class="link-text">Data Rekap</span>
                </a>
            </li>

            <li class="sidebar-label" style="font-size:0.65rem;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:var(--color-text-muted);padding:0.5rem 0.75rem 0.25rem;margin-top:0.5rem;">Data Master</li>
            <li>
                <a href="/pelanggan" class="sidebar-link d-flex align-items-center gap-3 text-decoration-none px-3 py-2 rounded-3 {{ request()->is('pelanggan*') ? 'active' : '' }}"
                   style="font-size:0.85rem;font-weight:500;color:var(--color-text-secondary);transition:all var(--transition);"
                   onmouseover="this.style.background='var(--color-border-light)'" onmouseout="this.style.background='transparent'"
                   data-bs-toggle="tooltip" data-bs-placement="right" title="Pelanggan">
                    <i class="ti ti-users" style="font-size:1.15rem;width:20px;text-align:center;flex-shrink:0;"></i>
                    <span class="link-text">Pelanggan</span>
                </a>
            </li>
            <li>
                <a href="/settings/wilayah" class="sidebar-link d-flex align-items-center gap-3 text-decoration-none px-3 py-2 rounded-3 {{ request()->is('settings/wilayah*') ? 'active' : '' }}"
                   style="font-size:0.85rem;font-weight:500;color:var(--color-text-secondary);transition:all var(--transition);"
                   onmouseover="this.style.background='var(--color-border-light)'" onmouseout="this.style.background='transparent'"
                   data-bs-toggle="tooltip" data-bs-placement="right" title="Wilayah">
                    <i class="ti ti-map" style="font-size:1.15rem;width:20px;text-align:center;flex-shrink:0;"></i>
                    <span class="link-text">Wilayah</span>
                </a>
            </li>
            <li>
                <a href="/settings/golongan" class="sidebar-link d-flex align-items-center gap-3 text-decoration-none px-3 py-2 rounded-3 {{ request()->is('settings/golongan*') ? 'active' : '' }}"
                   style="font-size:0.85rem;font-weight:500;color:var(--color-text-secondary);transition:all var(--transition);"
                   onmouseover="this.style.background='var(--color-border-light)'" onmouseout="this.style.background='transparent'"
                   data-bs-toggle="tooltip" data-bs-placement="right" title="Golongan">
                    <i class="ti ti-tag" style="font-size:1.15rem;width:20px;text-align:center;flex-shrink:0;"></i>
                    <span class="link-text">Golongan</span>
                </a>
            </li>
            <li>
                <a href="/settings/kondisi" class="sidebar-link d-flex align-items-center gap-3 text-decoration-none px-3 py-2 rounded-3 {{ request()->is('settings/kondisi*') ? 'active' : '' }}"
                   style="font-size:0.85rem;font-weight:500;color:var(--color-text-secondary);transition:all var(--transition);"
                   onmouseover="this.style.background='var(--color-border-light)'" onmouseout="this.style.background='transparent'"
                   data-bs-toggle="tooltip" data-bs-placement="right" title="Kondisi">
                    <i class="ti ti-gauge" style="font-size:1.15rem;width:20px;text-align:center;flex-shrink:0;"></i>
                    <span class="link-text">Kondisi</span>
                </a>
            </li>
            <li>
                <a href="/settings/petugas" class="sidebar-link d-flex align-items-center gap-3 text-decoration-none px-3 py-2 rounded-3 {{ request()->is('settings/petugas*') ? 'active' : '' }}"
                   style="font-size:0.85rem;font-weight:500;color:var(--color-text-secondary);transition:all var(--transition);"
                   onmouseover="this.style.background='var(--color-border-light)'" onmouseout="this.style.background='transparent'"
                   data-bs-toggle="tooltip" data-bs-placement="right" title="Petugas">
                    <i class="ti ti-user" style="font-size:1.15rem;width:20px;text-align:center;flex-shrink:0;"></i>
                    <span class="link-text">Petugas</span>
                </a>
            </li>

            <li class="sidebar-label" style="font-size:0.65rem;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:var(--color-text-muted);padding:0.5rem 0.75rem 0.25rem;margin-top:0.5rem;">Lainnya</li>
            <li>
                <a href="/info" class="sidebar-link d-flex align-items-center gap-3 text-decoration-none px-3 py-2 rounded-3 {{ request()->is('info') ? 'active' : '' }}"
                   style="font-size:0.85rem;font-weight:500;color:var(--color-text-secondary);transition:all var(--transition);"
                   onmouseover="this.style.background='var(--color-border-light)'" onmouseout="this.style.background='transparent'"
                   data-bs-toggle="tooltip" data-bs-placement="right" title="Info">
                    <i class="ti ti-info-circle" style="font-size:1.15rem;width:20px;text-align:center;flex-shrink:0;"></i>
                    <span class="link-text">Info</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="px-3 py-3" style="border-top:1px solid var(--color-border-light);flex-shrink:0;">
        <a href="{{ route('logout') }}" class="sidebar-link d-flex align-items-center gap-3 text-decoration-none px-3 py-2 rounded-3"
           style="font-size:0.85rem;font-weight:500;color:var(--color-danger);transition:all var(--transition);"
           onclick="event.preventDefault();document.getElementById('logout-form').submit();"
           onmouseover="this.style.background='var(--color-danger-light)'" onmouseout="this.style.background='transparent'"
           data-bs-toggle="tooltip" data-bs-placement="right" title="Logout">
            <i class="ti ti-logout" style="font-size:1.15rem;width:20px;text-align:center;flex-shrink:0;"></i>
            <span class="link-text">Logout</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
    </div>
</aside>

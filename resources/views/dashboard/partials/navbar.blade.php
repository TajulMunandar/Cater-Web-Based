<header class="navbar-modern" style="height:64px;display:flex;align-items:center;justify-content:space-between;padding:0 1.5rem;position:sticky;top:0;z-index:1020;">
    <div class="d-flex align-items-center gap-3">
        <a href="javascript:void(0)" class="sidebartoggler d-flex align-items-center justify-content-center text-decoration-none"
           style="width:36px;height:36px;border-radius:var(--radius-sm);color:var(--color-text-secondary);transition:var(--transition);"
           onmouseover="this.style.background='var(--color-border-light)'" onmouseout="this.style.background='transparent'">
            <i class="ti ti-menu-2 sidebar-hamburger" style="font-size:1.25rem;transition:transform 0.3s cubic-bezier(0.4,0,0.2,1), opacity 0.2s ease;"></i>
        </a>

    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('pelanggan.baru.index') }}" class="d-flex align-items-center justify-content-center position-relative text-decoration-none"
           style="width:38px;height:38px;border-radius:50%;color:var(--color-text-secondary);transition:var(--transition);"
           onmouseover="this.style.background='var(--color-border-light)'" onmouseout="this.style.background='transparent'">
            <i class="ti ti-bell" style="font-size:1.2rem;"></i>
            @if(isset($stats) && ($stats['pelanggan_baru_bulan_ini'] ?? 0) > 0)
                <span class="position-absolute" style="top:4px;right:4px;width:8px;height:8px;background:var(--color-danger);border-radius:50%;border:2px solid #fff;"></span>
            @endif
        </a>
        <div class="dropdown">
            <a href="javascript:void(0)" class="d-flex align-items-center gap-2 text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"
               style="padding:0.25rem 0.5rem;border-radius:100px;transition:var(--transition);"
               onmouseover="this.style.background='var(--color-border-light)'" onmouseout="this.style.background='transparent'">
                <div class="d-flex align-items-center justify-content-center rounded-circle" style="width:32px;height:32px;background:var(--color-primary);color:#fff;font-size:0.75rem;font-weight:600;">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
                <span class="d-none d-md-inline fw-medium" style="font-size:0.85rem;color:var(--color-text);">{{ Auth::user()->name ?? 'Admin' }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg py-2" style="border-radius:var(--radius-md);min-width:200px;">
                <li>
                    <a class="dropdown-item py-2 px-3 d-flex align-items-center gap-3" href="#" style="font-size:0.85rem;border-radius:var(--radius-sm);">
                        <i class="ti ti-user" style="font-size:1rem;color:var(--color-text-muted);"></i>
                        Profile
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item py-2 px-3 d-flex align-items-center gap-3" href="{{ route('logout') }}"
                       style="font-size:0.85rem;border-radius:var(--radius-sm);color:var(--color-danger);"
                       onclick="event.preventDefault();document.getElementById('logout-form-nav').submit();">
                        <i class="ti ti-logout" style="font-size:1rem;"></i>
                        Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</header>

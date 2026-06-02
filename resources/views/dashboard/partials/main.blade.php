<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Catat Meter Perumda Tirta Pase</title>
    @include('dashboard.partials.head')
    @stack('head')
</head>
<body>
    <div id="main-wrapper" class="d-flex">
        @include('dashboard.partials.sidebar')
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        <div class="content-wrapper d-flex flex-column min-vh-100">
            @include('dashboard.partials.navbar')
            <main class="flex-grow-1 px-4 px-lg-5 py-4" style="padding-top:1.5rem !important;">
                @yield('content')
            </main>
            <footer class="px-4 px-lg-5 py-3 text-center" style="font-size:0.75rem;color:var(--color-text-muted);border-top:1px solid var(--color-border-light);">
                &copy; {{ date('Y') }} Perumda Tirta Pase — Catat Meter
            </footer>
        </div>
    </div>

    @include('dashboard.partials.script')

    @stack('script')

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var sidebar = document.querySelector('.left-sidebar');
        var overlay = document.getElementById('sidebarOverlay');
        var toggler = document.querySelector('.sidebartoggler');
        var hamburger = document.querySelector('.sidebar-hamburger');
        var contentWrap = document.querySelector('.content-wrapper');
        var sidebarLogo = document.getElementById('sidebarLogo');
        var tooltipInstances = [];
        var isAnimating = false;

        var STORAGE_KEY = 'sidebar_state';
        var STATE_EXPANDED = 'expanded';
        var STATE_COLLAPSED = 'collapsed';

        // ── State helpers ──
        function getSavedState() {
            try {
                return localStorage.getItem(STORAGE_KEY) || STATE_EXPANDED;
            } catch (e) {
                return STATE_EXPANDED;
            }
        }

        function saveState(state) {
            try {
                localStorage.setItem(STORAGE_KEY, state);
            } catch (e) {}
        }

        function updateLogo(state) {
            if (!sidebarLogo) return;
            var base = '{{ asset("assets/images") }}';
            sidebarLogo.src = state === STATE_COLLAPSED
                ? base + '/logo_only.png'
                : base + '/logo_full.png';
        }

        // ── Tooltip management ──
        function initTooltips() {
            disposeTooltips();
            var links = document.querySelectorAll('.sidebar-link[data-bs-toggle="tooltip"]');
            links.forEach(function(el) {
                var tip = new bootstrap.Tooltip(el, {
                    customClass: 'sidebar-tooltip',
                    container: 'body'
                });
                tooltipInstances.push(tip);
            });
        }

        function disposeTooltips() {
            tooltipInstances.forEach(function(tip) {
                tip.dispose();
            });
            tooltipInstances = [];
        }

        // ── Hamburger icon animation ──
        function animateHamburger(toClose) {
            if (!hamburger) return;
            return new Promise(function(resolve) {
                hamburger.classList.add('hamburger-hidden');
                setTimeout(function() {
                    if (toClose) {
                        hamburger.classList.remove('ti-menu-2');
                        hamburger.classList.add('ti-x');
                    } else {
                        hamburger.classList.remove('ti-x');
                        hamburger.classList.add('ti-menu-2');
                    }
                    hamburger.classList.remove('hamburger-hidden');
                    resolve();
                }, 150);
            });
        }

        // ── Set sidebar state (without animation override) ──
        function setSidebarState(state, animate) {
            if (state === STATE_COLLAPSED) {
                sidebar.classList.add('collapsed');
                if (contentWrap) contentWrap.classList.add('expanded');
                if (window.innerWidth >= 1200) {
                    initTooltips();
                }
            } else {
                sidebar.classList.remove('collapsed');
                if (contentWrap) contentWrap.classList.remove('expanded');
                disposeTooltips();
            }
            updateLogo(state);
            saveState(state);
        }

        // ── Toggle sidebar ──
        function toggleSidebar() {
            if (isAnimating) return;
            isAnimating = true;

            var isCurrentlyCollapsed = sidebar.classList.contains('collapsed');
            var willCollapse = !isCurrentlyCollapsed;
            var isMobile = window.innerWidth < 1200;

            if (isMobile) {
                // Mobile: drawer toggle
                sidebar.classList.toggle('show');
                if (overlay) overlay.classList.toggle('show');
                // On mobile, hamburger always shows menu-2 (drawer), never close
                isAnimating = false;
                return;
            }

            // Desktop: collapse / expand
            animateHamburger(willCollapse).then(function() {
                setSidebarState(willCollapse ? STATE_COLLAPSED : STATE_EXPANDED, true);
                isAnimating = false;
            });
        }

        // ── Restore saved state on load ──
        function restoreState() {
            var saved = getSavedState();
            var isMobile = window.innerWidth < 1200;

            if (isMobile) {
                // On mobile, start with sidebar hidden
                sidebar.classList.remove('collapsed');
                sidebar.classList.remove('show');
                if (contentWrap) contentWrap.classList.remove('expanded');
                if (hamburger) {
                    hamburger.classList.remove('ti-x');
                    hamburger.classList.add('ti-menu-2');
                }
                updateLogo(STATE_EXPANDED);
                return;
            }

            if (saved === STATE_COLLAPSED) {
                sidebar.classList.add('collapsed');
                if (contentWrap) contentWrap.classList.add('expanded');
                if (hamburger) {
                    hamburger.classList.remove('ti-menu-2');
                    hamburger.classList.add('ti-x');
                }
                updateLogo(STATE_COLLAPSED);
                // Init tooltips after a small delay for DOM readiness
                setTimeout(initTooltips, 100);
            } else {
                sidebar.classList.remove('collapsed');
                if (contentWrap) contentWrap.classList.remove('expanded');
                if (hamburger) {
                    hamburger.classList.remove('ti-x');
                    hamburger.classList.add('ti-menu-2');
                }
                updateLogo(STATE_EXPANDED);
            }
        }

        // ── Event listeners ──
        if (toggler) {
            toggler.addEventListener('click', function(e) {
                e.preventDefault();
                toggleSidebar();
            });
        }

        if (overlay) {
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            });
        }

        // ── Handle window resize ──
        var resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                var isMobile = window.innerWidth < 1200;
                if (isMobile) {
                    sidebar.classList.remove('collapsed');
                    if (contentWrap) contentWrap.classList.remove('expanded');
                    disposeTooltips();
                    if (hamburger) {
                        hamburger.classList.remove('ti-x');
                        hamburger.classList.add('ti-menu-2');
                    }
                    updateLogo(STATE_EXPANDED);
                } else {
                    // Re-apply saved state on resize to desktop
                    var saved = getSavedState();
                    if (saved === STATE_COLLAPSED) {
                        sidebar.classList.add('collapsed');
                        if (contentWrap) contentWrap.classList.add('expanded');
                        if (hamburger) {
                            hamburger.classList.remove('ti-menu-2');
                            hamburger.classList.add('ti-x');
                        }
                        updateLogo(STATE_COLLAPSED);
                        initTooltips();
                    } else {
                        sidebar.classList.remove('collapsed');
                        if (contentWrap) contentWrap.classList.remove('expanded');
                        if (hamburger) {
                            hamburger.classList.remove('ti-x');
                            hamburger.classList.add('ti-menu-2');
                        }
                        updateLogo(STATE_EXPANDED);
                        disposeTooltips();
                    }
                }
            }, 200);
        });

        // ── Initialize ──
        restoreState();
    });
    </script>
</body>
</html>

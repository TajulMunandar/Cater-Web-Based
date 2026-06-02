<link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logo_only.png') }}" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        --font: 'Inter', system-ui, -apple-system, sans-serif;
        --bg-body: #F0F4F8;
        --bg-card: #FFFFFF;
        --bg-sidebar: #FFFFFF;
        --bg-navbar: rgba(255,255,255,0.85);
        --color-primary: #2563EB;
        --color-primary-light: #EFF6FF;
        --color-primary-dark: #1D4ED8;
        --color-success: #059669;
        --color-success-light: #ECFDF5;
        --color-warning: #D97706;
        --color-warning-light: #FFFBEB;
        --color-danger: #DC2626;
        --color-danger-light: #FEF2F2;
        --color-info: #0EA5E9;
        --color-info-light: #F0F9FF;
        --color-text: #0F172A;
        --color-text-secondary: #64748B;
        --color-text-muted: #94A3B8;
        --color-border: #E2E8F0;
        --color-border-light: #F1F5F9;
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 16px;
        --radius-xl: 20px;
        --shadow-sm: 0 1px 2px rgba(0,0,0,0.04);
        --shadow-md: 0 4px 12px rgba(0,0,0,0.06);
        --shadow-lg: 0 8px 30px rgba(0,0,0,0.08);
        --transition: 200ms cubic-bezier(0.4, 0, 0.2, 1);
    }

    *, *::before, *::after { box-sizing: border-box; }

    html { scroll-behavior: smooth; }

    body {
        font-family: var(--font);
        background: var(--bg-body);
        background-image:
            radial-gradient(circle at 20% 50%, rgba(37,99,235,0.03) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(5,150,105,0.03) 0%, transparent 50%),
            radial-gradient(circle at 50% 80%, rgba(217,119,6,0.02) 0%, transparent 50%);
        color: var(--color-text);
        min-height: 100vh;
        line-height: 1.6;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    ::selection { background: var(--color-primary); color: #fff; }

    /* Scrollbar */
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 3px; }
    ::-webkit-scrollbar-thumb:hover { background: #94A3B8; }

    /* Cards */
    .card-modern {
        background: var(--bg-card);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        transition: all var(--transition);
    }
    .card-modern:hover {
        box-shadow: var(--shadow-md);
    }

    .card-accent-top { position: relative; overflow: hidden; }
    .card-accent-top::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        border-radius: var(--radius-lg) var(--radius-lg) 0 0;
    }
    .card-accent-top.acc-primary::before { background: linear-gradient(90deg, #2563EB, #60A5FA); }
    .card-accent-top.acc-success::before { background: linear-gradient(90deg, #059669, #34D399); }
    .card-accent-top.acc-danger::before { background: linear-gradient(90deg, #DC2626, #F87171); }
    .card-accent-top.acc-warning::before { background: linear-gradient(90deg, #D97706, #FBBF24); }
    .card-accent-top.acc-info::before { background: linear-gradient(90deg, #0EA5E9, #38BDF8); }

    /* Card hover lift */
    .card-lift { transition: all 300ms cubic-bezier(0.4, 0, 0.2, 1); }
    .card-lift:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }

    /* Buttons */
    .btn-modern {
        font-family: var(--font);
        font-weight: 500;
        padding: 0.5rem 1.25rem;
        border-radius: var(--radius-md);
        transition: all var(--transition);
        border: 1px solid transparent;
    }
    .btn-modern:active { transform: scale(0.97); }
    .btn-modern-primary {
        background: var(--color-primary);
        color: #fff;
    }
    .btn-modern-primary:hover {
        background: var(--color-primary-dark);
        box-shadow: 0 4px 14px rgba(37,99,235,0.3);
    }
    .btn-modern-outline {
        background: transparent;
        border-color: var(--color-border);
        color: var(--color-text-secondary);
    }
    .btn-modern-outline:hover {
        background: var(--color-border-light);
        border-color: var(--color-border);
        color: var(--color-text);
    }

    /* Badges */
    .badge-modern {
        font-family: var(--font);
        font-weight: 500;
        padding: 0.35em 0.75em;
        border-radius: 100px;
        font-size: 0.7rem;
        letter-spacing: 0.02em;
    }

    /* Tables */
    .table-modern {
        font-size: 0.85rem;
        margin: 0;
    }
    .table-modern thead th {
        background: #F8FAFC;
        border-bottom: 1px solid var(--color-border);
        color: var(--color-text-secondary);
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.75rem 1rem;
    }
    .table-modern tbody td {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid var(--color-border-light);
        vertical-align: middle;
    }
    .table-modern tbody tr:hover { background: #FAFBFC; }
    .table-modern tbody tr:last-child td { border-bottom: none; }

    /* KPI value */
    .kpi-value {
        font-size: 1.75rem;
        font-weight: 700;
        letter-spacing: -0.03em;
        line-height: 1.2;
        color: var(--color-text);
    }

    /* Icon circles */
    .icon-circle {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border-radius: var(--radius-md);
        flex-shrink: 0;
    }

    /* Form controls */
    .form-control-modern {
        font-family: var(--font);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-md);
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        transition: all var(--transition);
        background: #fff;
    }
    .form-control-modern:focus {
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        outline: none;
    }

    /* Alerts / notifications */
    .alert-modern {
        border: 1px solid;
        border-radius: var(--radius-md);
        padding: 0.75rem 1rem;
    }

    /* Empty states */
    .empty-state-icon { font-size: 2.5rem; color: var(--color-text-muted); }
    .empty-state-text { color: var(--color-text-secondary); font-size: 0.9rem; }

    /* Progress bars */
    .progress-modern {
        height: 6px;
        background: #F1F5F9;
        border-radius: 100px;
        overflow: hidden;
    }
    .progress-modern .progress-bar {
        border-radius: 100px;
        transition: width 800ms cubic-bezier(0.4, 0, 0.2, 1);
    }
    .progress-modern .progress-bar-graded {
        height: 100%;
        border-radius: 100px;
        transition: width 800ms cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Animations */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(16px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.6; } }
    @keyframes countUp { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes slideInRight { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }
    @keyframes shimmer { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }
    @keyframes scaleIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }

    .animate-fade-in-up { animation: fadeInUp 500ms ease both; }
    .animate-fade-in { animation: fadeIn 500ms ease both; }
    .animate-scale-in { animation: scaleIn 400ms ease both; }
    .animate-slide-right { animation: slideInRight 500ms ease both; }

    .stagger-1 { animation-delay: 0ms; }
    .stagger-2 { animation-delay: 100ms; }
    .stagger-3 { animation-delay: 200ms; }
    .stagger-4 { animation-delay: 300ms; }
    .stagger-5 { animation-delay: 400ms; }
    .stagger-6 { animation-delay: 500ms; }
    .stagger-7 { animation-delay: 600ms; }
    .stagger-8 { animation-delay: 700ms; }

    /* Skeleton loading */
    .skeleton {
        background: linear-gradient(90deg, #F1F5F9 25%, #E2E8F0 50%, #F1F5F9 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
        border-radius: var(--radius-sm);
    }

    /* Main layout transitions */
    #main-wrapper { transition: all var(--transition); }
    .content-wrapper { transition: all var(--transition); }

    /* ── Sidebar ── */
    .left-sidebar {
        width: 260px;
        transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }

    /* Collapsed sidebar base */
    .left-sidebar.collapsed {
        width: 72px;
    }

    .left-sidebar .sidebar-link { transition: all var(--transition); }

    /* ── Content wrapper ── */
    .content-wrapper {
        margin-left: 260px;
        width: calc(100% - 260px);
        transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1), width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .content-wrapper.expanded {
        margin-left: 72px;
        width: calc(100% - 72px);
    }

    /* ── Collapsed sidebar inner elements ── */
    /* Hide brand text */
    .left-sidebar.collapsed .brand-text {
        opacity: 0;
        width: 0;
        overflow: hidden;
        transition: opacity 0.2s ease, width 0.3s ease;
    }

    /* Hide section labels */
    .left-sidebar.collapsed .sidebar-label {
        opacity: 0;
        height: 0;
        overflow: hidden;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        margin-top: 0 !important;
        margin-bottom: 0;
        transition: opacity 0.2s ease, height 0.3s ease;
    }

    /* Hide link text */
    .left-sidebar.collapsed .sidebar-link .link-text {
        opacity: 0;
        width: 0;
        overflow: hidden;
        transition: opacity 0.2s ease, width 0.3s ease;
    }

    /* Center icons when collapsed */
    .left-sidebar.collapsed .sidebar-link {
        justify-content: center !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        gap: 0 !important;
    }

    /* Header centering when collapsed */
    .left-sidebar.collapsed .sidebar-header {
        justify-content: center !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        gap: 0 !important;
    }

    /* Logo image shrink when collapsed */
    .left-sidebar.collapsed .sidebar-header img {
        height: 28px !important;
    }

    /* Hide logout text when collapsed */
    .left-sidebar.collapsed .px-3.py-3 .sidebar-link .link-text {
        opacity: 0;
        width: 0;
        overflow: hidden;
    }

    .left-sidebar.collapsed .px-3.py-3 .sidebar-link {
        justify-content: center !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        gap: 0 !important;
    }

    /* Nav padding reduction when collapsed */
    .left-sidebar.collapsed nav {
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
    }

    /* ── Hamburger animation ── */
    .sidebar-hamburger {
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.2s ease !important;
    }
    .sidebar-hamburger.hamburger-hidden {
        transform: rotate(90deg) scale(0.6);
        opacity: 0;
    }

    /* ── Tooltip customization ── */
    .sidebar-tooltip .tooltip-inner {
        background: #1E293B;
        color: #fff;
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.4rem 0.75rem;
        border-radius: 6px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        font-family: var(--font);
    }
    .sidebar-tooltip.bs-tooltip-end .tooltip-arrow::before {
        border-right-color: #1E293B;
    }

    /* ── Sidebar link hover refinement ── */
    .sidebar-link {
        position: relative;
        overflow: hidden;
    }
    .sidebar-link.active {
        background: var(--color-primary-light);
        color: var(--color-primary) !important;
        font-weight: 600;
    }

    /* Responsive */
    @media (max-width: 1199.98px) {
        .left-sidebar {
            transform: translateX(-100%);
            position: fixed;
            z-index: 1050;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), width 0.3s ease;
        }
        .left-sidebar.show {
            transform: translateX(0);
        }
        .left-sidebar.collapsed {
            width: 72px;
            transform: translateX(-100%);
        }
        .left-sidebar.collapsed.show {
            transform: translateX(0);
        }
        .content-wrapper,
        .content-wrapper.expanded {
            margin-left: 0;
            width: 100%;
        }
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.5);
            z-index: 1049;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .sidebar-overlay.show {
            display: block;
            opacity: 1;
        }
    }

    /* Navbar styles */
    .navbar-modern {
        background: var(--bg-navbar);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border-bottom: 1px solid var(--color-border);
    }

    /* Small helpers */
    .text-gradient-primary {
        background: linear-gradient(135deg, #2563EB, #60A5FA);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    @media (max-width: 576px) {
        .kpi-value { font-size: 1.35rem; }
        .card-modern { border-radius: var(--radius-md); }
    }
</style>

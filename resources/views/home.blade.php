<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memuat — Catat Meter Perumda Tirta Pase</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logo_only.png') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.47.0/dist/tabler-icons.min.css">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            overflow: hidden;
            height: 100vh;
            background: #F0F6FF;
            user-select: none;
        }

        /* ── SPLASH CONTAINER ── */
        #splash {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(165deg, #EEF5FF 0%, #FFFFFF 45%, #F4F9FF 100%);
            transition: opacity 0.8s ease, transform 1.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        #splash.fade-out {
            opacity: 0;
            transform: scale(1.05);
        }

        /* ── RIPPLED WATER TEXTURE ── */
        .ripple-bg {
            position: absolute;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
        }
        .ripple-bg::before {
            content: '';
            position: absolute;
            inset: -40% -40%;
            background:
                repeating-radial-gradient(
                    circle at 30% 50%,
                    rgba(37,99,235,0.03) 0px,
                    rgba(37,99,235,0.03) 2px,
                    transparent 2px,
                    transparent 20px
                ),
                repeating-radial-gradient(
                    circle at 70% 60%,
                    rgba(14,165,233,0.025) 0px,
                    rgba(14,165,233,0.025) 1px,
                    transparent 1px,
                    transparent 30px
                ),
                repeating-radial-gradient(
                    circle at 50% 40%,
                    rgba(37,99,235,0.015) 0px,
                    transparent 0px,
                    transparent 60px
                );
            animation: rippleDrift 18s linear infinite;
        }

        @keyframes rippleDrift {
            0%   { transform: translate(0, 0) rotate(0deg); }
            25%  { transform: translate(2%, 1%) rotate(0.5deg); }
            50%  { transform: translate(-1%, 2%) rotate(-0.3deg); }
            75%  { transform: translate(1%, -1%) rotate(0.4deg); }
            100% { transform: translate(0, 0) rotate(0deg); }
        }

        /* ── PERIMETER LIGHT ── */
        .perimeter-glow {
            position: absolute;
            inset: 0;
            pointer-events: none;
            background: radial-gradient(
                ellipse 70% 60% at 50% 50%,
                transparent 45%,
                rgba(37,99,235,0.04) 70%,
                rgba(14,165,233,0.06) 85%,
                transparent 100%
            );
        }

        /* ── CONTENT WRAPPER ── */
        .splash-content {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 2rem;
            max-width: 520px;
            width: 100%;
            transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }
        #splash.fade-out .splash-content {
            transform: translateY(-30px) scale(0.96);
        }

        /* ── WATER ENGINE AURA ── */
        .water-engine {
            position: relative;
            width: 140px;
            height: 140px;
            margin-bottom: 2rem;
            animation: engineEntrance 1.4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
            opacity: 0;
            transform: scale(0.7);
        }

        @keyframes engineEntrance {
            0%   { opacity: 0; transform: scale(0.7); }
            50%  { opacity: 1; transform: scale(1.08); }
            80%  { transform: scale(0.96); }
            100% { opacity: 1; transform: scale(1); }
        }

        /* Outer glow aura */
        .water-engine::before {
            content: '';
            position: absolute;
            inset: -18px;
            border-radius: 50%;
            background: conic-gradient(
                from 0deg,
                rgba(37,99,235,0.08),
                rgba(16,185,129,0.06),
                rgba(245,158,11,0.05),
                rgba(14,165,233,0.10),
                rgba(37,99,235,0.12),
                rgba(16,185,129,0.06),
                rgba(245,158,11,0.04),
                rgba(37,99,235,0.08)
            );
            filter: blur(6px);
            animation: auraSpin 8s linear infinite, auraPulse 3s ease-in-out infinite;
        }

        .water-engine::after {
            content: '';
            position: absolute;
            inset: -40px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(37,99,235,0.03) 30%, transparent 70%);
            animation: auraPulse 4s ease-in-out infinite;
        }

        @keyframes auraSpin {
            0%   { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes auraPulse {
            0%, 100% { opacity: 0.5; transform: scale(1); }
            50%      { opacity: 1; transform: scale(1.06); }
        }

        /* SVG circular progress + water wave integrated */
        .engine-ring {
            position: relative;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: rgba(255,255,255,0.80);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow:
                0 8px 48px rgba(15,79,168,0.10),
                0 0 0 1px rgba(255,255,255,0.5),
                inset 0 0 0 1px rgba(255,255,255,0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .engine-ring svg {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            transform: rotate(-90deg);
            z-index: 2;
        }

        .engine-ring .bg-ring {
            fill: none;
            stroke: #E8EDF5;
            stroke-width: 4;
        }

        .engine-ring .fg-ring {
            fill: none;
            stroke: url(#waterGradient);
            stroke-width: 4;
            stroke-linecap: round;
            stroke-dasharray: 396;
            stroke-dashoffset: 396;
            transition: stroke-dashoffset 0.1s linear;
            filter: drop-shadow(0 0 4px rgba(14,165,233,0.15));
        }

        /* Water wave fill inside ring */
        .engine-fill {
            position: absolute;
            inset: 6px;
            border-radius: 50%;
            overflow: hidden;
            background: transparent;
            z-index: 0;
        }

        .engine-wave {
            position: absolute;
            bottom: 0;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                180deg,
                rgba(14,165,233,0.08) 0%,
                rgba(37,99,235,0.04) 40%,
                rgba(16,185,129,0.02) 70%,
                rgba(245,158,11,0.01) 100%
            );
            border-radius: 30% 70% 40% 60% / 50% 40% 60% 50%;
            animation: waveMorph 5s ease-in-out infinite;
            transform: translateY(100%);
            transition: transform 0.15s linear;
        }

        @keyframes waveMorph {
            0%, 100% { border-radius: 30% 70% 40% 60% / 50% 40% 60% 50%; }
            33%      { border-radius: 45% 55% 55% 45% / 55% 50% 50% 45%; }
            66%      { border-radius: 55% 45% 30% 70% / 45% 55% 45% 55%; }
        }

        /* Engine inner logo */
        .engine-logo {
            position: relative;
            z-index: 3;
            height: 56px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 2px 12px rgba(15,79,168,0.12));
        }

        /* Progress percentage text (inside ring, overlaid on wave) */
        .engine-pct {
            position: absolute;
            z-index: 4;
            bottom: -30px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 0.7rem;
            font-weight: 600;
            color: #3B82F6;
            letter-spacing: 0.06em;
            opacity: 0;
            transition: opacity 0.5s ease;
        }
        .engine-pct.show {
            opacity: 1;
        }

        /* ── TEXT SECTION ── */
        .text-section {
            opacity: 0;
            animation: fadeUp 0.8s ease 0.8s forwards;
        }

        @keyframes fadeUp {
            to { opacity: 1; transform: translateY(0); }
        }

        .welcome-title {
            font-size: 2.6rem;
            font-weight: 800;
            color: #0F172A;
            letter-spacing: -0.03em;
            line-height: 1.15;
            margin-bottom: 0.35rem;
        }

        .welcome-sub {
            font-size: 1.05rem;
            font-weight: 500;
            color: #2563EB;
            letter-spacing: 0.01em;
            margin-bottom: 0.3rem;
        }

        .welcome-desc {
            font-size: 0.85rem;
            color: #94A3B8;
            font-weight: 400;
            letter-spacing: 0.02em;
        }

        /* ── STATS ROW (natural inline elements, no cards) ── */
        .stats-row {
            display: flex;
            gap: 2.5rem;
            margin-top: 2rem;
            align-items: center;
            justify-content: center;
            opacity: 0;
            animation: fadeUp 0.6s ease 1.6s forwards;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            opacity: 0;
            transform: translateY(12px);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }
        .stat-item.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .stat-item .si-icon {
            font-size: 1.4rem;
            color: #3B82F6;
            opacity: 0.7;
            line-height: 1;
        }

        .stat-item .si-body {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .stat-item .si-value {
            font-size: 1.6rem;
            font-weight: 700;
            color: #0F172A;
            line-height: 1.1;
            letter-spacing: -0.02em;
        }

        .stat-item .si-label {
            font-size: 0.7rem;
            color: #94A3B8;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .stat-divider {
            width: 1px;
            height: 36px;
            background: #E2E8F0;
        }

        /* ── LOADING LABEL ── */
        .loading-label {
            margin-top: 1.5rem;
            font-size: 0.78rem;
            color: #94A3B8;
            font-weight: 500;
            letter-spacing: 0.04em;
            opacity: 0;
            animation: fadeUp 0.5s ease 2s forwards;
        }
        .loading-dots::after {
            content: '';
            animation: loadDots 1.5s steps(3, end) infinite;
        }
        @keyframes loadDots {
            0%   { content: ''; }
            33%  { content: '.'; }
            66%  { content: '..'; }
            100% { content: '...'; }
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 640px) {
            .water-engine { width: 110px; height: 110px; }
            .engine-logo { height: 44px; }
            .welcome-title { font-size: 1.9rem; }
            .welcome-sub { font-size: 0.9rem; }
            .welcome-desc { font-size: 0.78rem; }
            .stats-row { gap: 1.5rem; }
            .stat-item .si-value { font-size: 1.3rem; }
            .stat-item .si-icon { font-size: 1.1rem; }
            .engine-pct { font-size: 0.6rem; bottom: -26px; }
        }
    </style>
</head>
<body>
    <div id="splash">
        {{-- RIPPLED WATER TEXTURE --}}
        <div class="ripple-bg"></div>

        {{-- PERIMETER GLOW --}}
        <div class="perimeter-glow"></div>

        {{-- CONTENT --}}
        <div class="splash-content">
            {{-- WATER ENGINE AURA --}}
            <div class="water-engine">
                <div class="engine-ring">
                    <svg viewBox="0 0 100 100">
                        <defs>
                            <linearGradient id="waterGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#2563EB" />
                                <stop offset="40%" stop-color="#0EA5E9" />
                                <stop offset="70%" stop-color="#10B981" />
                                <stop offset="100%" stop-color="#F59E0B" />
                            </linearGradient>
                        </defs>
                        <circle class="bg-ring" cx="50" cy="50" r="45" />
                        <circle class="fg-ring" id="progressRing" cx="50" cy="50" r="45" />
                    </svg>
                    <div class="engine-fill">
                        <div class="engine-wave" id="engineWave"></div>
                    </div>
                    <img class="engine-logo" src="{{ asset('assets/images/logo_only.png') }}" alt="Tirta Pase">
                </div>
                <span class="engine-pct" id="enginePct">0%</span>
            </div>

            {{-- TEXT --}}
            <div class="text-section">
                <h1 class="welcome-title">Selamat Datang</h1>
                <p class="welcome-sub">Sistem Informasi Catat Meter Tirta Pase</p>
                <p class="welcome-desc">Sistem Manajemen Air Modern.</p>
            </div>

            {{-- STATS --}}
            <div class="stats-row" id="statsRow">
                <div class="stat-item" data-target="{{ $stats['total_pelanggan'] }}">
                    <span class="si-icon"><i class="ti ti-users"></i></span>
                    <div class="si-body">
                        <span class="si-value" id="stat1">0</span>
                        <span class="si-label">Total Pelanggan</span>
                    </div>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item" data-target="{{ $stats['catat_hari_ini'] }}">
                    <span class="si-icon"><i class="ti ti-clipboard-check"></i></span>
                    <div class="si-body">
                        <span class="si-value" id="stat2">0</span>
                        <span class="si-label">Catatan Hari Ini</span>
                    </div>
                </div>
            </div>

            {{-- LOADING --}}
            <p class="loading-label">Mempersiapkan Dashboard<span class="loading-dots"></span></p>
        </div>
    </div>

    <script>
        (function() {
            var splash = document.getElementById('splash');
            var progressRing = document.getElementById('progressRing');
            var engineWave = document.getElementById('engineWave');
            var enginePct = document.getElementById('enginePct');
            var statsRow = document.getElementById('statsRow');

            var circumference = 2 * Math.PI * 45;
            var progress = 0;
            var hasTransitioned = false;

            // ── Progress animation ──
            function updateProgress(value) {
                var offset = circumference - (value / 100) * circumference;
                progressRing.style.strokeDashoffset = offset;
                enginePct.textContent = Math.round(value) + '%';

                var waveTranslate = 100 - value;
                engineWave.style.transform = 'translateY(' + waveTranslate + '%)';
            }

            function animateProgress() {
                if (hasTransitioned) return;
                progress += 0.5;
                if (progress >= 100) {
                    progress = 100;
                    updateProgress(100);
                    enginePct.classList.add('show');
                    setTimeout(transitionToDashboard, 500);
                    return;
                }
                updateProgress(progress);
                requestAnimationFrame(animateProgress);
            }

            // ── Stats counter ──
            function animateStats() {
                var items = statsRow.querySelectorAll('.stat-item');
                items.forEach(function(item, idx) {
                    setTimeout(function() {
                        item.classList.add('visible');
                    }, idx * 200);
                });

                setTimeout(function() {
                    var statEls = [
                        document.getElementById('stat1'),
                        document.getElementById('stat2')
                    ];
                    var targets = [];
                    items.forEach(function(c) { targets.push(parseInt(c.getAttribute('data-target'))); });

                    statEls.forEach(function(el, i) {
                        var target = targets[i];
                        var current = 0;
                        var step = Math.max(1, target / 40);
                        var interval = setInterval(function() {
                            current += step;
                            if (current >= target) {
                                current = target;
                                clearInterval(interval);
                            }
                            el.textContent = Math.round(current).toLocaleString();
                        }, 30);
                    });
                }, 500);
            }

            // ── Transition ──
            function transitionToDashboard() {
                if (hasTransitioned) return;
                hasTransitioned = true;

                setTimeout(function() {
                    splash.classList.add('fade-out');
                }, 300);

                setTimeout(function() {
                    window.location.href = '/';
                }, 1600);
            }

            // ── Start ──
            setTimeout(function() {
                animateProgress();
                animateStats();
            }, 1400);

            // ── Fallback ──
            setTimeout(function() {
                if (!hasTransitioned) transitionToDashboard();
            }, 8000);
        })();
    </script>
</body>
</html>

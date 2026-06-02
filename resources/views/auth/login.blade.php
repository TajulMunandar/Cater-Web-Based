@extends('layouts.app')

@section('content')
<style>
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        overflow: hidden;
    }
    #app {
        height: 100%;
    }
    #app > nav.navbar {
        display: none !important;
    }
    #app > main {
        padding: 0 !important;
        height: 100%;
    }

    .split-login {
        display: flex;
        height: 100vh;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    /* ===== LEFT PANEL ===== */
    .split-left {
        flex: 0 0 55%;
        position: relative;
        background: #0F172A;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .split-left-bg {
        position: absolute;
        inset: 0;
        background:
            linear-gradient(135deg, rgba(37,99,235,0.35) 0%, rgba(5,150,105,0.25) 50%, rgba(15,23,42,0.85) 100%),
            url('https://images.unsplash.com/photo-1581092160562-40aa08e78837?w=1200&q=80') center/cover no-repeat;
    }
    .split-left-content {
        position: relative;
        z-index: 2;
        text-align: center;
        padding: 3rem;
        max-width: 480px;
    }
    .split-left-content .brand-icon {
        width: 72px;
        height: 72px;
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(8px);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2rem;
        color: #fff;
        border: 1px solid rgba(255,255,255,0.1);
    }
    .split-left-content h1 {
        font-size: 1.75rem;
        font-weight: 700;
        color: #fff;
        margin: 0 0 0.5rem;
        letter-spacing: -0.03em;
    }
    .split-left-content p {
        font-size: 0.9rem;
        color: rgba(255,255,255,0.6);
        margin: 0 0 2rem;
        line-height: 1.6;
    }
    .split-left-content .stat-badges {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }
    .split-left-content .stat-badge {
        background: rgba(255,255,255,0.08);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 12px;
        padding: 0.75rem 1.25rem;
        text-align: center;
        min-width: 100px;
    }
    .split-left-content .stat-badge .num {
        font-size: 1.25rem;
        font-weight: 700;
        color: #fff;
        display: block;
    }
    .split-left-content .stat-badge .lbl {
        font-size: 0.7rem;
        color: rgba(255,255,255,0.5);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-top: 2px;
        display: block;
    }

    /* ===== RIGHT PANEL ===== */
    .split-right {
        flex: 0 0 45%;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2.5rem;
    }
    .split-right-inner {
        width: 100%;
        max-width: 380px;
    }
    .split-right-inner .welcome {
        margin-bottom: 2rem;
    }
    .split-right-inner .welcome h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0F172A;
        margin: 0 0 0.35rem;
        letter-spacing: -0.03em;
    }
    .split-right-inner .welcome p {
        font-size: 0.9rem;
        color: #64748B;
        margin: 0;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }
    .form-group label {
        display: block;
        font-size: 0.8rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.4rem;
    }
    .form-group .input-wrap {
        position: relative;
    }
    .form-group .input-wrap .input-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94A3B8;
        font-size: 1rem;
        pointer-events: none;
    }
    .form-group .input-wrap input {
        width: 100%;
        height: 46px;
        padding: 0 14px 0 42px;
        font-size: 0.9rem;
        font-family: inherit;
        color: #0F172A;
        background: #F8FAFC;
        border: 1.5px solid #E2E8F0;
        border-radius: 12px;
        outline: none;
        transition: all 0.2s;
    }
    .form-group .input-wrap input:focus {
        border-color: #2563EB;
        box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        background: #fff;
    }
    .form-group .input-wrap input.is-invalid {
        border-color: #DC2626;
        box-shadow: 0 0 0 3px rgba(220,38,38,0.1);
    }
    .form-group .input-wrap input::placeholder {
        color: #94A3B8;
    }
    .form-group .field-error {
        display: block;
        font-size: 0.75rem;
        color: #DC2626;
        margin-top: 0.3rem;
    }

    .form-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.25rem;
    }
    .form-row .remember {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8rem;
        color: #64748B;
        cursor: pointer;
    }
    .form-row .remember input[type="checkbox"] {
        width: 16px;
        height: 16px;
        accent-color: #2563EB;
        border-radius: 4px;
        cursor: pointer;
    }
    .form-row .forgot {
        font-size: 0.8rem;
        color: #2563EB;
        text-decoration: none;
        font-weight: 500;
    }
    .form-row .forgot:hover {
        text-decoration: underline;
    }

    .login-submit {
        width: 100%;
        height: 46px;
        background: #0F172A;
        color: #fff;
        border: none;
        border-radius: 12px;
        font-size: 0.9rem;
        font-weight: 600;
        font-family: inherit;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    .login-submit:hover {
        background: #1E293B;
        box-shadow: 0 4px 14px rgba(15,23,42,0.25);
        transform: translateY(-1px);
    }
    .login-submit:active {
        transform: translateY(0);
    }

    .split-right-footer {
        text-align: center;
        margin-top: 2rem;
        font-size: 0.75rem;
        color: #94A3B8;
    }

    @media (max-width: 900px) {
        .split-login { flex-direction: column; }
        .split-left { flex: 0 0 260px; }
        .split-left-content { padding: 1.5rem; }
        .split-left-content .stat-badges { display: none; }
        .split-left-content h1 { font-size: 1.35rem; }
        .split-right { flex: 1; padding: 2rem 1.5rem; }
    }
</style>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<div class="split-login">
    {{-- LEFT PANEL --}}
    <div class="split-left">
        <div class="split-left-bg"></div>
        <div class="split-left-content">
            <div class="brand-icon">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a8 8 0 0 0-8 8c0 5 4 10 8 12 4-2 8-7 8-12a8 8 0 0 0-8-8z"/><path d="M12 10a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/></svg>
            </div>
            <h1>Perumda Tirta Pase</h1>
            <p>Sistem informasi pencatatan meter air untuk pelayanan yang lebih baik kepada masyarakat.</p>
            <div class="stat-badges">
                <div class="stat-badge">
                    <span class="num">2.500+</span>
                    <span class="lbl">Pelanggan</span>
                </div>
                <div class="stat-badge">
                    <span class="num">5</span>
                    <span class="lbl">Wilayah</span>
                </div>
                <div class="stat-badge">
                    <span class="num">98%</span>
                    <span class="lbl">Aman</span>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT PANEL --}}
    <div class="split-right">
        <div class="split-right-inner">
            <div class="welcome">
                <h2>Selamat Datang</h2>
                <p>Silakan masuk ke akun Anda untuk melanjutkan.</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                        </span>
                        <input id="email" type="email"
                            class="@error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}"
                            placeholder="nama@email.com"
                            required autocomplete="email" autofocus>
                    </div>
                    @error('email')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <div class="input-wrap">
                        <span class="input-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </span>
                        <input id="password" type="password"
                            class="@error('password') is-invalid @enderror"
                            name="password" placeholder="••••••••"
                            required autocomplete="current-password">
                    </div>
                    @error('password')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-row">
                    <label class="remember">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        Ingat saya
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot">Lupa kata sandi?</a>
                    @endif
                </div>

                <button type="submit" class="login-submit">
                    Masuk
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </button>
            </form>

            <div class="split-right-footer">
                &copy; {{ date('Y') }} Perumda Tirta Pase
            </div>
        </div>
    </div>
</div>
@endsection

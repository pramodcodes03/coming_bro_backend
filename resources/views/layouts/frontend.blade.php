<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ComingBro — Move through the city, effortlessly')</title>
    <meta name="description" content="@yield('meta_description', 'ComingBro is India\'s premium ride-hailing platform. Book city rides, intercity travel and freight in seconds — safe, fast and fairly priced.')">
    <link rel="icon" href="/assets/images/logo.png">

    {{-- Social / Open Graph --}}
    <meta property="og:title" content="@yield('title', 'ComingBro — Move through the city, effortlessly')">
    <meta property="og:description" content="@yield('meta_description', 'India\'s premium ride-hailing platform.')">
    <meta property="og:type" content="website">
    <meta property="og:image" content="/assets/images/logo.png">
    <meta name="theme-color" content="#05080f">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    {{-- Space Grotesk for display headings, Inter for body — a clean 2026 pairing --}}
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* ==========================================================
           DESIGN TOKENS  —  premium dark + neon (default)
           ========================================================== */
        :root {
            --primary: #18b9e8;
            --primary-rgb: 24,185,232;
            --secondary: #5b8cff;
            --secondary-rgb: 91,140,255;
            --accent: #19e0c8;

            --bg: #05080f;
            --bg-elev: #0a0f1c;
            --bg-card: rgba(255,255,255,0.025);
            --bg-card-hover: rgba(255,255,255,0.045);
            --text: #f4f7fb;
            --text-soft: #aeb9cc;
            --text-dim: #6b7689;

            --border: rgba(255,255,255,0.08);
            --border-strong: rgba(255,255,255,0.14);

            --glass: rgba(10,15,28,0.6);
            --nav-bg: rgba(5,8,15,0.55);

            --grad: linear-gradient(120deg, var(--primary), var(--secondary));
            --grad-accent: linear-gradient(120deg, var(--accent), var(--primary));

            --glow-1: rgba(24,185,232,0.22);
            --glow-2: rgba(91,140,255,0.18);
            --glow-3: rgba(25,224,200,0.14);

            --ring: rgba(var(--primary-rgb),0.35);
            --shadow-card: 0 1px 0 rgba(255,255,255,0.04) inset, 0 20px 50px -25px rgba(0,0,0,0.7);
            --radius: 20px;
            --radius-sm: 14px;
            --maxw: 1200px;
            --ease: cubic-bezier(0.22,1,0.36,1);
        }

        /* ---- Light theme ---- */
        [data-theme="light"] {
            --bg: #f6f9fc;
            --bg-elev: #ffffff;
            --bg-card: rgba(10,20,40,0.025);
            --bg-card-hover: rgba(10,20,40,0.05);
            --text: #0a1424;
            --text-soft: #475467;
            --text-dim: #98a2b3;
            --border: rgba(10,20,40,0.08);
            --border-strong: rgba(10,20,40,0.14);
            --glass: rgba(255,255,255,0.7);
            --nav-bg: rgba(246,249,252,0.7);
            --glow-1: rgba(24,185,232,0.16);
            --glow-2: rgba(91,140,255,0.12);
            --glow-3: rgba(25,224,200,0.10);
            --shadow-card: 0 1px 0 rgba(255,255,255,0.6) inset, 0 18px 40px -24px rgba(16,40,80,0.28);
        }

        /* ==========================================================
           RESET & BASE
           ========================================================== */
        *,*::before,*::after { margin:0; padding:0; box-sizing:border-box; }
        html { scroll-behavior:smooth; -webkit-text-size-adjust:100%; }
        body {
            font-family:'Inter',-apple-system,BlinkMacSystemFont,sans-serif;
            background:var(--bg);
            color:var(--text);
            line-height:1.65;
            overflow-x:hidden;
            -webkit-font-smoothing:antialiased;
            -moz-osx-font-smoothing:grayscale;
            transition:background-color .5s var(--ease), color .5s var(--ease);
        }
        a { color:inherit; text-decoration:none; transition:color .25s var(--ease); }
        img,svg { display:block; max-width:100%; }
        ul { list-style:none; }
        button { font-family:inherit; border:none; background:none; cursor:pointer; color:inherit; }
        ::selection { background:rgba(var(--primary-rgb),0.3); color:var(--text); }

        h1,h2,h3,h4 { font-family:'Space Grotesk',sans-serif; font-weight:700; line-height:1.08; letter-spacing:-0.02em; }

        /* Ambient page glow */
        body::before {
            content:''; position:fixed; inset:0; z-index:-1; pointer-events:none;
            background:
                radial-gradient(60% 50% at 75% -5%, var(--glow-2), transparent 60%),
                radial-gradient(50% 45% at 10% 5%, var(--glow-1), transparent 55%),
                radial-gradient(45% 40% at 50% 110%, var(--glow-3), transparent 60%);
        }

        /* ==========================================================
           LAYOUT HELPERS
           ========================================================== */
        .container { width:100%; max-width:var(--maxw); margin:0 auto; padding:0 24px; }
        .eyebrow {
            display:inline-flex; align-items:center; gap:8px;
            font-family:'Space Grotesk',sans-serif; font-size:.72rem; font-weight:600;
            letter-spacing:.16em; text-transform:uppercase; color:var(--primary);
            padding:7px 14px; border:1px solid var(--border-strong); border-radius:999px;
            background:var(--bg-card); backdrop-filter:blur(8px);
        }
        .eyebrow svg { width:14px; height:14px; }
        .grad-text {
            background:var(--grad); -webkit-background-clip:text; background-clip:text;
            -webkit-text-fill-color:transparent; color:transparent;
        }
        .section { padding:120px 0; position:relative; }
        .section-head { max-width:680px; margin:0 auto 64px; text-align:center; }
        .section-head h2 { font-size:clamp(2rem,4.5vw,3.1rem); margin:18px 0 16px; }
        .section-head p { color:var(--text-soft); font-size:1.05rem; }

        /* ==========================================================
           BUTTONS
           ========================================================== */
        .btn {
            display:inline-flex; align-items:center; justify-content:center; gap:9px;
            font-weight:600; font-size:.95rem; padding:14px 26px; border-radius:14px;
            transition:transform .3s var(--ease), box-shadow .3s var(--ease), background .3s var(--ease), border-color .3s var(--ease);
            white-space:nowrap;
        }
        .btn svg { width:18px; height:18px; }
        .btn-primary {
            background:var(--grad); color:#fff;
            box-shadow:0 10px 30px -8px rgba(var(--primary-rgb),0.55);
        }
        .btn-primary:hover { transform:translateY(-3px); box-shadow:0 18px 44px -10px rgba(var(--primary-rgb),0.7); color:#fff; }
        .btn-ghost {
            background:var(--bg-card); color:var(--text); border:1px solid var(--border-strong);
            backdrop-filter:blur(8px);
        }
        .btn-ghost:hover { transform:translateY(-3px); background:var(--bg-card-hover); border-color:var(--ring); }

        /* App-store buttons */
        .store-row { display:flex; flex-wrap:wrap; gap:14px; }
        .btn-store {
            display:inline-flex; align-items:center; gap:12px; padding:12px 22px;
            border-radius:15px; border:1px solid var(--border-strong); background:var(--bg-card);
            backdrop-filter:blur(10px); transition:transform .3s var(--ease), border-color .3s var(--ease), background .3s var(--ease);
        }
        .btn-store:hover { transform:translateY(-3px); border-color:var(--ring); background:var(--bg-card-hover); }
        .btn-store svg { width:24px; height:24px; fill:var(--text); }
        .btn-store .store-text { display:flex; flex-direction:column; line-height:1.15; text-align:left; }
        .btn-store .store-text small { font-size:.66rem; color:var(--text-dim); letter-spacing:.04em; }
        .btn-store .store-text strong { font-size:1rem; font-family:'Space Grotesk',sans-serif; }

        /* ==========================================================
           CARD
           ========================================================== */
        .card {
            background:var(--bg-card); border:1px solid var(--border); border-radius:var(--radius);
            box-shadow:var(--shadow-card); backdrop-filter:blur(12px);
            transition:transform .4s var(--ease), border-color .4s var(--ease), background .4s var(--ease);
        }
        .card:hover { transform:translateY(-6px); border-color:var(--ring); background:var(--bg-card-hover); }

        /* ==========================================================
           NAVBAR
           ========================================================== */
        .navbar {
            position:fixed; top:0; left:0; width:100%; z-index:1000; padding:18px 0;
            background:transparent; transition:padding .35s var(--ease), background .4s var(--ease), border-color .4s var(--ease), box-shadow .4s var(--ease);
            border-bottom:1px solid transparent;
        }
        .navbar.scrolled {
            padding:11px 0; background:var(--nav-bg);
            backdrop-filter:blur(22px) saturate(1.8); -webkit-backdrop-filter:blur(22px) saturate(1.8);
            border-bottom:1px solid var(--border);
        }
        .navbar .container { display:flex; align-items:center; justify-content:space-between; }
        .nav-logo { display:flex; align-items:center; gap:10px; font-family:'Space Grotesk',sans-serif; font-weight:700; font-size:1.22rem; }
        .nav-logo img { height:34px; width:auto; }
        .nav-links { display:flex; align-items:center; gap:6px; }
        .nav-links a {
            font-size:.9rem; font-weight:500; color:var(--text-soft);
            padding:8px 14px; border-radius:10px; transition:color .25s var(--ease), background .25s var(--ease);
        }
        .nav-links a:hover { color:var(--text); background:var(--bg-card); }
        .nav-links a.active { color:var(--text); background:var(--bg-card); }
        .nav-actions { display:flex; align-items:center; gap:12px; }
        .theme-toggle {
            width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center;
            background:var(--bg-card); border:1px solid var(--border); color:var(--text);
            transition:all .3s var(--ease);
        }
        .theme-toggle:hover { border-color:var(--ring); background:var(--bg-card-hover); transform:rotate(18deg); }
        .theme-toggle svg { width:19px; height:19px; }
        [data-theme="light"] .theme-toggle .icon-moon { display:none; }
        [data-theme="dark"]  .theme-toggle .icon-sun { display:none; }

        /* Hamburger */
        .hamburger { display:none; flex-direction:column; gap:5px; padding:8px; z-index:1001; }
        .hamburger span { width:24px; height:2px; background:var(--text); border-radius:3px; transition:all .3s var(--ease); display:block; }
        .hamburger.active span:nth-child(1) { transform:translateY(7px) rotate(45deg); }
        .hamburger.active span:nth-child(2) { opacity:0; }
        .hamburger.active span:nth-child(3) { transform:translateY(-7px) rotate(-45deg); }

        .mobile-menu {
            display:none; position:fixed; inset:0; z-index:999; flex-direction:column;
            align-items:center; justify-content:center; gap:18px;
            background:var(--glass); backdrop-filter:blur(28px); -webkit-backdrop-filter:blur(28px);
            opacity:0; pointer-events:none; transition:opacity .4s var(--ease);
        }
        .mobile-menu.open { opacity:1; pointer-events:all; }
        .mobile-menu a { font-family:'Space Grotesk',sans-serif; font-size:1.5rem; font-weight:600; color:var(--text); }
        .mobile-menu a:hover { color:var(--primary); }
        .mobile-menu .btn { margin-top:14px; font-size:1.05rem; }

        /* ==========================================================
           PAGE CONTENT
           ========================================================== */
        .page-content { min-height:60vh; }
        /* Inner pages (about/contact/policy) start below the fixed nav */
        .page-pad { padding-top:140px; }

        /* ==========================================================
           SCROLL REVEAL
           ========================================================== */
        [data-reveal] { opacity:0; transform:translateY(28px); transition:opacity .8s var(--ease), transform .8s var(--ease); }
        [data-reveal].in { opacity:1; transform:none; }
        [data-reveal="left"]  { transform:translateX(-32px); }
        [data-reveal="right"] { transform:translateX(32px); }
        [data-reveal="left"].in, [data-reveal="right"].in { transform:none; }
        @media (prefers-reduced-motion:reduce) {
            [data-reveal] { opacity:1 !important; transform:none !important; transition:none; }
            html { scroll-behavior:auto; }
        }

        /* ==========================================================
           FOOTER
           ========================================================== */
        .footer { position:relative; border-top:1px solid var(--border); margin-top:40px; padding:80px 0 0; background:var(--bg-elev); }
        .footer-grid { display:grid; grid-template-columns:1.6fr 1fr 1fr 1.4fr; gap:48px; padding-bottom:48px; border-bottom:1px solid var(--border); }
        .footer-brand .nav-logo { margin-bottom:18px; }
        .footer-brand p { color:var(--text-soft); font-size:.92rem; max-width:300px; }
        .footer-socials { display:flex; gap:10px; margin-top:22px; }
        .footer-social {
            width:40px; height:40px; border-radius:12px; display:flex; align-items:center; justify-content:center;
            background:var(--bg-card); border:1px solid var(--border); transition:all .3s var(--ease);
        }
        .footer-social:hover { background:var(--grad); border-color:transparent; transform:translateY(-3px); }
        .footer-social svg { width:17px; height:17px; fill:var(--text-soft); transition:fill .3s var(--ease); }
        .footer-social:hover svg { fill:#fff; }
        .footer h4 { font-family:'Space Grotesk',sans-serif; font-size:.78rem; font-weight:600; letter-spacing:.12em; text-transform:uppercase; color:var(--text-dim); margin-bottom:18px; }
        .footer-links { display:flex; flex-direction:column; gap:11px; }
        .footer-links a { font-size:.92rem; color:var(--text-soft); width:fit-content; }
        .footer-links a:hover { color:var(--primary); }
        .footer-contact { display:flex; align-items:flex-start; gap:12px; margin-bottom:14px; font-size:.92rem; color:var(--text-soft); }
        .footer-contact svg { width:18px; height:18px; fill:var(--primary); margin-top:3px; flex-shrink:0; }
        .footer-contact a:hover { color:var(--primary); }
        .footer-bottom { padding:22px 0; display:flex; align-items:center; justify-content:space-between; font-size:.84rem; color:var(--text-dim); }
        .footer-bottom a:hover { color:var(--primary); }

        /* ==========================================================
           RESPONSIVE
           ========================================================== */
        @media (max-width:1024px){ .footer-grid{ grid-template-columns:repeat(2,1fr); gap:36px; } }
        @media (max-width:860px){
            .nav-links,.nav-actions .btn { display:none; }
            .hamburger { display:flex; }
            .mobile-menu { display:flex; }
            .section { padding:88px 0; }
        }
        @media (max-width:560px){
            .container { padding:0 18px; }
            .footer-grid { grid-template-columns:1fr; }
            .footer-bottom { flex-direction:column; gap:10px; text-align:center; }
        }

        @yield('styles')
    </style>
</head>
<body>
    {{-- ============================== NAVBAR ============================== --}}
    <nav class="navbar" id="navbar">
        <div class="container">
            <a href="/" class="nav-logo">
                <img src="/assets/images/logo.png" alt="ComingBro">
                <span>ComingBro</span>
            </a>

            <div class="nav-links">
                <a href="/" {{ request()->is('/') ? 'class=active' : '' }}>Home</a>
                <a href="/about" {{ request()->is('about') ? 'class=active' : '' }}>About</a>
                <a href="/#services">Services</a>
                <a href="/#how">How it works</a>
                <a href="/contact" {{ request()->is('contact') ? 'class=active' : '' }}>Contact</a>
            </div>

            <div class="nav-actions">
                <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">
                    <span class="icon-sun"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg></span>
                    <span class="icon-moon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg></span>
                </button>
                <a href="/#download" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Get the app
                </a>
            </div>

            <button class="hamburger" id="hamburger" aria-label="Menu"><span></span><span></span><span></span></button>
        </div>
    </nav>

    <div class="mobile-menu" id="mobileMenu">
        <a href="/" class="mobile-link">Home</a>
        <a href="/about" class="mobile-link">About</a>
        <a href="/#services" class="mobile-link">Services</a>
        <a href="/#how" class="mobile-link">How it works</a>
        <a href="/contact" class="mobile-link">Contact</a>
        <a href="/#download" class="btn btn-primary mobile-link">Get the app</a>
    </div>

    {{-- ============================== CONTENT ============================== --}}
    <main class="page-content">
        @yield('content')
    </main>

    {{-- ============================== FOOTER ============================== --}}
    @php
        $cb = $contact ?? [];
        $cbPhone = $cb['phone'] ?? '+919028777184';
        $cbEmail = $cb['email'] ?? 'support@comingbro.in';
        $cbAddress = (!empty($cb['address']) && strtolower($cb['address']) !== 'your address') ? $cb['address'] : null;
    @endphp
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <a href="/" class="nav-logo"><img src="/assets/images/logo.png" alt="ComingBro"><span>ComingBro</span></a>
                    <p>India's premium ride-hailing platform. City rides, intercity journeys and freight — booked in seconds, priced fairly, driven safely.</p>
                    <div class="footer-socials">
                        <a href="#" class="footer-social" aria-label="Facebook"><svg viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
                        <a href="#" class="footer-social" aria-label="X"><svg viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a>
                        <a href="#" class="footer-social" aria-label="Instagram"><svg viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg></a>
                    </div>
                </div>
                <div>
                    <h4>Company</h4>
                    <div class="footer-links">
                        <a href="/about">About us</a>
                        <a href="/#how">How it works</a>
                        <a href="/#services">Services</a>
                        <a href="/contact">Contact</a>
                    </div>
                </div>
                <div>
                    <h4>Legal</h4>
                    <div class="footer-links">
                        <a href="/privacy-policy">Privacy Policy</a>
                        <a href="/terms-and-conditions">Terms of Service</a>
                    </div>
                </div>
                <div>
                    <h4>Get in touch</h4>
                    <div class="footer-contact">
                        <svg viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1H7.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
                        <a href="tel:{{ $cbPhone }}">{{ $cbPhone }}</a>
                    </div>
                    <div class="footer-contact">
                        <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                        <a href="mailto:{{ $cbEmail }}">{{ $cbEmail }}</a>
                    </div>
                    @if($cbAddress)
                    <div class="footer-contact">
                        <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 110-5 2.5 2.5 0 010 5z"/></svg>
                        <span>{{ $cbAddress }}</span>
                    </div>
                    @endif
                </div>
            </div>
            <div class="footer-bottom">
                <span>&copy; {{ date('Y') }} ComingBro. All rights reserved.</span>
                <span>Made with care in India 🇮🇳</span>
            </div>
        </div>
    </footer>

    <script>
        (function(){
            'use strict';
            var html = document.documentElement;

            /* Theme — default dark, persisted */
            var stored = localStorage.getItem('comingbro-theme');
            if (stored) html.setAttribute('data-theme', stored);
            var toggle = document.getElementById('themeToggle');
            if (toggle) toggle.addEventListener('click', function(){
                var next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                html.setAttribute('data-theme', next);
                localStorage.setItem('comingbro-theme', next);
                var tc = document.querySelector('meta[name=theme-color]');
                if (tc) tc.setAttribute('content', next === 'dark' ? '#05080f' : '#f6f9fc');
            });

            /* Navbar shadow on scroll */
            var navbar = document.getElementById('navbar');
            function onScroll(){ navbar.classList.toggle('scrolled', (window.scrollY||window.pageYOffset) > 24); }
            window.addEventListener('scroll', onScroll, { passive:true }); onScroll();

            /* Mobile menu */
            var burger = document.getElementById('hamburger');
            var menu = document.getElementById('mobileMenu');
            if (burger) burger.addEventListener('click', function(){
                burger.classList.toggle('active');
                menu.classList.toggle('open');
                document.body.style.overflow = menu.classList.contains('open') ? 'hidden' : '';
            });
            if (menu) menu.querySelectorAll('a').forEach(function(a){
                a.addEventListener('click', function(){
                    burger.classList.remove('active'); menu.classList.remove('open'); document.body.style.overflow='';
                });
            });

            /* Scroll reveal */
            var revealEls = document.querySelectorAll('[data-reveal]');
            if ('IntersectionObserver' in window) {
                var io = new IntersectionObserver(function(entries){
                    entries.forEach(function(e){
                        if (e.isIntersecting){ e.target.classList.add('in'); io.unobserve(e.target); }
                    });
                }, { threshold:0.12, rootMargin:'0px 0px -8% 0px' });
                revealEls.forEach(function(el){ io.observe(el); });
            } else {
                revealEls.forEach(function(el){ el.classList.add('in'); });
            }
        })();
    </script>
    @yield('scripts')
</body>
</html>

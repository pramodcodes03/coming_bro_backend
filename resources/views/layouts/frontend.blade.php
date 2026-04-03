<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ComingBro')</title>
    <meta name="description" content="@yield('meta_description', 'ComingBro - Fast, safe & affordable ride-hailing across India.')">
    <link rel="icon" href="/assets/images/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* ============================================================
           CSS CUSTOM PROPERTIES — LIGHT THEME (default)
           ============================================================ */
        :root {
            --primary: #018DBD;
            --primary-rgb: 1,141,189;
            --secondary: #13C3C3;
            --secondary-rgb: 19,195,195;
            --dark-accent: #0a1628;
            --bg: #f8fbfd;
            --bg-card: #ffffff;
            --bg-muted: #f1f5f9;
            --text: #0a1628;
            --text-secondary: #64748b;
            --text-tertiary: #94a3b8;
            --border: rgba(0,0,0,0.06);
            --border-strong: rgba(0,0,0,0.10);
            --glass-bg: rgba(255,255,255,0.72);
            --glass-border: rgba(255,255,255,0.5);
            --glass-shadow: rgba(0,0,0,0.04);
            --nav-bg: rgba(248,251,253,0.82);
            --hero-glow-1: rgba(1,141,189,0.10);
            --hero-glow-2: rgba(19,195,195,0.07);
            --dot-color: rgba(0,0,0,0.04);
            --card-hover-shadow: rgba(1,141,189,0.14);
            --footer-bg: #0a1628;
            --footer-text: #cbd5e1;
            --footer-heading: #f1f5f9;
        }

        /* ============================================================
           DARK THEME
           ============================================================ */
        [data-theme="dark"] {
            --bg: #0a1222;
            --bg-card: #111d30;
            --bg-muted: #162033;
            --text: #f1f5f9;
            --text-secondary: #94a3b8;
            --text-tertiary: #64748b;
            --border: rgba(255,255,255,0.06);
            --border-strong: rgba(255,255,255,0.10);
            --glass-bg: rgba(17,29,48,0.78);
            --glass-border: rgba(255,255,255,0.08);
            --glass-shadow: rgba(0,0,0,0.20);
            --nav-bg: rgba(10,18,34,0.85);
            --hero-glow-1: rgba(1,141,189,0.14);
            --hero-glow-2: rgba(19,195,195,0.10);
            --dot-color: rgba(255,255,255,0.03);
            --card-hover-shadow: rgba(1,141,189,0.20);
            --footer-bg: #060d19;
        }

        /* ============================================================
           RESET & BASE
           ============================================================ */
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg);
            color: var(--text);
            line-height: 1.7;
            overflow-x: hidden;
            transition: background-color 0.4s ease, color 0.4s ease;
        }

        a {
            text-decoration: none;
            color: inherit;
            transition: color 0.3s ease;
        }

        img {
            max-width: 100%;
            display: block;
        }

        ul {
            list-style: none;
        }

        button {
            border: none;
            background: none;
            cursor: pointer;
            font-family: inherit;
        }

        /* ============================================================
           CONTAINER
           ============================================================ */
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* ============================================================
           NAVBAR
           ============================================================ */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            padding: 16px 0;
            background: var(--nav-bg);
            backdrop-filter: blur(20px) saturate(1.8);
            -webkit-backdrop-filter: blur(20px) saturate(1.8);
            border-bottom: 1px solid var(--border);
            transition: padding 0.3s ease, background-color 0.4s ease, box-shadow 0.3s ease;
        }

        .navbar.scrolled {
            padding: 10px 0;
            box-shadow: 0 4px 30px var(--glass-shadow);
        }

        .navbar .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 800;
            font-size: 1.3rem;
            color: var(--text);
        }

        .nav-logo img {
            height: 38px;
            width: auto;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 32px;
        }

        .nav-links a {
            font-size: 0.92rem;
            font-weight: 600;
            color: var(--text-secondary);
            position: relative;
            padding: 4px 0;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 2px;
            transition: width 0.3s ease;
        }

        .nav-links a:hover {
            color: var(--text);
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .nav-links a.active {
            color: var(--primary);
        }

        .nav-links a.active::after {
            width: 100%;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .theme-toggle {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-muted);
            border: 1px solid var(--border);
            color: var(--text);
            font-size: 1.2rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .theme-toggle:hover {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
            transform: rotate(20deg);
        }

        .theme-toggle .icon-sun,
        .theme-toggle .icon-moon {
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        [data-theme="light"] .theme-toggle .icon-moon {
            display: none;
        }

        [data-theme="dark"] .theme-toggle .icon-sun {
            display: none;
        }

        .nav-cta {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 22px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
            font-size: 0.88rem;
            font-weight: 700;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(var(--primary-rgb), 0.3);
        }

        .nav-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(var(--primary-rgb), 0.4);
            color: #fff;
        }

        /* Hamburger */
        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: 8px;
            z-index: 1001;
        }

        .hamburger span {
            width: 24px;
            height: 2.5px;
            background: var(--text);
            border-radius: 3px;
            transition: all 0.3s ease;
            display: block;
        }

        .hamburger.active span:nth-child(1) {
            transform: translateY(7.5px) rotate(45deg);
        }

        .hamburger.active span:nth-child(2) {
            opacity: 0;
            transform: translateX(-10px);
        }

        .hamburger.active span:nth-child(3) {
            transform: translateY(-7.5px) rotate(-45deg);
        }

        /* Mobile Menu */
        .mobile-menu {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background: var(--glass-bg);
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            z-index: 999;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 28px;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.4s ease;
        }

        .mobile-menu.open {
            opacity: 1;
            pointer-events: all;
        }

        .mobile-menu a {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text);
            transition: color 0.3s ease;
        }

        .mobile-menu a:hover {
            color: var(--primary);
        }

        /* ============================================================
           PAGE CONTENT
           ============================================================ */
        .page-content {
            padding-top: 80px;
            min-height: 60vh;
        }

        /* ============================================================
           FOOTER
           ============================================================ */
        .footer {
            background: var(--footer-bg);
            padding: 80px 0 0;
            color: var(--footer-text);
        }

        [data-theme="dark"] .footer {
            background: var(--footer-bg);
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr 1.3fr;
            gap: 50px;
            padding-bottom: 50px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .footer-brand p {
            font-size: 0.92rem;
            color: rgba(255,255,255,0.55);
            line-height: 1.8;
            margin-top: 16px;
            max-width: 280px;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .footer-logo img {
            height: 36px;
        }

        .footer-logo-text {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--footer-heading);
        }

        .footer-socials {
            display: flex;
            gap: 10px;
            margin-top: 24px;
        }

        .footer-social {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .footer-social:hover {
            background: var(--primary);
            border-color: var(--primary);
            transform: translateY(-3px);
        }

        .footer-social svg {
            width: 18px;
            height: 18px;
            fill: rgba(255,255,255,0.6);
            transition: fill 0.3s ease;
        }

        .footer-social:hover svg {
            fill: #fff;
        }

        .footer-heading {
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--footer-heading);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 20px;
        }

        .footer-links {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .footer-links a {
            font-size: 0.9rem;
            color: rgba(255,255,255,0.5);
            transition: all 0.3s ease;
        }

        .footer-links a:hover {
            color: #fff;
            padding-left: 6px;
        }

        .footer-contact-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 14px;
        }

        .footer-contact-icon {
            width: 36px;
            height: 36px;
            min-width: 36px;
            border-radius: 10px;
            background: rgba(255,255,255,0.06);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .footer-contact-icon svg {
            width: 16px;
            height: 16px;
            fill: var(--primary);
        }

        .footer-contact-text {
            font-size: 0.9rem;
            color: rgba(255,255,255,0.55);
            line-height: 1.6;
        }

        .footer-contact-text a {
            color: rgba(255,255,255,0.55);
        }

        .footer-contact-text a:hover {
            color: var(--primary);
        }

        .footer-bottom {
            padding: 24px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.84rem;
            color: rgba(255,255,255,0.35);
        }

        .footer-bottom a {
            color: rgba(255,255,255,0.45);
        }

        .footer-bottom a:hover {
            color: var(--primary);
        }

        /* ============================================================
           RESPONSIVE
           ============================================================ */
        @media (max-width: 1024px) {
            .footer-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .nav-cta {
                display: none;
            }

            .hamburger {
                display: flex;
            }

            .mobile-menu {
                display: flex;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 36px;
            }

            .footer-bottom {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 0 16px;
            }
        }

        /* ============================================================
           PAGE-SPECIFIC STYLES (yielded by child pages)
           ============================================================ */
        @yield('styles')
    </style>
</head>
<body>
    <!-- ============================================================
         NAVBAR
         ============================================================ -->
    <nav class="navbar" id="navbar">
        <div class="container">
            <a href="/" class="nav-logo">
                <img src="/assets/images/logo.png" alt="ComingBro Logo">
                <span>ComingBro</span>
            </a>

            <div class="nav-links">
                <a href="/" {{ request()->is('/') ? 'class=active' : '' }}>Home</a>
                <a href="/about" {{ request()->is('about') ? 'class=active' : '' }}>About</a>
                <a href="/#features">Services</a>
                <a href="/#how-it-works">How It Works</a>
                <a href="/#download">Download</a>
                <a href="/contact" {{ request()->is('contact') ? 'class=active' : '' }}>Contact</a>
            </div>

            <div class="nav-actions">
                <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">
                    <span class="icon-sun">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="5"></circle>
                            <line x1="12" y1="1" x2="12" y2="3"></line>
                            <line x1="12" y1="21" x2="12" y2="23"></line>
                            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                            <line x1="1" y1="12" x2="3" y2="12"></line>
                            <line x1="21" y1="12" x2="23" y2="12"></line>
                            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                        </svg>
                    </span>
                    <span class="icon-moon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                        </svg>
                    </span>
                </button>
                <a href="/#download" class="nav-cta">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Get App
                </a>
            </div>

            <button class="hamburger" id="hamburger" aria-label="Menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <a href="/" class="mobile-link">Home</a>
        <a href="/about" class="mobile-link">About</a>
        <a href="/#features" class="mobile-link">Services</a>
        <a href="/#how-it-works" class="mobile-link">How It Works</a>
        <a href="/#download" class="mobile-link">Download</a>
        <a href="/contact" class="mobile-link">Contact</a>
    </div>

    <!-- ============================================================
         PAGE CONTENT
         ============================================================ -->
    <div class="page-content">
        @yield('content')
    </div>

    <!-- ============================================================
         FOOTER
         ============================================================ -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <!-- Brand -->
                <div class="footer-brand">
                    <div class="footer-logo">
                        <img src="/assets/images/logo.png" alt="ComingBro Logo">
                        <span class="footer-logo-text">ComingBro</span>
                    </div>
                    <p>Fast, safe and affordable ride-hailing across India. Book city rides, intercity travel and freight delivery in seconds.</p>
                    <div class="footer-socials">
                        <a href="#" class="footer-social" aria-label="Facebook">
                            <svg viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="footer-social" aria-label="Twitter">
                            <svg viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="#" class="footer-social" aria-label="Instagram">
                            <svg viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="footer-heading">Quick Links</h4>
                    <div class="footer-links">
                        <a href="/about">About Us</a>
                        <a href="/#how-it-works">How It Works</a>
                        <a href="/#features">Features</a>
                        <a href="/#download">Download App</a>
                        <a href="/contact">Contact Us</a>
                    </div>
                </div>

                <!-- Legal -->
                <div>
                    <h4 class="footer-heading">Legal</h4>
                    <div class="footer-links">
                        <a href="/privacy-policy">Privacy Policy</a>
                        <a href="/terms-and-conditions">Terms of Service</a>
                    </div>
                </div>

                <!-- Contact Info -->
                <div>
                    <h4 class="footer-heading">Contact Us</h4>
                    <div class="footer-contact-item">
                        <div class="footer-contact-icon">
                            <svg viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
                        </div>
                        <div class="footer-contact-text">
                            <a href="tel:+919028777184">+91 9028777184</a>
                        </div>
                    </div>
                    <div class="footer-contact-item">
                        <div class="footer-contact-icon">
                            <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                        </div>
                        <div class="footer-contact-text">
                            <a href="mailto:support@comingbro.in">support@comingbro.in</a>
                        </div>
                    </div>
                    <div class="footer-contact-item">
                        <div class="footer-contact-icon">
                            <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                        </div>
                        <div class="footer-contact-text">
                            India
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <span>&copy; {{ date('Y') }} Coming Bro. All rights reserved.</span>
                <span>Made with care in India</span>
            </div>
        </div>
    </footer>

    <!-- ============================================================
         JAVASCRIPT
         ============================================================ -->
    <script>
        (function() {
            'use strict';

            /* --------------------------------------------------------
               THEME TOGGLE
               -------------------------------------------------------- */
            const html = document.documentElement;
            const themeToggle = document.getElementById('themeToggle');
            const stored = localStorage.getItem('comingbro-theme');
            if (stored) {
                html.setAttribute('data-theme', stored);
            }

            themeToggle.addEventListener('click', function() {
                const current = html.getAttribute('data-theme');
                const next = current === 'dark' ? 'light' : 'dark';
                html.setAttribute('data-theme', next);
                localStorage.setItem('comingbro-theme', next);
            });

            /* --------------------------------------------------------
               NAVBAR SCROLL
               -------------------------------------------------------- */
            const navbar = document.getElementById('navbar');

            window.addEventListener('scroll', function() {
                var sy = window.scrollY || window.pageYOffset;
                if (sy > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            }, { passive: true });

            /* --------------------------------------------------------
               HAMBURGER / MOBILE MENU
               -------------------------------------------------------- */
            const hamburger = document.getElementById('hamburger');
            const mobileMenu = document.getElementById('mobileMenu');

            hamburger.addEventListener('click', function() {
                hamburger.classList.toggle('active');
                mobileMenu.classList.toggle('open');
                document.body.style.overflow = mobileMenu.classList.contains('open') ? 'hidden' : '';
            });

            var mobileLinks = mobileMenu.querySelectorAll('a');
            mobileLinks.forEach(function(link) {
                link.addEventListener('click', function() {
                    hamburger.classList.remove('active');
                    mobileMenu.classList.remove('open');
                    document.body.style.overflow = '';
                });
            });
        })();
    </script>
    @yield('scripts')
</body>
</html>

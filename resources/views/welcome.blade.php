<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ComingBro — Your Ride Is Already On Its Way</title>
    <meta name="description" content="ComingBro - Fast, safe & affordable ride-hailing across India. Book city rides, intercity travel & freight delivery in seconds.">
    <link rel="icon" href="/assets/images/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
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
            --faq-bg: var(--bg-card);
            --faq-hover: rgba(1,141,189,0.04);
            --footer-bg: #0a1628;
            --footer-text: #cbd5e1;
            --footer-heading: #f1f5f9;
            --marquee-bg: #018DBD;
            --cursor-color: var(--primary);
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
            --faq-bg: var(--bg-card);
            --faq-hover: rgba(1,141,189,0.08);
            --footer-bg: #060d19;
            --cursor-color: var(--secondary);
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
           CUSTOM CURSOR
           ============================================================ */
        .cursor-dot {
            width: 8px;
            height: 8px;
            background: var(--cursor-color);
            border-radius: 50%;
            position: fixed;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: 99999;
            transition: transform 0.15s ease, opacity 0.3s ease;
        }

        .cursor-ring {
            width: 36px;
            height: 36px;
            border: 2px solid var(--cursor-color);
            border-radius: 50%;
            position: fixed;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: 99998;
            opacity: 0.5;
            transition: width 0.3s ease, height 0.3s ease, opacity 0.3s ease, border-color 0.3s ease;
        }

        .cursor-dot.hover {
            transform: scale(2.5);
        }

        .cursor-ring.hover {
            width: 50px;
            height: 50px;
            opacity: 0.25;
        }

        @media (hover: none) and (pointer: coarse) {
            .cursor-dot, .cursor-ring {
                display: none !important;
            }
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
           SCROLL REVEAL
           ============================================================ */
        [data-reveal] {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1),
                        transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        [data-reveal="left"] {
            transform: translateX(-40px);
        }

        [data-reveal="right"] {
            transform: translateX(40px);
        }

        [data-reveal="scale"] {
            transform: scale(0.92);
        }

        [data-reveal].revealed {
            opacity: 1;
            transform: translateY(0) translateX(0) scale(1);
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
           BUTTONS
           ============================================================ */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 28px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 0.95rem;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
            box-shadow: 0 4px 20px rgba(var(--primary-rgb), 0.30);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 35px rgba(var(--primary-rgb), 0.45);
            color: #fff;
        }

        .btn-outline {
            background: transparent;
            color: var(--text);
            border: 2px solid var(--border-strong);
        }

        .btn-outline:hover {
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-3px);
        }

        .btn-store {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 12px 24px;
            border-radius: 14px;
            font-weight: 600;
            font-size: 0.88rem;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            border: none;
        }

        .btn-store-dark {
            background: var(--dark-accent);
            color: #fff;
        }

        [data-theme="dark"] .btn-store-dark {
            background: #fff;
            color: #0a1628;
        }

        .btn-store-dark:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }

        .btn-store-outline {
            background: transparent;
            color: var(--text);
            border: 2px solid var(--border-strong);
        }

        .btn-store-outline:hover {
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-3px);
        }

        .btn-store .store-icon {
            width: 24px;
            height: 24px;
            flex-shrink: 0;
        }

        .btn-store .store-text {
            display: flex;
            flex-direction: column;
            text-align: left;
            line-height: 1.2;
        }

        .btn-store .store-text small {
            font-size: 0.68rem;
            opacity: 0.7;
            font-weight: 500;
        }

        .btn-store .store-text strong {
            font-size: 0.95rem;
        }

        /* ============================================================
           SECTION COMMON
           ============================================================ */
        .section {
            padding: 100px 0;
            position: relative;
        }

        .section-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px;
            background: rgba(var(--primary-rgb), 0.08);
            color: var(--primary);
            font-size: 0.8rem;
            font-weight: 700;
            border-radius: 100px;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            margin-bottom: 16px;
        }

        .section-label::before {
            content: '';
            width: 6px;
            height: 6px;
            background: var(--primary);
            border-radius: 50%;
        }

        .section-title {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 800;
            line-height: 1.15;
            color: var(--text);
            margin-bottom: 16px;
            letter-spacing: -0.02em;
        }

        .section-title span {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .section-desc {
            font-size: 1.05rem;
            color: var(--text-secondary);
            max-width: 580px;
            line-height: 1.8;
        }

        /* ============================================================
           HERO
           ============================================================ */
        .hero {
            padding-top: 140px;
            padding-bottom: 80px;
            position: relative;
            overflow: hidden;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -20%;
            left: -10%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, var(--hero-glow-1) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: -10%;
            right: -5%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, var(--hero-glow-2) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .hero-content {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 18px;
            background: rgba(var(--primary-rgb), 0.08);
            border: 1px solid rgba(var(--primary-rgb), 0.15);
            border-radius: 100px;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--primary);
            width: fit-content;
        }

        .hero-badge svg {
            width: 16px;
            height: 16px;
        }

        .hero-title {
            font-size: clamp(2.6rem, 5.5vw, 4.2rem);
            font-weight: 800;
            line-height: 1.08;
            letter-spacing: -0.03em;
            color: var(--text);
        }

        .hero-title span {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-desc {
            font-size: 1.1rem;
            color: var(--text-secondary);
            line-height: 1.8;
            max-width: 500px;
        }

        .hero-ctas {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
        }

        .hero-trust {
            display: flex;
            align-items: center;
            gap: 16px;
            padding-top: 16px;
        }

        .hero-trust-avatars {
            display: flex;
        }

        .hero-trust-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: 3px solid var(--bg);
            margin-left: -10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
            font-weight: 700;
            color: #fff;
        }

        .hero-trust-avatar:first-child {
            margin-left: 0;
        }

        .hero-trust-text {
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        .hero-trust-text strong {
            color: var(--text);
            font-weight: 700;
        }

        /* Phone mockup */
        .hero-visual {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .phone-mockup {
            width: 280px;
            height: 570px;
            background: var(--bg-card);
            border-radius: 40px;
            border: 3px solid var(--border-strong);
            position: relative;
            overflow: hidden;
            box-shadow:
                0 25px 60px rgba(0,0,0,0.12),
                0 0 0 1px var(--border),
                inset 0 2px 4px rgba(255,255,255,0.1);
            animation: phoneBob 4s ease-in-out infinite;
        }

        @keyframes phoneBob {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-14px); }
        }

        .phone-notch {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 130px;
            height: 30px;
            background: var(--dark-accent);
            border-radius: 0 0 18px 18px;
            z-index: 3;
        }

        [data-theme="dark"] .phone-notch {
            background: #000;
        }

        .phone-screen {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(180deg, #e8f4f8 0%, #d1ecf5 100%);
            display: flex;
            flex-direction: column;
        }

        [data-theme="dark"] .phone-screen {
            background: linear-gradient(180deg, #0f1a2e 0%, #152238 100%);
        }

        .phone-map {
            flex: 1;
            position: relative;
            overflow: hidden;
            padding: 40px 20px 20px;
        }

        .phone-map svg {
            width: 100%;
            height: 100%;
        }

        .phone-bottom-card {
            background: var(--bg-card);
            border-radius: 20px 20px 0 0;
            padding: 16px 20px;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.06);
        }

        .phone-ride-info {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
        }

        .phone-ride-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .phone-ride-icon svg {
            width: 22px;
            height: 22px;
            fill: #fff;
        }

        .phone-ride-text {
            flex: 1;
        }

        .phone-ride-text .ride-label {
            font-size: 0.7rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .phone-ride-text .ride-dest {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--text);
        }

        .phone-ride-price {
            font-size: 0.9rem;
            font-weight: 800;
            color: var(--primary);
        }

        .phone-btn {
            width: 100%;
            padding: 10px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 0.78rem;
            font-weight: 700;
            text-align: center;
            letter-spacing: 0.04em;
        }

        /* Floating elements around phone */
        .hero-float {
            position: absolute;
            border-radius: 16px;
            padding: 14px 18px;
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            box-shadow: 0 8px 30px var(--glass-shadow);
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text);
            z-index: 4;
            white-space: nowrap;
        }

        .hero-float-icon {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .hero-float-1 {
            top: 25%;
            right: -20px;
            animation: floatA 5s ease-in-out infinite;
        }

        .hero-float-2 {
            bottom: 20%;
            left: -30px;
            animation: floatB 6s ease-in-out infinite;
        }

        @keyframes floatA {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(2deg); }
        }

        @keyframes floatB {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(8px) rotate(-2deg); }
        }

        /* ============================================================
           MARQUEE TICKER
           ============================================================ */
        .ticker {
            background: var(--marquee-bg);
            padding: 14px 0;
            overflow: hidden;
            position: relative;
        }

        .ticker-track {
            display: flex;
            gap: 40px;
            animation: marquee 30s linear infinite;
            width: max-content;
        }

        .ticker-item {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.9rem;
            font-weight: 700;
            color: #fff;
            white-space: nowrap;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .ticker-item svg {
            width: 18px;
            height: 18px;
            fill: #fff;
            opacity: 0.7;
        }

        .ticker-dot {
            width: 6px;
            height: 6px;
            background: rgba(255,255,255,0.4);
            border-radius: 50%;
        }

        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        /* ============================================================
           OUR MISSION
           ============================================================ */
        .mission-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
        }

        .mission-visual {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 450px;
        }

        .mission-shape {
            width: 350px;
            height: 350px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(var(--primary-rgb), 0.08), rgba(var(--secondary-rgb), 0.06));
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .mission-shape::before {
            content: '';
            position: absolute;
            width: 420px;
            height: 420px;
            border-radius: 50%;
            border: 2px dashed rgba(var(--primary-rgb), 0.12);
            animation: spinSlow 25s linear infinite;
        }

        @keyframes spinSlow {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .mission-shape svg {
            width: 200px;
            height: 200px;
        }

        .mission-float-card {
            position: absolute;
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 14px 20px;
            box-shadow: 0 8px 25px var(--glass-shadow);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .mission-float-card-1 {
            top: 10%;
            right: 0;
            animation: floatA 5s ease-in-out infinite;
        }

        .mission-float-card-2 {
            bottom: 10%;
            left: 0;
            animation: floatB 6s ease-in-out infinite;
        }

        .mission-float-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .mission-float-text {
            font-size: 0.82rem;
        }

        .mission-float-text strong {
            display: block;
            font-weight: 700;
            color: var(--text);
        }

        .mission-float-text small {
            color: var(--text-secondary);
            font-size: 0.72rem;
        }

        .mission-content {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .mission-text {
            font-size: 1.02rem;
            color: var(--text-secondary);
            line-height: 1.8;
        }

        .mission-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin-top: 8px;
        }

        .mission-list li {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            font-size: 0.95rem;
            color: var(--text-secondary);
            line-height: 1.6;
        }

        .mission-list li .check-icon {
            width: 24px;
            height: 24px;
            min-width: 24px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 2px;
        }

        .mission-list li .check-icon svg {
            width: 12px;
            height: 12px;
            fill: #fff;
        }

        /* ============================================================
           HOW IT WORKS
           ============================================================ */
        .how-it-works {
            background: var(--bg-muted);
        }

        .how-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .how-header .section-desc {
            margin-left: auto;
            margin-right: auto;
        }

        .how-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            position: relative;
        }

        /* Connector line behind cards */
        .how-cards::before {
            content: '';
            position: absolute;
            top: 70px;
            left: 15%;
            right: 15%;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--secondary), var(--primary));
            border-radius: 3px;
            z-index: 0;
            opacity: 0.25;
        }

        .how-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 40px 30px;
            text-align: center;
            position: relative;
            z-index: 1;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .how-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 50px var(--card-hover-shadow);
            border-color: rgba(var(--primary-rgb), 0.2);
        }

        .how-card-icon {
            width: 72px;
            height: 72px;
            margin: 0 auto 24px;
            border-radius: 20px;
            background: linear-gradient(135deg, rgba(var(--primary-rgb), 0.10), rgba(var(--secondary-rgb), 0.08));
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.4s ease;
        }

        .how-card:hover .how-card-icon {
            transform: scale(1.1) rotate(-4deg);
            background: linear-gradient(135deg, var(--primary), var(--secondary));
        }

        .how-card-icon svg {
            width: 32px;
            height: 32px;
            fill: var(--primary);
            transition: fill 0.3s ease;
        }

        .how-card:hover .how-card-icon svg {
            fill: #fff;
        }

        .how-card-step {
            display: inline-block;
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--primary);
            background: rgba(var(--primary-rgb), 0.08);
            padding: 4px 12px;
            border-radius: 100px;
            margin-bottom: 14px;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .how-card-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .how-card-desc {
            font-size: 0.92rem;
            color: var(--text-secondary);
            line-height: 1.7;
        }

        /* ============================================================
           FEATURES
           ============================================================ */
        .features-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .features-header .section-desc {
            margin-left: auto;
            margin-right: auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .feature-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 34px 28px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .feature-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 50px var(--card-hover-shadow);
            border-color: rgba(var(--primary-rgb), 0.2);
        }

        .feature-card-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            background: linear-gradient(135deg, rgba(var(--primary-rgb), 0.10), rgba(var(--secondary-rgb), 0.08));
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            transition: all 0.4s ease;
        }

        .feature-card:hover .feature-card-icon {
            transform: scale(1.1) rotate(-4deg);
            background: linear-gradient(135deg, var(--primary), var(--secondary));
        }

        .feature-card-icon svg {
            width: 26px;
            height: 26px;
            fill: var(--primary);
            transition: fill 0.3s ease;
        }

        .feature-card:hover .feature-card-icon svg {
            fill: #fff;
        }

        .feature-card-title {
            font-size: 1.05rem;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 10px;
        }

        .feature-card-desc {
            font-size: 0.9rem;
            color: var(--text-secondary);
            line-height: 1.7;
        }

        /* ============================================================
           GET APP / DOWNLOAD
           ============================================================ */
        .get-app {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            position: relative;
            overflow: hidden;
        }

        .get-app::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
            border-radius: 50%;
        }

        .get-app::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255,255,255,0.06) 0%, transparent 70%);
            border-radius: 50%;
        }

        .get-app-content {
            text-align: center;
            position: relative;
            z-index: 2;
            max-width: 650px;
            margin: 0 auto;
        }

        .get-app-content .section-label {
            background: rgba(255,255,255,0.15);
            color: #fff;
        }

        .get-app-content .section-label::before {
            background: #fff;
        }

        .get-app-title {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 800;
            line-height: 1.15;
            color: #fff;
            margin-bottom: 16px;
            letter-spacing: -0.02em;
        }

        .get-app-desc {
            font-size: 1.05rem;
            color: rgba(255,255,255,0.85);
            line-height: 1.8;
            margin-bottom: 32px;
        }

        .get-app-buttons {
            display: flex;
            gap: 14px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .get-app .btn-store {
            background: #fff;
            color: #0a1628;
        }

        .get-app .btn-store:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.2);
        }

        .get-app .btn-store-outline {
            background: transparent;
            border: 2px solid rgba(255,255,255,0.4);
            color: #fff;
        }

        .get-app .btn-store-outline:hover {
            border-color: #fff;
            background: rgba(255,255,255,0.1);
        }

        /* Decorative floating shapes in get-app */
        .get-app-shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
            pointer-events: none;
        }

        .get-app-shape-1 {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 8%;
            animation: floatA 6s ease-in-out infinite;
        }

        .get-app-shape-2 {
            width: 50px;
            height: 50px;
            bottom: 25%;
            right: 12%;
            animation: floatB 5s ease-in-out infinite;
        }

        .get-app-shape-3 {
            width: 30px;
            height: 30px;
            top: 30%;
            right: 20%;
            animation: floatA 7s ease-in-out infinite;
        }

        /* ============================================================
           FAQ
           ============================================================ */
        .faq-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .faq-header .section-desc {
            margin-left: auto;
            margin-right: auto;
        }

        .faq-list {
            max-width: 760px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .faq-item {
            background: var(--faq-bg);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .faq-item:hover {
            background: var(--faq-hover);
        }

        .faq-item.active {
            border-color: rgba(var(--primary-rgb), 0.2);
            box-shadow: 0 4px 20px var(--card-hover-shadow);
        }

        .faq-question {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 20px 24px;
            cursor: pointer;
            user-select: none;
            width: 100%;
            text-align: left;
            font-size: 1rem;
            font-weight: 700;
            color: var(--text);
            background: none;
            border: none;
            font-family: inherit;
        }

        .faq-question:hover {
            color: var(--primary);
        }

        .faq-icon {
            width: 32px;
            height: 32px;
            min-width: 32px;
            border-radius: 10px;
            background: rgba(var(--primary-rgb), 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .faq-item.active .faq-icon {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            transform: rotate(45deg);
        }

        .faq-icon svg {
            width: 14px;
            height: 14px;
            fill: var(--primary);
            transition: fill 0.3s ease;
        }

        .faq-item.active .faq-icon svg {
            fill: #fff;
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1), padding 0.3s ease;
        }

        .faq-answer-inner {
            padding: 0 24px 20px;
            font-size: 0.94rem;
            color: var(--text-secondary);
            line-height: 1.8;
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
            .hero-grid {
                grid-template-columns: 1fr;
                gap: 50px;
                text-align: center;
            }

            .hero-content {
                align-items: center;
            }

            .hero-desc {
                margin: 0 auto;
            }

            .hero-ctas {
                justify-content: center;
            }

            .hero-trust {
                justify-content: center;
            }

            .hero-visual {
                order: -1;
            }

            .phone-mockup {
                width: 240px;
                height: 490px;
            }

            .hero-float-1 {
                right: 10%;
            }

            .hero-float-2 {
                left: 10%;
            }

            .mission-grid {
                grid-template-columns: 1fr;
                gap: 50px;
            }

            .mission-visual {
                min-height: 350px;
            }

            .how-cards {
                grid-template-columns: 1fr;
                max-width: 420px;
                margin: 0 auto;
            }

            .how-cards::before {
                display: none;
            }

            .features-grid {
                grid-template-columns: repeat(2, 1fr);
            }

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

            .section {
                padding: 70px 0;
            }

            .hero {
                padding-top: 120px;
                padding-bottom: 60px;
                min-height: auto;
            }

            .hero-float {
                display: none;
            }

            .features-grid {
                grid-template-columns: 1fr;
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

            .mission-shape {
                width: 260px;
                height: 260px;
            }

            .mission-shape::before {
                width: 320px;
                height: 320px;
            }

            .mission-float-card {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 0 16px;
            }

            .hero-title {
                font-size: 2.2rem;
            }

            .phone-mockup {
                width: 220px;
                height: 450px;
            }

            .get-app-buttons {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <!-- Custom Cursor -->
    <div class="cursor-dot" id="cursorDot"></div>
    <div class="cursor-ring" id="cursorRing"></div>

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
                <a href="/" class="active">Home</a>
                <a href="/about">About</a>
                <a href="#features">Services</a>
                <a href="#how-it-works">How It Works</a>
                <a href="#download">Download</a>
                <a href="/contact">Contact</a>
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
                <a href="#download" class="nav-cta">
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
        <a href="#features" class="mobile-link">Services</a>
        <a href="#how-it-works" class="mobile-link">How It Works</a>
        <a href="#download" class="mobile-link">Download</a>
        <a href="/contact" class="mobile-link">Contact</a>
    </div>

    <!-- ============================================================
         HERO
         ============================================================ -->
    <section class="hero" id="hero">
        <div class="container">
            <div class="hero-grid">
                <div class="hero-content" data-reveal="left">
                    <div class="hero-badge">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        #1 Ride-Hailing App in India
                    </div>

                    <h1 class="hero-title">
                        The Best Way to Get <span>Wherever</span> You're Going
                    </h1>

                    <p class="hero-desc">
                        The online cab booking service providers care the price of traveling based on the distance of travel trip and type of car, traffic, and waiting prices. There are numerous apps.
                    </p>

                    <div class="hero-ctas">
                        <a href="#" class="btn-store btn-store-dark">
                            <span class="store-icon">
                                <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>
                            </span>
                            <span class="store-text">
                                <small>Download on the</small>
                                <strong>App Store</strong>
                            </span>
                        </a>
                        <a href="#" class="btn-store btn-store-outline">
                            <span class="store-icon">
                                <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M3.609 1.814L13.792 12 3.61 22.186a.996.996 0 0 1-.61-.92V2.734a1 1 0 0 1 .609-.92zm10.89 10.893l2.302 2.302-10.937 6.333 8.635-8.635zm3.199-3.199l2.302 1.327a1 1 0 0 1 0 1.73l-2.302 1.327-2.53-2.53 2.53-2.854zM5.864 2.658L16.8 8.99l-2.302 2.302-8.635-8.635z"/></svg>
                            </span>
                            <span class="store-text">
                                <small>Get it on</small>
                                <strong>Google Play</strong>
                            </span>
                        </a>
                    </div>

                    <div class="hero-trust">
                        <div class="hero-trust-avatars">
                            <div class="hero-trust-avatar">AK</div>
                            <div class="hero-trust-avatar">PR</div>
                            <div class="hero-trust-avatar">SM</div>
                            <div class="hero-trust-avatar">VJ</div>
                            <div class="hero-trust-avatar" style="background: linear-gradient(135deg, var(--secondary), var(--primary));">+5k</div>
                        </div>
                        <div class="hero-trust-text">
                            <strong>5,000+</strong> happy riders across India
                        </div>
                    </div>
                </div>

                <div class="hero-visual" data-reveal="right">
                    <div class="phone-mockup">
                        <div class="phone-notch"></div>
                        <div class="phone-screen">
                            <div class="phone-map">
                                <svg viewBox="0 0 240 360" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <!-- Map background grid lines -->
                                    <line x1="0" y1="40" x2="240" y2="40" stroke="var(--border)" stroke-width="0.5" />
                                    <line x1="0" y1="80" x2="240" y2="80" stroke="var(--border)" stroke-width="0.5" />
                                    <line x1="0" y1="120" x2="240" y2="120" stroke="var(--border)" stroke-width="0.5" />
                                    <line x1="0" y1="160" x2="240" y2="160" stroke="var(--border)" stroke-width="0.5" />
                                    <line x1="0" y1="200" x2="240" y2="200" stroke="var(--border)" stroke-width="0.5" />
                                    <line x1="0" y1="240" x2="240" y2="240" stroke="var(--border)" stroke-width="0.5" />
                                    <line x1="0" y1="280" x2="240" y2="280" stroke="var(--border)" stroke-width="0.5" />
                                    <line x1="0" y1="320" x2="240" y2="320" stroke="var(--border)" stroke-width="0.5" />
                                    <line x1="40" y1="0" x2="40" y2="360" stroke="var(--border)" stroke-width="0.5" />
                                    <line x1="80" y1="0" x2="80" y2="360" stroke="var(--border)" stroke-width="0.5" />
                                    <line x1="120" y1="0" x2="120" y2="360" stroke="var(--border)" stroke-width="0.5" />
                                    <line x1="160" y1="0" x2="160" y2="360" stroke="var(--border)" stroke-width="0.5" />
                                    <line x1="200" y1="0" x2="200" y2="360" stroke="var(--border)" stroke-width="0.5" />

                                    <!-- Roads -->
                                    <rect x="30" y="55" width="180" height="12" rx="6" fill="rgba(var(--primary-rgb), 0.08)" />
                                    <rect x="70" y="20" width="12" height="200" rx="6" fill="rgba(var(--primary-rgb), 0.06)" />
                                    <rect x="150" y="80" width="12" height="180" rx="6" fill="rgba(var(--primary-rgb), 0.06)" />
                                    <rect x="40" y="150" width="160" height="12" rx="6" fill="rgba(var(--primary-rgb), 0.08)" />
                                    <rect x="20" y="230" width="200" height="12" rx="6" fill="rgba(var(--primary-rgb), 0.06)" />
                                    <rect x="105" y="100" width="12" height="160" rx="6" fill="rgba(var(--primary-rgb), 0.05)" />

                                    <!-- Route path -->
                                    <path d="M60 70 L76 70 L76 155 L156 155 L156 240 L130 240"
                                          stroke="var(--primary)" stroke-width="4" stroke-linecap="round"
                                          stroke-dasharray="8 5" fill="none" opacity="0.7">
                                        <animate attributeName="stroke-dashoffset" from="0" to="-26" dur="1.5s" repeatCount="indefinite"/>
                                    </path>

                                    <!-- Pickup marker -->
                                    <circle cx="60" cy="70" r="16" fill="var(--primary)" opacity="0.15" />
                                    <circle cx="60" cy="70" r="8" fill="var(--primary)" />
                                    <circle cx="60" cy="70" r="3" fill="#fff" />

                                    <!-- Pickup pulse -->
                                    <circle cx="60" cy="70" r="8" fill="none" stroke="var(--primary)" stroke-width="2" opacity="0.6">
                                        <animate attributeName="r" from="8" to="24" dur="2s" repeatCount="indefinite"/>
                                        <animate attributeName="opacity" from="0.6" to="0" dur="2s" repeatCount="indefinite"/>
                                    </circle>

                                    <!-- Drop marker -->
                                    <circle cx="130" cy="240" r="16" fill="var(--secondary)" opacity="0.15" />
                                    <circle cx="130" cy="240" r="8" fill="var(--secondary)" />
                                    <circle cx="130" cy="240" r="3" fill="#fff" />

                                    <!-- Car icon on route -->
                                    <g transform="translate(110, 155) rotate(0)">
                                        <rect x="-8" y="-5" width="16" height="10" rx="3" fill="var(--primary)" />
                                        <rect x="-5" y="-8" width="10" height="4" rx="2" fill="var(--primary)" opacity="0.7" />
                                        <circle cx="-5" cy="5" r="2.5" fill="var(--dark-accent)" />
                                        <circle cx="5" cy="5" r="2.5" fill="var(--dark-accent)" />
                                    </g>

                                    <!-- Buildings / landmarks -->
                                    <rect x="170" y="45" width="30" height="40" rx="4" fill="rgba(var(--primary-rgb), 0.07)" />
                                    <rect x="175" y="50" width="8" height="8" rx="1" fill="rgba(var(--primary-rgb), 0.12)" />
                                    <rect x="187" y="50" width="8" height="8" rx="1" fill="rgba(var(--primary-rgb), 0.12)" />
                                    <rect x="175" y="62" width="8" height="8" rx="1" fill="rgba(var(--primary-rgb), 0.12)" />
                                    <rect x="187" y="62" width="8" height="8" rx="1" fill="rgba(var(--primary-rgb), 0.12)" />

                                    <rect x="15" y="175" width="40" height="30" rx="4" fill="rgba(var(--secondary-rgb), 0.07)" />
                                    <rect x="20" y="180" width="12" height="8" rx="1" fill="rgba(var(--secondary-rgb), 0.12)" />
                                    <rect x="36" y="180" width="12" height="8" rx="1" fill="rgba(var(--secondary-rgb), 0.12)" />

                                    <rect x="180" y="200" width="35" height="45" rx="4" fill="rgba(var(--primary-rgb), 0.06)" />
                                    <rect x="185" y="205" width="8" height="8" rx="1" fill="rgba(var(--primary-rgb), 0.1)" />
                                    <rect x="197" y="205" width="8" height="8" rx="1" fill="rgba(var(--primary-rgb), 0.1)" />
                                    <rect x="185" y="218" width="8" height="8" rx="1" fill="rgba(var(--primary-rgb), 0.1)" />
                                    <rect x="197" y="218" width="8" height="8" rx="1" fill="rgba(var(--primary-rgb), 0.1)" />
                                    <rect x="185" y="231" width="8" height="8" rx="1" fill="rgba(var(--primary-rgb), 0.1)" />

                                    <!-- Park / green area -->
                                    <ellipse cx="40" cy="280" rx="28" ry="22" fill="rgba(16,185,129,0.08)" />
                                    <circle cx="32" cy="275" r="6" fill="rgba(16,185,129,0.12)" />
                                    <circle cx="48" cy="278" r="5" fill="rgba(16,185,129,0.10)" />
                                    <circle cx="38" cy="285" r="4" fill="rgba(16,185,129,0.12)" />
                                </svg>
                            </div>
                            <div class="phone-bottom-card">
                                <div class="phone-ride-info">
                                    <div class="phone-ride-icon">
                                        <svg viewBox="0 0 24 24"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/></svg>
                                    </div>
                                    <div class="phone-ride-text">
                                        <div class="ride-label">Your ride</div>
                                        <div class="ride-dest">MG Road, Pune</div>
                                    </div>
                                    <div class="phone-ride-price">&#8377;149</div>
                                </div>
                                <div class="phone-btn">CONFIRM RIDE</div>
                            </div>
                        </div>
                    </div>

                    <!-- Floating card 1 -->
                    <div class="hero-float hero-float-1">
                        <div class="hero-float-icon" style="background: rgba(16,185,129,0.12);">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        Ride Confirmed!
                    </div>

                    <!-- Floating card 2 -->
                    <div class="hero-float hero-float-2">
                        <div class="hero-float-icon" style="background: rgba(var(--primary-rgb), 0.12);">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                        ETA: 4 min
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         MARQUEE TICKER
         ============================================================ -->
    <div class="ticker">
        <div class="ticker-track" id="tickerTrack">
            <span class="ticker-item">
                <svg viewBox="0 0 24 24"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/></svg>
                City Rides
            </span>
            <span class="ticker-dot"></span>
            <span class="ticker-item">
                <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
                Intercity Travel
            </span>
            <span class="ticker-dot"></span>
            <span class="ticker-item">
                <svg viewBox="0 0 24 24"><path d="M19.15 8a2 2 0 0 0-1.72-1H15V5a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v11a2 2 0 1 0 4 0h6a2 2 0 1 0 4 0h1a1 1 0 0 0 1-1v-3.28a2 2 0 0 0-.28-1.03L17.43 8zM15 9.62l1.38 2.38H15V9.62z" fill="white"/></svg>
                Freight Delivery
            </span>
            <span class="ticker-dot"></span>
            <span class="ticker-item">
                <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                Verified Drivers
            </span>
            <span class="ticker-dot"></span>
            <span class="ticker-item">
                <svg viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg>
                Safe &amp; Secure
            </span>
            <span class="ticker-dot"></span>
            <span class="ticker-item">
                <svg viewBox="0 0 24 24"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
                Fair Pricing
            </span>
            <span class="ticker-dot"></span>
            <span class="ticker-item">
                <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                Live Tracking
            </span>
            <span class="ticker-dot"></span>
            <span class="ticker-item">
                <svg viewBox="0 0 24 24"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
                24/7 Support
            </span>
            <span class="ticker-dot"></span>
            <!-- duplicate set for seamless loop -->
            <span class="ticker-item">
                <svg viewBox="0 0 24 24"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/></svg>
                City Rides
            </span>
            <span class="ticker-dot"></span>
            <span class="ticker-item">
                <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
                Intercity Travel
            </span>
            <span class="ticker-dot"></span>
            <span class="ticker-item">
                <svg viewBox="0 0 24 24"><path d="M19.15 8a2 2 0 0 0-1.72-1H15V5a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v11a2 2 0 1 0 4 0h6a2 2 0 1 0 4 0h1a1 1 0 0 0 1-1v-3.28a2 2 0 0 0-.28-1.03L17.43 8zM15 9.62l1.38 2.38H15V9.62z" fill="white"/></svg>
                Freight Delivery
            </span>
            <span class="ticker-dot"></span>
            <span class="ticker-item">
                <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                Verified Drivers
            </span>
            <span class="ticker-dot"></span>
            <span class="ticker-item">
                <svg viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg>
                Safe &amp; Secure
            </span>
            <span class="ticker-dot"></span>
            <span class="ticker-item">
                <svg viewBox="0 0 24 24"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
                Fair Pricing
            </span>
            <span class="ticker-dot"></span>
            <span class="ticker-item">
                <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                Live Tracking
            </span>
            <span class="ticker-dot"></span>
            <span class="ticker-item">
                <svg viewBox="0 0 24 24"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
                24/7 Support
            </span>
            <span class="ticker-dot"></span>
        </div>
    </div>

    <!-- ============================================================
         OUR MISSION
         ============================================================ -->
    <section class="section" id="our-mission">
        <div class="container">
            <div class="mission-grid">
                <div class="mission-visual" data-reveal="left">
                    <div class="mission-shape">
                        <!-- Car / ride SVG illustration -->
                        <svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Road -->
                            <ellipse cx="100" cy="145" rx="80" ry="18" fill="rgba(var(--primary-rgb), 0.06)" />
                            <rect x="25" y="138" width="150" height="6" rx="3" fill="rgba(var(--primary-rgb), 0.08)" />
                            <line x1="40" y1="141" x2="55" y2="141" stroke="var(--secondary)" stroke-width="2" stroke-dasharray="4 4" opacity="0.4" />
                            <line x1="65" y1="141" x2="80" y2="141" stroke="var(--secondary)" stroke-width="2" stroke-dasharray="4 4" opacity="0.4" />
                            <line x1="90" y1="141" x2="105" y2="141" stroke="var(--secondary)" stroke-width="2" stroke-dasharray="4 4" opacity="0.4" />
                            <line x1="115" y1="141" x2="130" y2="141" stroke="var(--secondary)" stroke-width="2" stroke-dasharray="4 4" opacity="0.4" />
                            <line x1="140" y1="141" x2="155" y2="141" stroke="var(--secondary)" stroke-width="2" stroke-dasharray="4 4" opacity="0.4" />

                            <!-- Car body -->
                            <rect x="52" y="104" width="96" height="36" rx="10" fill="var(--primary)" />
                            <rect x="60" y="84" width="72" height="28" rx="8" fill="var(--primary)" opacity="0.85" />

                            <!-- Windows -->
                            <rect x="66" y="89" width="28" height="18" rx="4" fill="rgba(255,255,255,0.35)" />
                            <rect x="98" y="89" width="28" height="18" rx="4" fill="rgba(255,255,255,0.35)" />

                            <!-- Headlights -->
                            <rect x="140" y="112" width="10" height="8" rx="4" fill="#fbbf24" />
                            <rect x="50" y="112" width="10" height="8" rx="4" fill="#ef4444" opacity="0.8" />

                            <!-- Wheels -->
                            <circle cx="78" cy="140" r="14" fill="var(--dark-accent)" />
                            <circle cx="78" cy="140" r="7" fill="var(--text-secondary)" opacity="0.3" />
                            <circle cx="78" cy="140" r="3" fill="var(--bg-card)" />
                            <circle cx="122" cy="140" r="14" fill="var(--dark-accent)" />
                            <circle cx="122" cy="140" r="7" fill="var(--text-secondary)" opacity="0.3" />
                            <circle cx="122" cy="140" r="3" fill="var(--bg-card)" />

                            <!-- Location pin above car -->
                            <g transform="translate(100, 50)">
                                <path d="M0-25C-8-25-14-19-14-11C-14-2 0 12 0 12S14-2 14-11C14-19 8-25 0-25Z" fill="var(--secondary)" />
                                <circle cx="0" cy="-11" r="5" fill="#fff" />
                            </g>

                            <!-- Signal waves from pin -->
                            <circle cx="100" cy="38" r="6" fill="none" stroke="var(--secondary)" stroke-width="1.5" opacity="0.3">
                                <animate attributeName="r" from="6" to="20" dur="2s" repeatCount="indefinite"/>
                                <animate attributeName="opacity" from="0.3" to="0" dur="2s" repeatCount="indefinite"/>
                            </circle>
                        </svg>
                    </div>

                    <!-- Floating stat cards -->
                    <div class="mission-float-card mission-float-card-1">
                        <div class="mission-float-icon" style="background: rgba(var(--primary-rgb), 0.12);">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </div>
                        <div class="mission-float-text">
                            <strong>5,000+</strong>
                            <small>Active Riders</small>
                        </div>
                    </div>

                    <div class="mission-float-card mission-float-card-2">
                        <div class="mission-float-icon" style="background: rgba(var(--secondary-rgb), 0.12);">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--secondary)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        </div>
                        <div class="mission-float-text">
                            <strong>4.8 Rating</strong>
                            <small>User Reviews</small>
                        </div>
                    </div>
                </div>

                <div class="mission-content" data-reveal="right">
                    <span class="section-label">Our Mission</span>
                    <h2 class="section-title">
                        Feel the Difference and Relaxation with <span>Coming Bro</span>
                    </h2>
                    <p class="mission-text">
                        The online cab booking service providers care the price of traveling based on the distance of travel trip and type of car, traffic, and waiting prices. There are numerous apps available in the android play store and apple app store for cab booking in India.
                    </p>
                    <p class="mission-text">
                        Selecting the greatest online cab booking apps in India is tough, which may vary from town to town.
                    </p>
                    <ul class="mission-list">
                        <li>
                            <span class="check-icon">
                                <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                            </span>
                            At vero eos et accusamus et iusto odio
                        </li>
                        <li>
                            <span class="check-icon">
                                <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                            </span>
                            Established fact that a reader will be distracted
                        </li>
                        <li>
                            <span class="check-icon">
                                <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                            </span>
                            Sed ut perspiciatis unde omnis iste natus sit
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         HOW IT WORKS
         ============================================================ -->
    <section class="section how-it-works" id="how-it-works">
        <div class="container">
            <div class="how-header" data-reveal>
                <span class="section-label">How It Works</span>
                <h2 class="section-title">
                    Book Your Ride in <span>3 Simple Steps</span>
                </h2>
                <p class="section-desc">
                    Getting from A to B has never been easier. Here's how ComingBro makes every journey seamless.
                </p>
            </div>

            <div class="how-cards">
                <div class="how-card" data-reveal>
                    <div class="how-card-icon">
                        <svg viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg>
                    </div>
                    <span class="how-card-step">Step 01</span>
                    <h3 class="how-card-title">Safe Guarantee</h3>
                    <p class="how-card-desc">
                        The online cab booking service providers care the price of traveling based on the distance of travel trip and type of car, traffic, and waiting prices.
                    </p>
                </div>

                <div class="how-card" data-reveal>
                    <div class="how-card-icon">
                        <svg viewBox="0 0 24 24"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
                    </div>
                    <span class="how-card-step">Step 02</span>
                    <h3 class="how-card-title">Fast Pickups</h3>
                    <p class="how-card-desc">
                        The online cab booking service providers care the price of traveling based on the distance of travel trip and type of car, traffic, and waiting prices.
                    </p>
                </div>

                <div class="how-card" data-reveal>
                    <div class="how-card-icon">
                        <svg viewBox="0 0 24 24"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/></svg>
                    </div>
                    <span class="how-card-step">Step 03</span>
                    <h3 class="how-card-title">Quick Ride</h3>
                    <p class="how-card-desc">
                        The online cab booking service providers care the price of traveling based on the distance of travel trip and type of car, traffic, and waiting prices.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         FEATURES
         ============================================================ -->
    <section class="section" id="features">
        <div class="container">
            <div class="features-header" data-reveal>
                <span class="section-label">Features</span>
                <h2 class="section-title">
                    Everything You Need for a <span>Perfect Ride</span>
                </h2>
                <p class="section-desc">
                    ComingBro is packed with features designed to make your journey safe, comfortable, and affordable.
                </p>
            </div>

            <div class="features-grid">
                <!-- Live Tracking -->
                <div class="feature-card" data-reveal>
                    <div class="feature-card-icon">
                        <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                    </div>
                    <h3 class="feature-card-title">Live Tracking</h3>
                    <p class="feature-card-desc">
                        Track your ride in real-time on the map. Share your trip with family for added safety and peace of mind.
                    </p>
                </div>

                <!-- Verified Drivers -->
                <div class="feature-card" data-reveal>
                    <div class="feature-card-icon">
                        <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                    </div>
                    <h3 class="feature-card-title">Verified Drivers</h3>
                    <p class="feature-card-desc">
                        Every driver is background-checked and verified. See ratings, reviews and vehicle details before you ride.
                    </p>
                </div>

                <!-- Fair Pricing -->
                <div class="feature-card" data-reveal>
                    <div class="feature-card-icon">
                        <svg viewBox="0 0 24 24"><path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/></svg>
                    </div>
                    <h3 class="feature-card-title">Fair Pricing</h3>
                    <p class="feature-card-desc">
                        Transparent fare estimates before you book. No hidden charges, no surge surprises. Pay what you see.
                    </p>
                </div>

                <!-- Quick Pickup -->
                <div class="feature-card" data-reveal>
                    <div class="feature-card-icon">
                        <svg viewBox="0 0 24 24"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
                    </div>
                    <h3 class="feature-card-title">Quick Pickup</h3>
                    <p class="feature-card-desc">
                        Average pickup time under 5 minutes. Our smart dispatch system matches you with the nearest available driver.
                    </p>
                </div>

                <!-- Multiple Payments -->
                <div class="feature-card" data-reveal>
                    <div class="feature-card-icon">
                        <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/></svg>
                    </div>
                    <h3 class="feature-card-title">Multiple Payments</h3>
                    <p class="feature-card-desc">
                        Pay with cash, UPI, debit/credit cards, or wallets. Choose whatever payment method works best for you.
                    </p>
                </div>

                <!-- 24/7 Support -->
                <div class="feature-card" data-reveal>
                    <div class="feature-card-icon">
                        <svg viewBox="0 0 24 24"><path d="M20 15.5c-1.25 0-2.45-.2-3.57-.57-.35-.11-.74-.03-1.02.24l-2.2 2.2c-2.83-1.44-5.15-3.75-6.59-6.59l2.2-2.21c.28-.26.36-.65.25-1C8.7 6.45 8.5 5.25 8.5 4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1 0 9.39 7.61 17 17 17 .55 0 1-.45 1-1v-3.5c0-.55-.45-1-1-1zM19 12h2c0-4.97-4.03-9-9-9v2c3.87 0 7 3.13 7 7zm-4 0h2c0-2.76-2.24-5-5-5v2c1.66 0 3 1.34 3 3z"/></svg>
                    </div>
                    <h3 class="feature-card-title">24/7 Support</h3>
                    <p class="feature-card-desc">
                        Round-the-clock customer support via phone, chat and email. We're always here when you need us.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         GET APP / DOWNLOAD
         ============================================================ -->
    <section class="section get-app" id="download">
        <div class="get-app-shape get-app-shape-1"></div>
        <div class="get-app-shape get-app-shape-2"></div>
        <div class="get-app-shape get-app-shape-3"></div>

        <div class="container">
            <div class="get-app-content" data-reveal="scale">
                <span class="section-label">Download Now</span>
                <h2 class="get-app-title">
                    Get a Free Coming Bro App from Online Store
                </h2>
                <p class="get-app-desc">
                    We'll go ahead and lay out your dashboard to fit your preferences based on the data you're most interested in.
                </p>
                <div class="get-app-buttons">
                    <a href="#" class="btn-store">
                        <span class="store-icon">
                            <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>
                        </span>
                        <span class="store-text">
                            <small>Download on the</small>
                            <strong>App Store</strong>
                        </span>
                    </a>
                    <a href="#" class="btn-store btn-store-outline">
                        <span class="store-icon">
                            <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M3.609 1.814L13.792 12 3.61 22.186a.996.996 0 0 1-.61-.92V2.734a1 1 0 0 1 .609-.92zm10.89 10.893l2.302 2.302-10.937 6.333 8.635-8.635zm3.199-3.199l2.302 1.327a1 1 0 0 1 0 1.73l-2.302 1.327-2.53-2.53 2.53-2.854zM5.864 2.658L16.8 8.99l-2.302 2.302-8.635-8.635z"/></svg>
                        </span>
                        <span class="store-text">
                            <small>Get it on</small>
                            <strong>Google Play</strong>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         FAQ
         ============================================================ -->
    <section class="section" id="faq">
        <div class="container">
            <div class="faq-header" data-reveal>
                <span class="section-label">FAQ</span>
                <h2 class="section-title">
                    Frequently Asked <span>Questions</span>
                </h2>
                <p class="section-desc">
                    Got questions? We've got answers. Here are the most common things riders ask about ComingBro.
                </p>
            </div>

            <div class="faq-list">
                <div class="faq-item" data-reveal>
                    <button class="faq-question">
                        How do I book a ride on ComingBro?
                        <span class="faq-icon">
                            <svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                        </span>
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-inner">
                            Simply download the ComingBro app from the App Store or Google Play, create your account, enter your pickup and drop location, choose your ride type, and confirm your booking. A nearby driver will be assigned to you within minutes. You can also schedule rides in advance for planned trips.
                        </div>
                    </div>
                </div>

                <div class="faq-item" data-reveal>
                    <button class="faq-question">
                        What payment methods are accepted?
                        <span class="faq-icon">
                            <svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                        </span>
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-inner">
                            ComingBro accepts multiple payment options including cash, UPI (Google Pay, PhonePe, Paytm), credit and debit cards, net banking, and in-app wallet. You can set your preferred payment method in the app settings and change it anytime before or during a ride.
                        </div>
                    </div>
                </div>

                <div class="faq-item" data-reveal>
                    <button class="faq-question">
                        How does ComingBro ensure rider safety?
                        <span class="faq-icon">
                            <svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                        </span>
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-inner">
                            Safety is our top priority. All drivers undergo thorough background verification and vehicle inspections. During your ride, you get real-time GPS tracking, an SOS emergency button, trip-sharing with family or friends, and an in-app call feature so your personal number stays private. Our 24/7 support team monitors trips continuously.
                        </div>
                    </div>
                </div>

                <div class="faq-item" data-reveal>
                    <button class="faq-question">
                        Can I cancel a ride after booking?
                        <span class="faq-icon">
                            <svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                        </span>
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-inner">
                            Yes, you can cancel a ride after booking. If cancelled within 2 minutes of booking or before the driver arrives, there is no cancellation fee. After that, a small cancellation fee may apply to compensate the driver for their time and fuel. You can view the cancellation policy in the app before confirming your ride.
                        </div>
                    </div>
                </div>

                <div class="faq-item" data-reveal>
                    <button class="faq-question">
                        How do I become a ComingBro driver?
                        <span class="faq-icon">
                            <svg viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                        </span>
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-inner">
                            To become a ComingBro driver partner, you need a valid driving licence, vehicle registration certificate, insurance, and an Aadhaar card. Download the ComingBro Driver app, submit your documents for verification, and once approved (usually within 24-48 hours), you can start accepting rides and earning money on your own schedule.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         FOOTER
         ============================================================ -->
    <footer class="footer" id="contact">
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
                        <!-- Facebook -->
                        <a href="#" class="footer-social" aria-label="Facebook">
                            <svg viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <!-- Twitter / X -->
                        <a href="#" class="footer-social" aria-label="Twitter">
                            <svg viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <!-- Instagram -->
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
                        <a href="#how-it-works">How It Works</a>
                        <a href="#features">Features</a>
                        <a href="#download">Download App</a>
                        <a href="/contact">Contact Us</a>
                    </div>
                </div>

                <!-- Legal -->
                <div>
                    <h4 class="footer-heading">Legal</h4>
                    <div class="footer-links">
                        <a href="/privacy-policy">Privacy Policy</a>
                        <a href="/terms-and-conditions">Terms of Service</a>
                        <a href="#">Cookie Policy</a>
                        <a href="#">Refund Policy</a>
                        <a href="#">Driver Agreement</a>
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
                            <a href="tel:{{ $contact['phone'] ?? '+919028777184' }}">{{ $contact['phone'] ?? '+91 9028777184' }}</a>
                        </div>
                    </div>
                    <div class="footer-contact-item">
                        <div class="footer-contact-icon">
                            <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                        </div>
                        <div class="footer-contact-text">
                            <a href="mailto:{{ $contact['email'] ?? 'support@comingbro.in' }}">{{ $contact['email'] ?? 'support@comingbro.in' }}</a>
                        </div>
                    </div>
                    <div class="footer-contact-item">
                        <div class="footer-contact-icon">
                            <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                        </div>
                        <div class="footer-contact-text">
                            {{ $contact['address'] ?? 'India' }}
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
               CUSTOM CURSOR
               -------------------------------------------------------- */
            const cursorDot = document.getElementById('cursorDot');
            const cursorRing = document.getElementById('cursorRing');
            let mouseX = -100, mouseY = -100;
            let ringX = -100, ringY = -100;
            let isTouch = false;

            // Detect touch device
            window.addEventListener('touchstart', function onFirstTouch() {
                isTouch = true;
                cursorDot.style.display = 'none';
                cursorRing.style.display = 'none';
                window.removeEventListener('touchstart', onFirstTouch);
            }, { passive: true });

            document.addEventListener('mousemove', function(e) {
                if (isTouch) return;
                mouseX = e.clientX;
                mouseY = e.clientY;
                cursorDot.style.left = mouseX - 4 + 'px';
                cursorDot.style.top = mouseY - 4 + 'px';
            });

            function animateRing() {
                if (!isTouch) {
                    ringX += (mouseX - ringX) * 0.15;
                    ringY += (mouseY - ringY) * 0.15;
                    cursorRing.style.left = ringX - 18 + 'px';
                    cursorRing.style.top = ringY - 18 + 'px';
                }
                requestAnimationFrame(animateRing);
            }
            requestAnimationFrame(animateRing);

            // Hover state for interactive elements
            const hoverTargets = document.querySelectorAll('a, button, .faq-question, .how-card, .feature-card');
            hoverTargets.forEach(function(el) {
                el.addEventListener('mouseenter', function() {
                    cursorDot.classList.add('hover');
                    cursorRing.classList.add('hover');
                });
                el.addEventListener('mouseleave', function() {
                    cursorDot.classList.remove('hover');
                    cursorRing.classList.remove('hover');
                });
            });

            /* --------------------------------------------------------
               NAVBAR SCROLL
               -------------------------------------------------------- */
            const navbar = document.getElementById('navbar');
            let lastScrollY = 0;

            window.addEventListener('scroll', function() {
                var sy = window.scrollY || window.pageYOffset;
                if (sy > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
                lastScrollY = sy;
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

            // Close mobile menu on link click
            var mobileLinks = mobileMenu.querySelectorAll('a');
            mobileLinks.forEach(function(link) {
                link.addEventListener('click', function() {
                    hamburger.classList.remove('active');
                    mobileMenu.classList.remove('open');
                    document.body.style.overflow = '';
                });
            });

            /* --------------------------------------------------------
               SMOOTH SCROLL
               -------------------------------------------------------- */
            document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
                anchor.addEventListener('click', function(e) {
                    var target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        e.preventDefault();
                        var offset = navbar.offsetHeight + 10;
                        var targetPos = target.getBoundingClientRect().top + window.pageYOffset - offset;
                        window.scrollTo({
                            top: targetPos,
                            behavior: 'smooth'
                        });
                    }
                });
            });

            /* --------------------------------------------------------
               SCROLL REVEAL (IntersectionObserver)
               -------------------------------------------------------- */
            var revealElements = document.querySelectorAll('[data-reveal]');

            if ('IntersectionObserver' in window) {
                var revealObserver = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('revealed');
                            revealObserver.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.12,
                    rootMargin: '0px 0px -40px 0px'
                });

                revealElements.forEach(function(el) {
                    revealObserver.observe(el);
                });
            } else {
                // Fallback: show everything
                revealElements.forEach(function(el) {
                    el.classList.add('revealed');
                });
            }

            /* --------------------------------------------------------
               FAQ ACCORDION
               -------------------------------------------------------- */
            var faqQuestions = document.querySelectorAll('.faq-question');

            faqQuestions.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var item = btn.closest('.faq-item');
                    var answer = item.querySelector('.faq-answer');
                    var isOpen = item.classList.contains('active');

                    // Close all others
                    document.querySelectorAll('.faq-item.active').forEach(function(openItem) {
                        if (openItem !== item) {
                            openItem.classList.remove('active');
                            openItem.querySelector('.faq-answer').style.maxHeight = null;
                        }
                    });

                    // Toggle current
                    if (isOpen) {
                        item.classList.remove('active');
                        answer.style.maxHeight = null;
                    } else {
                        item.classList.add('active');
                        answer.style.maxHeight = answer.scrollHeight + 'px';
                    }
                });
            });

        })();
    </script>
</body>
</html>
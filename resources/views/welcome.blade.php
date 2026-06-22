@extends('layouts.frontend')

@section('title', 'ComingBro — Move through the city, effortlessly')
@section('meta_description', 'ComingBro is India\'s premium ride-hailing platform. Book city rides, intercity travel and freight in seconds — safe, fairly priced, and always on time.')

@section('styles')
    /* ============================================================
       HERO
       ============================================================ */
    .hero { position:relative; padding:180px 0 120px; overflow:hidden; }
    .hero-grid { display:grid; grid-template-columns:1.05fr 0.95fr; gap:64px; align-items:center; }
    .hero h1 {
        font-size:clamp(2.6rem,6vw,4.6rem); line-height:1.02; margin:24px 0 22px;
    }
    .hero p.lead { color:var(--text-soft); font-size:1.18rem; max-width:520px; margin-bottom:34px; }
    .hero-ctas { display:flex; flex-wrap:wrap; gap:14px; margin-bottom:40px; }
    .hero-trust { display:flex; align-items:center; gap:16px; }
    .hero-avatars { display:flex; }
    .hero-avatars span {
        width:42px; height:42px; border-radius:50%; display:flex; align-items:center; justify-content:center;
        font-size:.78rem; font-weight:700; color:#fff; font-family:'Space Grotesk',sans-serif;
        border:2px solid var(--bg); margin-left:-12px; background:var(--grad);
    }
    .hero-avatars span:first-child { margin-left:0; }
    .hero-avatars span:nth-child(2){ background:linear-gradient(135deg,#5b8cff,#9b6bff); }
    .hero-avatars span:nth-child(3){ background:linear-gradient(135deg,#19e0c8,#18b9e8); }
    .hero-avatars span:nth-child(4){ background:linear-gradient(135deg,#ffa14a,#ff6b6b); }
    .hero-avatars span:nth-child(5){ background:linear-gradient(135deg,var(--secondary),var(--primary)); font-size:.68rem; }
    .hero-trust-text { font-size:.9rem; color:var(--text-soft); }
    .hero-trust-text strong { color:var(--text); }
    .hero-stars { color:#ffb547; letter-spacing:2px; font-size:.85rem; }

    /* Device mockup */
    .hero-visual { position:relative; display:flex; justify-content:center; }
    .device {
        position:relative; width:300px; aspect-ratio:300/610; border-radius:42px;
        background:linear-gradient(160deg,#0e1626,#070b15); border:1px solid var(--border-strong);
        box-shadow:0 40px 100px -30px rgba(0,0,0,0.8), 0 0 0 8px rgba(255,255,255,0.02);
        padding:12px; z-index:2;
    }
    .device-screen {
        width:100%; height:100%; border-radius:32px; overflow:hidden; position:relative;
        background:radial-gradient(120% 80% at 50% 0%, #102338, #060b14);
        display:flex; flex-direction:column;
    }
    .device-notch { position:absolute; top:10px; left:50%; transform:translateX(-50%); width:96px; height:22px; border-radius:14px; background:#05080f; z-index:5; }
    .map-zone { flex:1; position:relative; background:
        linear-gradient(90deg, rgba(255,255,255,0.04) 1px, transparent 1px) 0 0/40px 40px,
        linear-gradient(0deg, rgba(255,255,255,0.04) 1px, transparent 1px) 0 0/40px 40px,
        radial-gradient(80% 60% at 50% 30%, rgba(24,185,232,0.16), transparent 70%); }
    .map-route { position:absolute; left:24%; top:22%; width:52%; height:46%; border-left:2.5px dashed var(--primary); border-bottom:2.5px dashed var(--primary); border-radius:0 0 0 28px; opacity:.8; }
    .map-pin { position:absolute; width:16px; height:16px; border-radius:50%; transform:translate(-50%,-50%); }
    .map-pin.start { left:24%; top:22%; background:var(--accent); box-shadow:0 0 0 6px rgba(25,224,200,0.18); }
    .map-pin.end { left:76%; top:68%; background:#ff6b6b; box-shadow:0 0 0 6px rgba(255,107,107,0.18); }
    .map-car {
        position:absolute; left:50%; top:45%; transform:translate(-50%,-50%);
        width:34px; height:34px; border-radius:11px; background:var(--grad);
        display:flex; align-items:center; justify-content:center; color:#fff;
        box-shadow:0 8px 22px -4px rgba(var(--primary-rgb),0.7); animation:carfloat 3.5s var(--ease) infinite;
    }
    .map-car svg { width:18px; height:18px; }
    @keyframes carfloat { 0%,100%{ transform:translate(-50%,-50%);} 50%{ transform:translate(-46%,-58%);} }
    .device-sheet { background:var(--bg-elev); border-top:1px solid var(--border); padding:16px 16px 20px; }
    .sheet-handle { width:38px; height:4px; border-radius:4px; background:var(--border-strong); margin:0 auto 14px; }
    .sheet-row { display:flex; align-items:center; gap:11px; margin-bottom:12px; }
    .sheet-ico { width:34px; height:34px; border-radius:10px; background:var(--bg-card); border:1px solid var(--border); display:flex; align-items:center; justify-content:center; }
    .sheet-ico svg { width:16px; height:16px; stroke:var(--primary); }
    .sheet-row .t { font-size:.78rem; font-weight:600; }
    .sheet-row .s { font-size:.66rem; color:var(--text-dim); }
    .sheet-cta { margin-top:6px; background:var(--grad); color:#fff; text-align:center; padding:11px; border-radius:12px; font-weight:600; font-size:.82rem; font-family:'Space Grotesk',sans-serif; }

    /* Floating chips */
    .float-chip {
        position:absolute; z-index:3; display:flex; align-items:center; gap:10px;
        background:var(--glass); backdrop-filter:blur(14px); border:1px solid var(--border-strong);
        padding:11px 15px; border-radius:15px; box-shadow:var(--shadow-card);
    }
    .float-chip .ic { width:34px; height:34px; border-radius:10px; display:flex; align-items:center; justify-content:center; }
    .float-chip .ic svg { width:18px; height:18px; }
    .float-chip .tt { font-size:.82rem; font-weight:700; font-family:'Space Grotesk',sans-serif; }
    .float-chip .ss { font-size:.66rem; color:var(--text-dim); }
    .chip-1 { left:-26px; top:18%; animation:floaty 5s var(--ease) infinite; }
    .chip-2 { right:-22px; bottom:16%; animation:floaty 6s var(--ease) infinite reverse; }
    @keyframes floaty { 0%,100%{ transform:translateY(0);} 50%{ transform:translateY(-14px);} }

    /* ============================================================
       TRUST BAR
       ============================================================ */
    .trust-bar { border-top:1px solid var(--border); border-bottom:1px solid var(--border); padding:30px 0; }
    .trust-inner { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:30px; }
    .trust-item { text-align:center; flex:1; min-width:130px; }
    .trust-item .n { font-family:'Space Grotesk',sans-serif; font-size:2rem; font-weight:700; }
    .trust-item .l { font-size:.82rem; color:var(--text-dim); margin-top:2px; }

    /* ============================================================
       BENTO SERVICES
       ============================================================ */
    .bento { display:grid; grid-template-columns:repeat(6,1fr); gap:20px; }
    .bento .card { padding:30px; display:flex; flex-direction:column; }
    .b-lg { grid-column:span 4; min-height:280px; }
    .b-sm { grid-column:span 2; }
    .b-md { grid-column:span 3; }
    .svc-ico { width:50px; height:50px; border-radius:14px; display:flex; align-items:center; justify-content:center; margin-bottom:18px; background:var(--bg-card-hover); border:1px solid var(--border); }
    .svc-ico svg { width:24px; height:24px; stroke:var(--primary); fill:none; }
    .card h3 { font-size:1.3rem; margin-bottom:9px; }
    .card p { color:var(--text-soft); font-size:.94rem; }
    .b-lg .bento-art { margin-top:auto; padding-top:24px; display:flex; gap:10px; flex-wrap:wrap; }
    .pill { font-size:.74rem; font-weight:600; color:var(--text-soft); padding:7px 13px; border-radius:999px; border:1px solid var(--border); background:var(--bg-card); }

    /* ============================================================
       HOW IT WORKS
       ============================================================ */
    .steps { display:grid; grid-template-columns:repeat(3,1fr); gap:24px; }
    .step { padding:34px 28px; }
    .step .num {
        font-family:'Space Grotesk',sans-serif; font-size:.9rem; font-weight:700; color:var(--primary);
        width:44px; height:44px; border-radius:13px; display:flex; align-items:center; justify-content:center;
        background:var(--bg-card-hover); border:1px solid var(--border-strong); margin-bottom:20px;
    }
    .step h3 { font-size:1.18rem; margin-bottom:8px; }
    .step p { color:var(--text-soft); font-size:.92rem; }

    /* ============================================================
       FEATURES SPLIT
       ============================================================ */
    .feat-grid { display:grid; grid-template-columns:1fr 1fr; gap:60px; align-items:center; }
    .feat-list { display:flex; flex-direction:column; gap:22px; }
    .feat-item { display:flex; gap:16px; }
    .feat-item .fi { width:46px; height:46px; min-width:46px; border-radius:13px; display:flex; align-items:center; justify-content:center; background:var(--grad); }
    .feat-item .fi svg { width:22px; height:22px; stroke:#fff; fill:none; }
    .feat-item h3 { font-size:1.1rem; margin-bottom:5px; }
    .feat-item p { color:var(--text-soft); font-size:.92rem; }
    .feat-visual { position:relative; border-radius:var(--radius); overflow:hidden; border:1px solid var(--border); background:var(--bg-card); aspect-ratio:1/1; box-shadow:var(--shadow-card); }
    .feat-visual .glowmap { position:absolute; inset:0; background:
        linear-gradient(90deg, rgba(255,255,255,0.04) 1px, transparent 1px) 0 0/52px 52px,
        linear-gradient(0deg, rgba(255,255,255,0.04) 1px, transparent 1px) 0 0/52px 52px,
        radial-gradient(60% 50% at 60% 40%, rgba(24,185,232,0.20), transparent 70%),
        radial-gradient(50% 40% at 30% 80%, rgba(91,140,255,0.16), transparent 70%); }
    .feat-badge { position:absolute; display:flex; align-items:center; gap:10px; background:var(--glass); backdrop-filter:blur(14px); border:1px solid var(--border-strong); padding:12px 16px; border-radius:14px; box-shadow:var(--shadow-card); }
    .feat-badge svg { width:20px; height:20px; fill:none; }
    .feat-badge .bt { font-size:.84rem; font-weight:700; font-family:'Space Grotesk',sans-serif; }
    .feat-badge .bs { font-size:.66rem; color:var(--text-dim); }

    /* ============================================================
       DOWNLOAD CTA
       ============================================================ */
    .cta-wrap { position:relative; border-radius:30px; overflow:hidden; border:1px solid var(--border-strong);
        background:linear-gradient(140deg, rgba(24,185,232,0.16), rgba(91,140,255,0.12)); padding:70px 56px; }
    .cta-wrap::after { content:''; position:absolute; inset:0; z-index:0; background:radial-gradient(50% 80% at 85% 20%, var(--glow-1), transparent 60%); }
    .cta-inner { position:relative; z-index:1; max-width:620px; }
    .cta-inner h2 { font-size:clamp(2rem,4vw,2.9rem); margin:14px 0 16px; }
    .cta-inner p { color:var(--text-soft); font-size:1.05rem; margin-bottom:30px; }

    /* ============================================================
       FAQ
       ============================================================ */
    .faq-list { max-width:760px; margin:0 auto; display:flex; flex-direction:column; gap:14px; }
    .faq { border:1px solid var(--border); border-radius:var(--radius-sm); background:var(--bg-card); overflow:hidden; transition:border-color .3s var(--ease); }
    .faq.open { border-color:var(--ring); }
    .faq-q { width:100%; display:flex; align-items:center; justify-content:space-between; gap:16px; padding:20px 24px; font-weight:600; font-size:1rem; text-align:left; font-family:'Space Grotesk',sans-serif; }
    .faq-q .chev { width:22px; height:22px; transition:transform .3s var(--ease); flex-shrink:0; stroke:var(--primary); fill:none; }
    .faq.open .chev { transform:rotate(180deg); }
    .faq-a { max-height:0; overflow:hidden; transition:max-height .35s var(--ease); }
    .faq-a p { padding:0 24px 22px; color:var(--text-soft); font-size:.94rem; }

    /* ============================================================
       RESPONSIVE
       ============================================================ */
    @media (max-width:960px){
        .hero-grid { grid-template-columns:1fr; gap:60px; }
        .hero p.lead { margin-left:auto; margin-right:auto; }
        .hero { padding:150px 0 90px; text-align:center; }
        .hero-ctas, .hero-trust, .hero-copy .eyebrow { justify-content:center; }
        .hero-trust { flex-direction:column; }
        .feat-grid { grid-template-columns:1fr; gap:40px; }
        .steps { grid-template-columns:1fr; }
        .bento { grid-template-columns:1fr 1fr; }
        .b-lg,.b-md,.b-sm { grid-column:span 2; }
    }
    @media (max-width:560px){
        .bento { grid-template-columns:1fr; }
        .b-lg,.b-md,.b-sm { grid-column:span 1; }
        .cta-wrap { padding:48px 26px; }
        .trust-item { min-width:45%; }
        .chip-1 { left:-8px; } .chip-2 { right:-8px; }
    }
@endsection

@section('content')
    {{-- ====================== HERO ====================== --}}
    <section class="hero" id="hero">
        <div class="container">
            <div class="hero-grid">
                <div class="hero-copy" data-reveal="left">
                    <span class="eyebrow">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        Rated 4.8 by 50,000+ riders
                    </span>
                    <h1>Move through the city,<br><span class="grad-text">effortlessly.</span></h1>
                    <p class="lead">Tap once and go. ComingBro connects you with nearby drivers in seconds — for city rides, intercity trips and freight — at fares you can trust, every single time.</p>

                    <div class="hero-ctas">
                        <a href="#" class="btn-store">
                            <svg viewBox="0 0 24 24"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>
                            <span class="store-text"><small>Download on the</small><strong>App Store</strong></span>
                        </a>
                        <a href="#" class="btn-store">
                            <svg viewBox="0 0 24 24"><path d="M3.609 1.814L13.792 12 3.61 22.186a.996.996 0 0 1-.61-.92V2.734a1 1 0 0 1 .609-.92zm10.89 10.893l2.302 2.302-10.937 6.333 8.635-8.635zm3.199-3.199l2.302 1.327a1 1 0 0 1 0 1.73l-2.302 1.327-2.53-2.53 2.53-2.854zM5.864 2.658L16.8 8.99l-2.302 2.302-8.635-8.635z"/></svg>
                            <span class="store-text"><small>Get it on</small><strong>Google Play</strong></span>
                        </a>
                    </div>

                    <div class="hero-trust">
                        <div class="hero-avatars">
                            <span>AK</span><span>PR</span><span>SM</span><span>VJ</span><span>+5k</span>
                        </div>
                        <div class="hero-trust-text">
                            <div class="hero-stars">★★★★★</div>
                            <strong>50,000+</strong> happy riders across India
                        </div>
                    </div>
                </div>

                {{-- Device mockup --}}
                <div class="hero-visual" data-reveal="right">
                    <div class="float-chip chip-1">
                        <span class="ic" style="background:rgba(25,224,200,0.14)"><svg viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"/></svg></span>
                        <div><div class="tt">Driver arriving</div><div class="ss">2 min away</div></div>
                    </div>
                    <div class="float-chip chip-2">
                        <span class="ic" style="background:rgba(var(--primary-rgb),0.14)"><svg viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></span>
                        <div><div class="tt">₹148</div><div class="ss">upfront fare</div></div>
                    </div>

                    <div class="device">
                        <div class="device-notch"></div>
                        <div class="device-screen">
                            <div class="map-zone">
                                <div class="map-route"></div>
                                <div class="map-pin start"></div>
                                <div class="map-pin end"></div>
                                <div class="map-car">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 17H3v-5l2-5h11l3 5h2v5h-2"/><circle cx="7.5" cy="17" r="2"/><circle cx="16.5" cy="17" r="2"/></svg>
                                </div>
                            </div>
                            <div class="device-sheet">
                                <div class="sheet-handle"></div>
                                <div class="sheet-row">
                                    <span class="sheet-ico"><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="10" r="3"/><path d="M12 2a8 8 0 0 0-8 8c0 5.25 8 12 8 12s8-6.75 8-12a8 8 0 0 0-8-8z"/></svg></span>
                                    <div><div class="t">MG Road, Bengaluru</div><div class="s">Pickup</div></div>
                                </div>
                                <div class="sheet-row">
                                    <span class="sheet-ico"><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></span>
                                    <div><div class="t">Kempegowda Airport</div><div class="s">Dropoff · 38 km</div></div>
                                </div>
                                <div class="sheet-cta">Confirm ComingBro · ₹148</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ====================== TRUST BAR ====================== --}}
    <div class="trust-bar">
        <div class="container">
            <div class="trust-inner" data-reveal>
                <div class="trust-item"><div class="n grad-text">50K+</div><div class="l">Active riders</div></div>
                <div class="trust-item"><div class="n grad-text">2.5K+</div><div class="l">Verified drivers</div></div>
                <div class="trust-item"><div class="n grad-text">120+</div><div class="l">Cities served</div></div>
                <div class="trust-item"><div class="n grad-text">4.8★</div><div class="l">Average rating</div></div>
                <div class="trust-item"><div class="n grad-text">99.2%</div><div class="l">On-time pickups</div></div>
            </div>
        </div>
    </div>

    {{-- ====================== SERVICES (BENTO) ====================== --}}
    <section class="section" id="services">
        <div class="container">
            <div class="section-head" data-reveal>
                <span class="eyebrow">What we move</span>
                <h2>One app for every journey</h2>
                <p>City hops, long-distance runs, or sending a package across town — ComingBro has a ride for it.</p>
            </div>
            <div class="bento">
                <div class="card b-lg" data-reveal>
                    <div class="svc-ico"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 17H3v-5l2-5h11l3 5h2v5h-2"/><circle cx="7.5" cy="17" r="2"/><circle cx="16.5" cy="17" r="2"/></svg></div>
                    <h3>City Rides</h3>
                    <p>Hatchbacks, sedans and SUVs at your doorstep in minutes. Upfront fares, live tracking and verified drivers — for the daily commute or the late-night ride home.</p>
                    <div class="bento-art">
                        <span class="pill">Mini</span><span class="pill">Sedan</span><span class="pill">SUV</span><span class="pill">AC / Non-AC</span><span class="pill">Auto</span>
                    </div>
                </div>
                <div class="card b-sm" data-reveal>
                    <div class="svc-ico"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12h18M3 12l4-7h10l4 7M3 12v6h2M21 12v6h-2M7 18h10"/></svg></div>
                    <h3>Intercity</h3>
                    <p>One-way or round-trip travel between cities, at fixed, transparent prices.</p>
                </div>
                <div class="card b-sm" data-reveal>
                    <div class="svc-ico"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><path d="M16 8h4l3 3v5h-7M5.5 18.5a2 2 0 1 0 0 .01M18.5 18.5a2 2 0 1 0 0 .01"/></svg></div>
                    <h3>Freight</h3>
                    <p>Move parcels and cargo with tempo and mini-truck options on demand.</p>
                </div>
                <div class="card b-md" data-reveal>
                    <div class="svc-ico"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/></svg></div>
                    <h3>Safety, built in</h3>
                    <p>Live trip sharing, an in-app SOS button and 24/7 support keep every journey accountable from pickup to drop.</p>
                </div>
                <div class="card b-md" data-reveal>
                    <div class="svc-ico"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 1 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78z"/></svg></div>
                    <h3>Rewards &amp; wallet</h3>
                    <p>Top up once, ride anywhere. Earn referral credits and unlock loyalty perks the more you travel.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ====================== HOW IT WORKS ====================== --}}
    <section class="section" id="how" style="background:var(--bg-elev);">
        <div class="container">
            <div class="section-head" data-reveal>
                <span class="eyebrow">How it works</span>
                <h2>Your ride, in three taps</h2>
                <p>No haggling, no surprises. Just open the app and go.</p>
            </div>
            <div class="steps">
                <div class="card step" data-reveal>
                    <div class="num">01</div>
                    <h3>Set your destination</h3>
                    <p>Enter where you're headed and instantly see an upfront fare for every ride type — before you book.</p>
                </div>
                <div class="card step" data-reveal>
                    <div class="num">02</div>
                    <h3>Get matched instantly</h3>
                    <p>We connect you with the nearest verified driver and share their live location, car and rating with you.</p>
                </div>
                <div class="card step" data-reveal>
                    <div class="num">03</div>
                    <h3>Ride &amp; pay your way</h3>
                    <p>Track the whole trip in real time, then pay by wallet, UPI, card or cash. Rate, and you're done.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ====================== FEATURES SPLIT ====================== --}}
    <section class="section" id="features">
        <div class="container">
            <div class="feat-grid">
                <div data-reveal="left">
                    <span class="eyebrow">Why ComingBro</span>
                    <h2 style="font-size:clamp(1.9rem,4vw,2.8rem); margin:18px 0 30px;">Built for trust, tuned for speed</h2>
                    <div class="feat-list">
                        <div class="feat-item">
                            <span class="fi"><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></span>
                            <div><h3>Fares you see first</h3><p>Know the exact price before you confirm. No meter anxiety, no surge surprises.</p></div>
                        </div>
                        <div class="feat-item">
                            <span class="fi"><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></span>
                            <div><h3>Verified drivers only</h3><p>Every driver is background-checked and document-verified before their first trip.</p></div>
                        </div>
                        <div class="feat-item">
                            <span class="fi"><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg></span>
                            <div><h3>Support that answers</h3><p>Real humans, available 24/7, plus an in-app SOS that loops in your trusted contacts.</p></div>
                        </div>
                        <div class="feat-item">
                            <span class="fi"><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg></span>
                            <div><h3>Lightning-fast matching</h3><p>Smart dispatch finds the closest driver in seconds, so you're moving sooner.</p></div>
                        </div>
                    </div>
                </div>
                <div class="feat-visual" data-reveal="right">
                    <div class="glowmap"></div>
                    <div class="feat-badge" style="top:22px; left:22px;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"/></svg>
                        <div><div class="bt">Trip shared</div><div class="bs">with 2 contacts</div></div>
                    </div>
                    <div class="feat-badge" style="bottom:24px; right:24px;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                        <div><div class="bt">ETA 4 min</div><div class="bs">live tracking on</div></div>
                    </div>
                    <div class="feat-badge" style="top:46%; left:50%; transform:translate(-50%,-50%);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="var(--secondary)" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 17H3v-5l2-5h11l3 5h2v5h-2"/><circle cx="7.5" cy="17" r="2"/><circle cx="16.5" cy="17" r="2"/></svg>
                        <div><div class="bt">Driver on the way</div><div class="bs">KA 01 · 4.9★</div></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ====================== DOWNLOAD CTA ====================== --}}
    <section class="section" id="download" style="padding-top:0;">
        <div class="container">
            <div class="cta-wrap" data-reveal>
                <div class="cta-inner">
                    <span class="eyebrow">Get the app</span>
                    <h2>Your next ride is one tap away</h2>
                    <p>Download ComingBro free and join 50,000+ riders moving smarter across India. Available on iOS and Android.</p>
                    <div class="store-row">
                        <a href="#" class="btn-store">
                            <svg viewBox="0 0 24 24"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>
                            <span class="store-text"><small>Download on the</small><strong>App Store</strong></span>
                        </a>
                        <a href="#" class="btn-store">
                            <svg viewBox="0 0 24 24"><path d="M3.609 1.814L13.792 12 3.61 22.186a.996.996 0 0 1-.61-.92V2.734a1 1 0 0 1 .609-.92zm10.89 10.893l2.302 2.302-10.937 6.333 8.635-8.635zm3.199-3.199l2.302 1.327a1 1 0 0 1 0 1.73l-2.302 1.327-2.53-2.53 2.53-2.854zM5.864 2.658L16.8 8.99l-2.302 2.302-8.635-8.635z"/></svg>
                            <span class="store-text"><small>Get it on</small><strong>Google Play</strong></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ====================== FAQ ====================== --}}
    <section class="section" id="faq" style="padding-top:0;">
        <div class="container">
            <div class="section-head" data-reveal>
                <span class="eyebrow">FAQ</span>
                <h2>Good to know</h2>
                <p>Everything you might want to ask before your first ride.</p>
            </div>
            <div class="faq-list" data-reveal>
                <div class="faq">
                    <button class="faq-q">How do I book a ride? <svg class="chev" viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg></button>
                    <div class="faq-a"><p>Download the app, set your pickup and destination, choose a ride type and tap confirm. You'll be matched with the nearest driver in seconds and can track them all the way to you.</p></div>
                </div>
                <div class="faq">
                    <button class="faq-q">Are the fares fixed upfront? <svg class="chev" viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg></button>
                    <div class="faq-a"><p>Yes. You see the exact fare before you confirm your ride, calculated from distance, vehicle type and live demand — no hidden charges at the end of the trip.</p></div>
                </div>
                <div class="faq">
                    <button class="faq-q">How are drivers verified? <svg class="chev" viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg></button>
                    <div class="faq-a"><p>Every ComingBro driver completes document verification and a background check, and uploads valid licence, registration and insurance before they can accept their first trip.</p></div>
                </div>
                <div class="faq">
                    <button class="faq-q">What payment methods can I use? <svg class="chev" viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg></button>
                    <div class="faq-a"><p>Pay however suits you — in-app wallet, UPI, debit or credit card, or cash directly to the driver at the end of the ride.</p></div>
                </div>
                <div class="faq">
                    <button class="faq-q">Can I become a ComingBro driver? <svg class="chev" viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg></button>
                    <div class="faq-a"><p>Absolutely. Download the driver app, complete your profile and document verification, and start earning on your own schedule. Reach out via our <a href="/contact" style="color:var(--primary)">contact page</a> to learn more.</p></div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.faq-q').forEach(function(btn){
        btn.addEventListener('click', function(){
            var faq = btn.closest('.faq');
            var ans = faq.querySelector('.faq-a');
            var isOpen = faq.classList.contains('open');
            document.querySelectorAll('.faq.open').forEach(function(f){
                f.classList.remove('open'); f.querySelector('.faq-a').style.maxHeight = null;
            });
            if (!isOpen){ faq.classList.add('open'); ans.style.maxHeight = ans.scrollHeight + 'px'; }
        });
    });
</script>
@endsection

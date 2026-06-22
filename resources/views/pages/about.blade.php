@extends('layouts.frontend')

@section('title', 'About Us — ComingBro')
@section('meta_description', 'Learn about ComingBro — committed to making a positive impact by tackling unemployment and improving transportation access across India.')

@section('styles')
    .page-hero { padding:0 0 8px; text-align:center; }
    .page-hero h1 { font-size:clamp(2.4rem,5vw,3.6rem); margin-bottom:16px; }
    .page-hero p { color:var(--text-soft); font-size:1.1rem; max-width:560px; margin:0 auto; }

    .about-story { max-width:820px; margin:64px auto 0; }
    .about-story .card { padding:44px; }
    .about-story h2 { font-size:1.8rem; margin-bottom:14px; }
    .about-story .gradient-line { width:70px; height:4px; border-radius:4px; background:var(--grad); margin-bottom:22px; }
    .about-text, .about-text p { color:var(--text-soft); font-size:1.02rem; line-height:1.8; }
    .about-text p + p { margin-top:14px; }

    .values-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:22px; margin-top:56px; }
    .value-card { padding:32px; }
    .value-icon { width:56px; height:56px; border-radius:15px; background:var(--grad); display:flex; align-items:center; justify-content:center; margin-bottom:20px; }
    .value-icon svg { width:26px; height:26px; fill:none; stroke:#fff; stroke-width:1.8; }
    .value-card h3 { font-size:1.2rem; margin-bottom:8px; }
    .value-card p { color:var(--text-soft); font-size:.93rem; }

    @media (max-width:860px){ .values-grid { grid-template-columns:1fr; } .about-story .card { padding:32px; } }
@endsection

@section('content')
<div class="page-pad">
    <section class="section" style="padding-top:0;">
        <div class="container">
            <div class="page-hero" data-reveal>
                <span class="eyebrow">Our story</span>
                <h1>Driven to move <span class="grad-text">India forward</span></h1>
                <p>Empowering communities with smarter, safer and more accessible transportation — one ride at a time.</p>
            </div>

            <div class="about-story" data-reveal>
                <div class="card">
                    <h2>Who we are</h2>
                    <div class="gradient-line"></div>
                    <div class="about-text">
                        @if(isset($about) && $about->description)
                            {!! strip_tags($about->description, '<p><b><strong><br><em><ul><li>') !!}
                        @else
                            <p>We represent the spirit of India's youth — committed to making a positive impact by tackling unemployment and improving transportation access across the country's underserved places. Through ComingBro, we've made a bold move to extend financial opportunity to thousands of drivers while solving everyday transport needs with a swift, straightforward solution.</p>
                            <p>What started as a simple idea has grown into a platform that connects riders and drivers across more than a hundred cities — built on trust, fair pricing and technology that just works.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="section-head" style="margin-top:96px;" data-reveal>
                <span class="eyebrow">What we stand for</span>
                <h2>Our core values</h2>
                <p>The principles that guide every decision we make at ComingBro.</p>
            </div>
            <div class="values-grid">
                <div class="card value-card" data-reveal>
                    <div class="value-icon"><svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a7 7 0 0 0-7 7c0 2.38 1.19 4.47 3 5.74V17a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-2.26c1.81-1.27 3-3.36 3-5.74a7 7 0 0 0-7-7z"/><line x1="9" y1="21" x2="15" y2="21"/></svg></div>
                    <h3>Innovation</h3>
                    <p>We use cutting-edge technology to create seamless ride experiences and solve real transportation challenges across India.</p>
                </div>
                <div class="card value-card" data-reveal>
                    <div class="value-icon"><svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
                    <h3>Safety</h3>
                    <p>Your safety comes first. From verified drivers to real-time tracking, every feature is built with your security in mind.</p>
                </div>
                <div class="card value-card" data-reveal>
                    <div class="value-icon"><svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M2 12h20"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg></div>
                    <h3>Accessibility</h3>
                    <p>Affordable rides for everyone, everywhere. We're committed to reaching underserved communities and bridging the transport gap.</p>
                </div>
            </div>

            <div class="cta-wrap" style="margin-top:96px; border-radius:24px; border:1px solid var(--border-strong); position:relative; overflow:hidden; background:linear-gradient(140deg, rgba(24,185,232,0.16), rgba(91,140,255,0.12)); padding:56px; text-align:center;" data-reveal>
                <div style="position:relative; z-index:1; max-width:560px; margin:0 auto;">
                    <h2 style="font-size:clamp(1.7rem,4vw,2.4rem); margin-bottom:14px;">Have questions? Get in touch</h2>
                    <p style="color:var(--text-soft); margin-bottom:26px;">We'd love to hear from you. Reach out and let us know how we can help.</p>
                    <a href="/contact" class="btn btn-primary"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg> Contact us</a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

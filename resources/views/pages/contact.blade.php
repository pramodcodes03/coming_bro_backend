@extends('layouts.frontend')

@section('title', 'Contact Us — ComingBro')
@section('meta_description', 'Get in touch with ComingBro. We\'re here to help with questions about rides, partnerships and driving with us.')

@section('styles')
    .page-hero { padding:0 0 8px; text-align:center; }
    .page-hero h1 { font-size:clamp(2.4rem,5vw,3.6rem); margin-bottom:16px; }
    .page-hero p { color:var(--text-soft); font-size:1.1rem; max-width:560px; margin:0 auto; }

    .contact-grid { display:grid; grid-template-columns:0.85fr 1.15fr; gap:30px; margin-top:60px; align-items:start; }
    .contact-info { display:flex; flex-direction:column; gap:16px; }
    .info-card { display:flex; align-items:center; gap:16px; padding:22px 24px; }
    .info-ic { width:50px; height:50px; min-width:50px; border-radius:14px; background:var(--grad); display:flex; align-items:center; justify-content:center; }
    .info-ic svg { width:22px; height:22px; fill:none; stroke:#fff; stroke-width:1.9; }
    .info-card h3 { font-size:.78rem; text-transform:uppercase; letter-spacing:.1em; color:var(--text-dim); margin-bottom:3px; }
    .info-card a, .info-card p { font-size:1.02rem; font-weight:600; color:var(--text); }
    .info-card a:hover { color:var(--primary); }

    .form-card { padding:38px; }
    .form-card h2 { font-size:1.6rem; margin-bottom:6px; }
    .form-card .subtitle { color:var(--text-soft); font-size:.95rem; margin-bottom:26px; }
    .form-row { display:grid; grid-template-columns:1fr 1fr; gap:18px; }
    .form-group { margin-bottom:18px; }
    .form-group label { display:block; font-size:.84rem; font-weight:600; margin-bottom:8px; color:var(--text-soft); }
    .form-group input, .form-group textarea {
        width:100%; padding:13px 16px; border-radius:13px; font-family:inherit; font-size:.95rem;
        background:var(--bg-card); border:1px solid var(--border-strong); color:var(--text);
        transition:border-color .25s var(--ease), background .25s var(--ease);
    }
    .form-group input::placeholder, .form-group textarea::placeholder { color:var(--text-dim); }
    .form-group input:focus, .form-group textarea:focus { outline:none; border-color:var(--primary); background:var(--bg-card-hover); box-shadow:0 0 0 3px var(--ring); }
    .form-group textarea { resize:vertical; min-height:130px; }
    .form-note { margin-top:14px; font-size:.82rem; color:var(--text-dim); }

    .map-wrapper { margin-top:30px; border-radius:var(--radius); overflow:hidden; border:1px solid var(--border); box-shadow:var(--shadow-card); }
    .map-wrapper iframe { width:100%; height:380px; border:0; display:block; filter:grayscale(0.2) contrast(1.05); }
    [data-theme="dark"] .map-wrapper iframe { filter:invert(0.9) hue-rotate(180deg) grayscale(0.2); }

    @media (max-width:860px){ .contact-grid { grid-template-columns:1fr; } .form-row { grid-template-columns:1fr; } .form-card { padding:28px; } }
@endsection

@section('content')
<div class="page-pad">
    <section class="section" style="padding-top:0;">
        <div class="container">
            <div class="page-hero" data-reveal>
                <span class="eyebrow">Contact</span>
                <h1>Let's <span class="grad-text">talk</span></h1>
                <p>Have a question, partnership idea, or need help with a ride? We're always here for you.</p>
            </div>

            <div class="contact-grid">
                {{-- Contact info --}}
                <div class="contact-info" data-reveal="left">
                    <div class="card info-card">
                        <span class="info-ic"><svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><polyline points="22,4 12,13 2,4"/></svg></span>
                        <div>
                            <h3>Email</h3>
                            <a href="mailto:{{ $contact['email'] ?? 'support@comingbro.in' }}">{{ $contact['email'] ?? 'support@comingbro.in' }}</a>
                        </div>
                    </div>
                    <div class="card info-card">
                        <span class="info-ic"><svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg></span>
                        <div>
                            <h3>Phone</h3>
                            <a href="tel:{{ $contact['phone'] ?? '+919028777184' }}">{{ $contact['phone'] ?? '+919028777184' }}</a>
                        </div>
                    </div>
                    @if(!empty($contact['address'] ?? '') && strtolower($contact['address']) !== 'your address')
                    <div class="card info-card">
                        <span class="info-ic"><svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></span>
                        <div>
                            <h3>Address</h3>
                            <p>{{ $contact['address'] }}</p>
                        </div>
                    </div>
                    @endif
                    <div class="card info-card">
                        <span class="info-ic"><svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></span>
                        <div>
                            <h3>Support hours</h3>
                            <p>24 / 7 · Every day</p>
                        </div>
                    </div>
                </div>

                {{-- Form --}}
                <div class="card form-card" data-reveal="right">
                    <h2>Send us a message</h2>
                    <p class="subtitle">Fill out the form and we'll get back to you as soon as possible.</p>
                    <form action="#" method="POST" onsubmit="return false;">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Full name</label>
                                <input type="text" id="name" name="name" placeholder="Your name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email address</label>
                                <input type="email" id="email" name="email" placeholder="your@email.com" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" id="subject" name="subject" placeholder="How can we help?">
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" placeholder="Tell us a bit more about your query…" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width:100%;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                            Send message
                        </button>
                        <p class="form-note">We typically reply within a few hours.</p>
                    </form>
                </div>
            </div>

            <div class="map-wrapper" data-reveal>
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1884.2837186356746!2d73.22505683967282!3d19.170402899999996!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be793006436dc1f%3A0xa8beb2bfc1e41e8e!2sA%20Wing!5e0!3m2!1sen!2sin!4v1729079433738!5m2!1sen!2sin"
                    allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </section>
</div>
@endsection

@extends('layouts.frontend')

@section('title', 'Contact Us - ComingBro')
@section('meta_description', 'Get in touch with ComingBro. We are here to help with your questions about rides, partnerships and more.')

@section('styles')
/* ============================================================
   HERO BANNER
   ============================================================ */
.page-hero {
    padding: 80px 0 60px;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    text-align: center;
    position: relative;
    overflow: hidden;
}

.page-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle at 30% 50%, rgba(255,255,255,0.08) 0%, transparent 50%),
                radial-gradient(circle at 70% 50%, rgba(255,255,255,0.05) 0%, transparent 50%);
    pointer-events: none;
}

.page-hero h1 {
    font-size: 2.8rem;
    font-weight: 800;
    color: #fff;
    margin-bottom: 12px;
    position: relative;
}

.page-hero p {
    font-size: 1.1rem;
    color: rgba(255,255,255,0.8);
    max-width: 600px;
    margin: 0 auto;
    position: relative;
}

/* ============================================================
   CONTACT SECTION
   ============================================================ */
.contact-section {
    padding: 80px 0;
}

.contact-grid {
    display: grid;
    grid-template-columns: 1fr 1.3fr;
    gap: 40px;
    align-items: start;
}

/* Contact Info Cards */
.contact-info {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.contact-info-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 24px;
    display: flex;
    align-items: flex-start;
    gap: 16px;
    transition: all 0.35s ease;
}

.contact-info-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px var(--card-hover-shadow);
    border-color: var(--primary);
}

.contact-icon {
    width: 52px;
    height: 52px;
    min-width: 52px;
    border-radius: 14px;
    background: linear-gradient(135deg, rgba(var(--primary-rgb), 0.1), rgba(var(--secondary-rgb), 0.1));
    display: flex;
    align-items: center;
    justify-content: center;
}

.contact-icon svg {
    width: 22px;
    height: 22px;
    stroke: var(--primary);
    fill: none;
    stroke-width: 2;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.contact-info-card h3 {
    font-size: 0.82rem;
    font-weight: 700;
    color: var(--text-tertiary);
    text-transform: uppercase;
    letter-spacing: 0.06em;
    margin-bottom: 6px;
}

.contact-info-card p,
.contact-info-card a {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text);
    line-height: 1.6;
}

.contact-info-card a:hover {
    color: var(--primary);
}

/* Contact Form */
.contact-form-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 4px 24px var(--glass-shadow);
}

.contact-form-card h2 {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--text);
    margin-bottom: 6px;
}

.contact-form-card .subtitle {
    font-size: 0.92rem;
    color: var(--text-secondary);
    margin-bottom: 28px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-size: 0.88rem;
    font-weight: 600;
    color: var(--text);
    margin-bottom: 8px;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 12px 16px;
    background: var(--bg-muted);
    border: 1px solid var(--border);
    border-radius: 12px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.95rem;
    color: var(--text);
    outline: none;
    transition: all 0.3s ease;
}

.form-group input::placeholder,
.form-group textarea::placeholder {
    color: var(--text-tertiary);
}

.form-group input:focus,
.form-group textarea:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(var(--primary-rgb), 0.1);
}

.form-group textarea {
    min-height: 130px;
    resize: vertical;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.form-submit {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 14px 32px;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: #fff;
    font-size: 0.95rem;
    font-weight: 700;
    border-radius: 14px;
    border: none;
    cursor: pointer;
    transition: all 0.35s ease;
    font-family: 'Plus Jakarta Sans', sans-serif;
    box-shadow: 0 4px 20px rgba(var(--primary-rgb), 0.3);
}

.form-submit:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 35px rgba(var(--primary-rgb), 0.45);
}

/* ============================================================
   MAP SECTION
   ============================================================ */
.map-section {
    padding: 0 0 80px;
}

.map-wrapper {
    border-radius: 20px;
    overflow: hidden;
    border: 1px solid var(--border);
    box-shadow: 0 4px 24px var(--glass-shadow);
}

.map-wrapper iframe {
    width: 100%;
    height: 400px;
    border: 0;
    display: block;
}

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media (max-width: 768px) {
    .page-hero {
        padding: 60px 0 40px;
    }

    .page-hero h1 {
        font-size: 2rem;
    }

    .contact-grid {
        grid-template-columns: 1fr;
    }

    .contact-form-card {
        padding: 28px 20px;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .map-wrapper iframe {
        height: 280px;
    }
}
@endsection

@section('content')
<!-- Hero Banner -->
<section class="page-hero">
    <div class="container">
        <h1>Contact Us</h1>
        <p>Have a question or need help? We are always here for you.</p>
    </div>
</section>

<!-- Contact Section -->
<section class="contact-section">
    <div class="container">
        <div class="contact-grid">
            <!-- Left: Contact Info -->
            <div class="contact-info">
                <!-- Email -->
                <div class="contact-info-card">
                    <div class="contact-icon">
                        <svg viewBox="0 0 24 24">
                            <rect x="2" y="4" width="20" height="16" rx="2"/>
                            <polyline points="22,4 12,13 2,4"/>
                        </svg>
                    </div>
                    <div>
                        <h3>Email</h3>
                        <a href="mailto:{{ $contact['email'] ?? 'support@comingbro.in' }}">{{ $contact['email'] ?? 'support@comingbro.in' }}</a>
                    </div>
                </div>

                <!-- Phone -->
                <div class="contact-info-card">
                    <div class="contact-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                    </div>
                    <div>
                        <h3>Phone</h3>
                        <a href="tel:{{ $contact['phone'] ?? '+919028777184' }}">{{ $contact['phone'] ?? '+919028777184' }}</a>
                    </div>
                </div>

                <!-- Address -->
                @if(!empty($contact['address'] ?? ''))
                <div class="contact-info-card">
                    <div class="contact-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                    </div>
                    <div>
                        <h3>Address</h3>
                        <p>{{ $contact['address'] }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Right: Contact Form -->
            <div class="contact-form-card">
                <h2>Send Us a Message</h2>
                <p class="subtitle">Fill out the form and we will get back to you as soon as possible.</p>
                <form action="#" method="POST" onsubmit="return false;">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" name="name" placeholder="Your name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" placeholder="your@email.com" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" id="subject" name="subject" placeholder="How can we help?">
                    </div>
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" placeholder="Tell us more about your query..." required></textarea>
                    </div>
                    <button type="submit" class="form-submit">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                        Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Google Map -->
<section class="map-section">
    <div class="container">
        <div class="map-wrapper">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1884.2837186356746!2d73.22505683967282!3d19.170402899999996!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be793006436dc1f%3A0xa8beb2bfc1e41e8e!2sA%20Wing!5e0!3m2!1sen!2sin!4v1729079433738!5m2!1sen!2sin"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
</section>
@endsection

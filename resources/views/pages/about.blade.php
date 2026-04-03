@extends('layouts.frontend')

@section('title', 'About Us - ComingBro')
@section('meta_description', 'Learn about ComingBro - committed to making a positive impact by tackling unemployment and improving transportation access across India.')

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
   ABOUT CONTENT
   ============================================================ */
.about-section {
    padding: 80px 0;
}

.about-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 48px;
    max-width: 900px;
    margin: 0 auto;
    box-shadow: 0 4px 24px var(--glass-shadow);
}

.about-card h2 {
    font-size: 1.8rem;
    font-weight: 800;
    color: var(--text);
    margin-bottom: 8px;
}

.about-card .gradient-line {
    width: 60px;
    height: 4px;
    background: linear-gradient(90deg, var(--primary), var(--secondary));
    border-radius: 4px;
    margin-bottom: 24px;
}

.about-card .about-text {
    font-size: 1.05rem;
    color: var(--text-secondary);
    line-height: 1.9;
}

.about-card .about-text p {
    margin-bottom: 16px;
}

.about-card .about-text p:last-child {
    margin-bottom: 0;
}

/* ============================================================
   MISSION / VALUES
   ============================================================ */
.values-section {
    padding: 0 0 80px;
}

.values-section .section-title {
    text-align: center;
    margin-bottom: 48px;
}

.values-section .section-title h2 {
    font-size: 2rem;
    font-weight: 800;
    color: var(--text);
    margin-bottom: 12px;
}

.values-section .section-title p {
    font-size: 1rem;
    color: var(--text-secondary);
    max-width: 500px;
    margin: 0 auto;
}

.values-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 28px;
    max-width: 1000px;
    margin: 0 auto;
}

.value-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 36px 28px;
    text-align: center;
    transition: all 0.35s ease;
}

.value-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 40px var(--card-hover-shadow);
    border-color: var(--primary);
}

.value-icon {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    background: linear-gradient(135deg, rgba(var(--primary-rgb), 0.1), rgba(var(--secondary-rgb), 0.1));
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
}

.value-icon svg {
    width: 28px;
    height: 28px;
    stroke: var(--primary);
    fill: none;
    stroke-width: 2;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.value-card h3 {
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 10px;
}

.value-card p {
    font-size: 0.92rem;
    color: var(--text-secondary);
    line-height: 1.7;
}

/* ============================================================
   CTA SECTION
   ============================================================ */
.cta-section {
    padding: 0 0 80px;
}

.cta-card {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    border-radius: 24px;
    padding: 60px 48px;
    text-align: center;
    position: relative;
    overflow: hidden;
    max-width: 900px;
    margin: 0 auto;
}

.cta-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -30%;
    width: 400px;
    height: 400px;
    background: rgba(255,255,255,0.06);
    border-radius: 50%;
    pointer-events: none;
}

.cta-card h2 {
    font-size: 2rem;
    font-weight: 800;
    color: #fff;
    margin-bottom: 12px;
    position: relative;
}

.cta-card p {
    font-size: 1.05rem;
    color: rgba(255,255,255,0.85);
    margin-bottom: 28px;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
    position: relative;
}

.cta-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 14px 32px;
    background: #fff;
    color: var(--primary);
    font-size: 0.95rem;
    font-weight: 700;
    border-radius: 14px;
    transition: all 0.35s ease;
    position: relative;
}

.cta-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.2);
    color: var(--primary);
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

    .about-card {
        padding: 32px 24px;
    }

    .values-grid {
        grid-template-columns: 1fr;
        max-width: 400px;
    }

    .cta-card {
        padding: 40px 24px;
    }

    .cta-card h2 {
        font-size: 1.5rem;
    }
}
@endsection

@section('content')
<!-- Hero Banner -->
<section class="page-hero">
    <div class="container">
        <h1>About ComingBro</h1>
        <p>Empowering India with smarter, safer and more accessible transportation</p>
    </div>
</section>

<!-- About Content -->
<section class="about-section">
    <div class="container">
        <div class="about-card">
            <h2>Our Story</h2>
            <div class="gradient-line"></div>
            <div class="about-text">
                @if(isset($about) && $about->description)
                    {!! strip_tags($about->description, '<p><b><strong><br>') !!}
                @else
                    <p>We represent the spirit of an Indian youth, committed to make a positive impact by tackling unemployment and improving transportation access across the underserved places of India. Through COMING BRO we have made a bold move to extend the financial assistance to numerous individuals and tackling their transportation needs with swift and straightforward solution.</p>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Values -->
<section class="values-section">
    <div class="container">
        <div class="section-title">
            <h2>Our Core Values</h2>
            <p>The principles that drive everything we do at ComingBro</p>
        </div>
        <div class="values-grid">
            <!-- Innovation -->
            <div class="value-card">
                <div class="value-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 2a7 7 0 0 0-7 7c0 2.38 1.19 4.47 3 5.74V17a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-2.26c1.81-1.27 3-3.36 3-5.74a7 7 0 0 0-7-7z"/>
                        <line x1="9" y1="21" x2="15" y2="21"/>
                        <line x1="10" y1="24" x2="14" y2="24"/>
                    </svg>
                </div>
                <h3>Innovation</h3>
                <p>We leverage cutting-edge technology to create seamless ride experiences and solve real transportation challenges across India.</p>
            </div>

            <!-- Safety -->
            <div class="value-card">
                <div class="value-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                </div>
                <h3>Safety</h3>
                <p>Your safety is our top priority. From verified drivers to real-time tracking, every feature is built with your security in mind.</p>
            </div>

            <!-- Accessibility -->
            <div class="value-card">
                <div class="value-icon">
                    <svg viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M2 12h20"/>
                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                    </svg>
                </div>
                <h3>Accessibility</h3>
                <p>Affordable rides for everyone, everywhere. We are committed to reaching underserved communities and bridging the transportation gap.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div class="container">
        <div class="cta-card">
            <h2>Have Questions? Get in Touch</h2>
            <p>We would love to hear from you. Reach out to us and let us know how we can help.</p>
            <a href="/contact" class="cta-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                Contact Us
            </a>
        </div>
    </div>
</section>
@endsection

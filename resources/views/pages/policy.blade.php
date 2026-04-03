@extends('layouts.frontend')

@section('title', ($title ?? 'Policy') . ' - ComingBro')
@section('meta_description', 'Read the ' . ($title ?? 'policy') . ' for ComingBro ride-hailing services.')

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
   POLICY CONTENT
   ============================================================ */
.policy-section {
    padding: 80px 0;
}

.policy-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 48px;
    max-width: 900px;
    margin: 0 auto;
    box-shadow: 0 4px 24px var(--glass-shadow);
}

/* Prose-like styling for CMS HTML content */
.policy-content {
    font-size: 1rem;
    color: var(--text-secondary);
    line-height: 1.9;
}

.policy-content h1 {
    font-size: 2rem;
    font-weight: 800;
    color: var(--text);
    margin-top: 40px;
    margin-bottom: 16px;
    line-height: 1.3;
}

.policy-content h2 {
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--text);
    margin-top: 36px;
    margin-bottom: 14px;
    line-height: 1.3;
}

.policy-content h3 {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--text);
    margin-top: 28px;
    margin-bottom: 12px;
    line-height: 1.4;
}

.policy-content h4 {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--text);
    margin-top: 24px;
    margin-bottom: 10px;
}

.policy-content h5,
.policy-content h6 {
    font-size: 1rem;
    font-weight: 700;
    color: var(--text);
    margin-top: 20px;
    margin-bottom: 8px;
}

.policy-content p {
    margin-bottom: 16px;
}

.policy-content a {
    color: var(--primary);
    text-decoration: underline;
    text-underline-offset: 2px;
}

.policy-content a:hover {
    color: var(--secondary);
}

.policy-content ul,
.policy-content ol {
    margin-bottom: 16px;
    padding-left: 24px;
}

.policy-content ul {
    list-style: disc;
}

.policy-content ol {
    list-style: decimal;
}

.policy-content li {
    margin-bottom: 8px;
    line-height: 1.7;
}

.policy-content li ul,
.policy-content li ol {
    margin-top: 8px;
    margin-bottom: 0;
}

.policy-content blockquote {
    border-left: 4px solid var(--primary);
    padding: 16px 20px;
    margin: 24px 0;
    background: var(--bg-muted);
    border-radius: 0 12px 12px 0;
    color: var(--text);
    font-style: italic;
}

.policy-content table {
    width: 100%;
    border-collapse: collapse;
    margin: 24px 0;
    font-size: 0.92rem;
    border-radius: 12px;
    overflow: hidden;
}

.policy-content table th,
.policy-content table td {
    padding: 12px 16px;
    border: 1px solid var(--border);
    text-align: left;
}

.policy-content table th {
    background: var(--bg-muted);
    font-weight: 700;
    color: var(--text);
}

.policy-content table tr:hover td {
    background: var(--bg-muted);
}

.policy-content img {
    max-width: 100%;
    border-radius: 12px;
    margin: 20px 0;
}

.policy-content strong,
.policy-content b {
    font-weight: 700;
    color: var(--text);
}

.policy-content hr {
    border: none;
    border-top: 1px solid var(--border);
    margin: 32px 0;
}

.policy-content code {
    background: var(--bg-muted);
    padding: 2px 8px;
    border-radius: 6px;
    font-size: 0.9em;
}

.policy-content pre {
    background: var(--bg-muted);
    padding: 20px;
    border-radius: 12px;
    overflow-x: auto;
    margin: 20px 0;
}

.policy-content pre code {
    background: none;
    padding: 0;
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

    .policy-card {
        padding: 28px 20px;
    }

    .policy-content h1 {
        font-size: 1.6rem;
    }

    .policy-content h2 {
        font-size: 1.3rem;
    }

    .policy-content h3 {
        font-size: 1.1rem;
    }

    .policy-content table {
        display: block;
        overflow-x: auto;
    }
}
@endsection

@section('content')
<!-- Hero Banner -->
<section class="page-hero">
    <div class="container">
        <h1>{{ $title ?? 'Policy' }}</h1>
        <p>Please read the following carefully</p>
    </div>
</section>

<!-- Policy Content -->
<section class="policy-section">
    <div class="container">
        <div class="policy-card">
            <div class="policy-content">
                {!! $content ?? '' !!}
            </div>
        </div>
    </div>
</section>
@endsection

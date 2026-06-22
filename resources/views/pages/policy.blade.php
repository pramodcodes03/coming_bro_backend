@extends('layouts.frontend')

@section('title', ($title ?? 'Policy') . ' — ComingBro')
@section('meta_description', 'Read the ' . ($title ?? 'policy') . ' for ComingBro ride-hailing services.')

@section('styles')
    .policy-head { text-align:center; margin-bottom:48px; }
    .policy-head h1 { font-size:clamp(2.2rem,5vw,3.2rem); margin-bottom:12px; }
    .policy-head p { color:var(--text-dim); font-size:.95rem; }

    .policy-card { max-width:860px; margin:0 auto; padding:48px; }
    .policy-body { color:var(--text-soft); line-height:1.85; font-size:1rem; }
    .policy-body :is(h1,h2,h3,h4) { color:var(--text); margin:30px 0 14px; line-height:1.3; }
    .policy-body h2 { font-size:1.5rem; }
    .policy-body h3 { font-size:1.2rem; }
    .policy-body p { margin-bottom:16px; }
    .policy-body ul, .policy-body ol { margin:0 0 16px 22px; }
    .policy-body li { margin-bottom:8px; }
    .policy-body a { color:var(--primary); }
    .policy-body a:hover { text-decoration:underline; }
    .policy-body strong, .policy-body b { color:var(--text); }
    .policy-body table { width:100%; border-collapse:collapse; margin:16px 0; }
    .policy-body td, .policy-body th { border:1px solid var(--border); padding:10px 12px; }
    .policy-empty { text-align:center; color:var(--text-dim); padding:40px 0; }

    @media (max-width:640px){ .policy-card { padding:30px 24px; } }
@endsection

@section('content')
<div class="page-pad">
    <section class="section" style="padding-top:0;">
        <div class="container">
            <div class="policy-head" data-reveal>
                <span class="eyebrow">Legal</span>
                <h1>{{ $title ?? 'Policy' }}</h1>
                <p>Last updated {{ date('F Y') }}</p>
            </div>
            <div class="card policy-card" data-reveal>
                <div class="policy-body">
                    @if(!empty(trim(strip_tags($content ?? ''))))
                        {!! $content !!}
                    @else
                        <p class="policy-empty">This {{ strtolower($title ?? 'document') }} will be available here shortly. For any questions in the meantime, please <a href="/contact">contact us</a>.</p>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

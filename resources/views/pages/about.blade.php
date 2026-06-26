@extends('layouts.frontend')

@section('title', 'About Us — ComingBro')
@section('meta_description', 'Learn about ComingBro — committed to making a positive impact by tackling unemployment and improving transportation access across India.')

@section('content')
<section class="relative overflow-hidden px-7 pb-28 pt-44 lg:pt-48">
    <div aria-hidden="true" class="animate-spinslow pointer-events-none absolute -right-[6%] -top-[12%] -z-10 h-[620px] w-[620px] rounded-full opacity-80 blur-[70px]
        [background:conic-gradient(from_120deg_at_50%_50%,rgba(43,111,255,.12),rgba(109,75,255,.1),rgba(0,194,168,.08),rgba(43,111,255,.12))]"></div>
    <div class="mx-auto max-w-[1200px]">
        {{-- Hero --}}
        <div data-reveal class="mx-auto max-w-[680px] text-center">
            <span class="inline-flex items-center gap-2 rounded-full border border-[#0d1c3a22] bg-white px-3.5 py-1.5 text-[.72rem] font-semibold uppercase tracking-[.13em] text-[#2b6fff] shadow-soft dark:border-white/15 dark:bg-white/[.05]"><span class="h-[7px] w-[7px] rounded-full bg-[#00c2a8]"></span> Our story</span>
            <h1 class="mt-5 font-display text-[clamp(2.4rem,5vw,3.6rem)] font-extrabold leading-[1.05] tracking-tight">Driven to move <span class="brand-text">India forward</span></h1>
            <p class="mx-auto mt-4 max-w-[560px] text-[1.1rem] leading-relaxed text-[#4a5a72] dark:text-[#aab6cc]">Empowering communities with smarter, safer and more accessible transportation — one ride at a time.</p>
        </div>

        {{-- Who we are --}}
        <div data-reveal class="mx-auto mt-16 max-w-[820px]">
            <div class="rounded-[28px] border border-[#0d1c3a14] bg-white p-8 shadow-card sm:p-11 dark:border-white/10 dark:bg-white/[.035]">
                <h2 class="font-display text-[1.8rem] font-bold">Who we are</h2>
                <div class="mb-6 mt-3.5 h-1 w-[70px] rounded bg-brand-grad"></div>
                <div class="space-y-3.5 text-[1.02rem] leading-[1.8] text-[#4a5a72] [&_a]:text-[#2b6fff] [&_b]:text-[#0b1220] [&_strong]:text-[#0b1220] dark:text-[#aab6cc] dark:[&_b]:text-white dark:[&_strong]:text-white">
                    @if(isset($about) && $about->description)
                        {!! strip_tags($about->description, '<p><b><strong><br><em><ul><li>') !!}
                    @else
                        <p>We represent the spirit of India's youth — committed to making a positive impact by tackling unemployment and improving transportation access across the country's underserved places. Through ComingBro, we've made a bold move to extend financial opportunity to thousands of drivers while solving everyday transport needs with a swift, straightforward solution.</p>
                        <p>What started as a simple idea has grown into a platform that connects riders and drivers across more than a hundred cities — built on trust, fair pricing and technology that just works.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Core values --}}
        <div data-reveal class="mx-auto mb-14 mt-24 max-w-[660px] text-center">
            <span class="inline-flex items-center gap-2 rounded-full border border-[#0d1c3a22] bg-white px-3.5 py-1.5 text-[.72rem] font-semibold uppercase tracking-[.13em] text-[#2b6fff] shadow-soft dark:border-white/15 dark:bg-white/[.05]"><span class="h-[7px] w-[7px] rounded-full bg-[#00c2a8]"></span> What we stand for</span>
            <h2 class="mt-5 font-display text-[clamp(1.9rem,4vw,2.6rem)] font-bold tracking-tight">Our core values</h2>
            <p class="mt-4 text-[1.05rem] leading-relaxed text-[#4a5a72] dark:text-[#aab6cc]">The principles that guide every decision we make at ComingBro.</p>
        </div>
        <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
            @php
                $values = [
                    ['M12 2a7 7 0 00-7 7c0 2.38 1.19 4.47 3 5.74V17a1 1 0 001 1h6a1 1 0 001-1v-2.26c1.81-1.27 3-3.36 3-5.74a7 7 0 00-7-7zM9 21h6','Innovation','We use cutting-edge technology to create seamless ride experiences and solve real transportation challenges across India.','0'],
                    ['M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z','Safety','Your safety comes first. From verified drivers to real-time tracking, every feature is built with your security in mind.','1'],
                    ['M12 2a10 10 0 100 20 10 10 0 000-20M2 12h20M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z','Accessibility','Affordable rides for everyone, everywhere. We\'re committed to reaching underserved communities and bridging the transport gap.','2'],
                ];
            @endphp
            @foreach($values as $v)
                <div data-reveal data-delay="{{ $v[3] }}" class="rounded-[22px] border border-[#0d1c3a14] bg-white p-8 shadow-card transition hover:-translate-y-1.5 hover:shadow-lift dark:border-white/10 dark:bg-white/[.035]">
                    <div class="mb-5 flex h-14 w-14 items-center justify-center rounded-[15px] bg-brand-grad shadow-glow"><svg class="h-[26px] w-[26px] stroke-white" viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $v[0] }}"/></svg></div>
                    <h3 class="mb-2 font-display text-[1.2rem] font-bold">{{ $v[1] }}</h3>
                    <p class="text-[.93rem] leading-relaxed text-[#4a5a72] dark:text-[#aab6cc]">{{ $v[2] }}</p>
                </div>
            @endforeach
        </div>

        {{-- CTA --}}
        <div data-reveal class="relative mt-24 overflow-hidden rounded-[28px] bg-[linear-gradient(135deg,#1b4dd8,#6d4bff)] p-14 text-center text-white shadow-lift">
            <div aria-hidden="true" class="absolute inset-0 opacity-50 [background:radial-gradient(40%_80%_at_88%_12%,rgba(255,255,255,.35),transparent_60%),radial-gradient(50%_90%_at_10%_100%,rgba(0,194,168,.5),transparent_60%)]"></div>
            <div class="relative z-[1] mx-auto max-w-[560px]">
                <h2 class="mb-3.5 font-display text-[clamp(1.7rem,4vw,2.4rem)] font-bold tracking-tight text-white">Have questions? Get in touch</h2>
                <p class="mb-7 text-white/90">We'd love to hear from you. Reach out and let us know how we can help.</p>
                <a href="/contact" class="inline-flex items-center gap-2 rounded-[14px] bg-white px-7 py-3.5 text-[.95rem] font-semibold text-[#1b4dd8] shadow-lg transition hover:-translate-y-0.5">
                    <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/></svg>
                    Contact us
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

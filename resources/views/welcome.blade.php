@extends('layouts.frontend')

@section('title', 'ComingBro — Move through the city, effortlessly')
@section('meta_description', 'ComingBro is India\'s premium ride-hailing platform. Book city rides, intercity travel and freight in seconds — safe, fairly priced, and always on time.')

@section('content')
    {{-- ====================== HERO ====================== --}}
    <section id="hero" class="relative overflow-hidden pb-28 pt-44 lg:pt-48">
        {{-- soft rotating halo --}}
        <div aria-hidden="true" class="animate-spinslow pointer-events-none absolute -right-[6%] -top-[12%] -z-10 h-[760px] w-[760px] rounded-full opacity-90 blur-[70px]
            [background:conic-gradient(from_120deg_at_50%_50%,rgba(43,111,255,.12),rgba(109,75,255,.1),rgba(0,194,168,.08),rgba(43,111,255,.12))]"></div>

        <div class="mx-auto grid max-w-[1200px] grid-cols-1 items-center gap-14 px-7 lg:grid-cols-[1.04fr_.96fr]">
            {{-- copy --}}
            <div data-reveal="left" class="text-center lg:text-left">
                <span class="inline-flex items-center gap-2 rounded-full border border-[#0d1c3a22] bg-white px-3.5 py-1.5 text-[.72rem] font-semibold tracking-[.13em] text-[#2b6fff] shadow-soft dark:border-white/15 dark:bg-white/[.05]">
                    <span class="h-[7px] w-[7px] rounded-full bg-[#00c2a8] shadow-[0_0_0_4px_rgba(0,194,168,.18)]"></span>
                    RATED 4.8 ★ BY 50,000+ RIDERS
                </span>
                <h1 class="mt-6 font-display text-[clamp(2.7rem,5.6vw,4.5rem)] font-extrabold leading-[1.04] tracking-tight">
                    Move through the city,<br><span class="brand-text">effortlessly.</span>
                </h1>
                <p class="mx-auto mt-5 max-w-[512px] text-[1.16rem] leading-relaxed text-[#4a5a72] dark:text-[#aab6cc] lg:mx-0">
                    Tap once and go. ComingBro connects you with nearby drivers in seconds — for city rides, intercity trips and freight — at fares you can trust, every single time.
                </p>

                <div class="mt-9 flex flex-wrap justify-center gap-3.5 lg:justify-start">
                    <a href="#download" class="group relative inline-flex items-center gap-2 overflow-hidden rounded-[14px] bg-brand-grad px-7 py-3.5 text-[.95rem] font-semibold text-white shadow-glow transition hover:-translate-y-0.5">
                        <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Get the app
                        <span class="absolute -left-3/4 top-0 h-full w-1/2 -skew-x-[20deg] bg-gradient-to-r from-transparent via-white/40 to-transparent transition-all duration-700 group-hover:left-[130%]"></span>
                    </a>
                    <a href="#how" class="inline-flex items-center gap-2 rounded-[14px] border border-[#0d1c3a22] bg-white px-7 py-3.5 text-[.95rem] font-semibold text-[#0b1220] shadow-soft transition hover:-translate-y-0.5 hover:border-[#2b6fff]/40 dark:border-white/15 dark:bg-white/[.05] dark:text-white">
                        <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8"/></svg>
                        See how it works
                    </a>
                </div>

                <div class="mt-10 flex flex-col items-center gap-4 sm:flex-row lg:justify-start">
                    <div class="flex">
                        @foreach([['AK','bg-brand-grad'],['PR','bg-[linear-gradient(135deg,#6d4bff,#a26bff)]'],['SM','bg-[linear-gradient(135deg,#00c2a8,#2b9fff)]'],['VJ','bg-[linear-gradient(135deg,#ff8a3d,#ff5e7e)]'],['+5k','bg-[linear-gradient(135deg,#6d4bff,#2b6fff)]']] as $i => $a)
                            <span class="flex h-[42px] w-[42px] items-center justify-center rounded-full border-[2.5px] border-white font-display text-[.78rem] font-bold text-white shadow-soft dark:border-[#0c1322] {{ $a[1] }} {{ $i ? '-ml-3.5' : '' }} {{ $a[0]==='+5k' ? '!text-[.68rem]' : '' }}">{{ $a[0] }}</span>
                        @endforeach
                    </div>
                    <div class="text-[.9rem] text-[#4a5a72] dark:text-[#aab6cc]">
                        <div class="tracking-[2px] text-[#ffb547]">★★★★★</div>
                        <strong class="font-semibold text-[#0b1220] dark:text-white">50,000+</strong> happy riders across India
                    </div>
                </div>
            </div>

            {{-- device mockup --}}
            <div data-reveal="right" class="relative flex justify-center">
                <div aria-hidden="true" class="absolute -bottom-[4%] left-1/2 -z-0 h-[34%] w-[78%] -translate-x-1/2 blur-[34px] [background:radial-gradient(50%_60%_at_50%_50%,rgba(43,111,255,.22),transparent_72%)]"></div>

                {{-- floating chips --}}
                <div class="animate-floaty absolute -left-7 top-[16%] z-[3] flex items-center gap-2.5 rounded-2xl border border-[#0d1c3a22] bg-white/70 px-4 py-2.5 shadow-card backdrop-blur-md dark:border-white/15 dark:bg-[#0c1322]/70">
                    <span class="flex h-[34px] w-[34px] items-center justify-center rounded-xl bg-[#00c2a8]/15"><svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="#00c2a8" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"/></svg></span>
                    <div><div class="font-display text-[.82rem] font-bold">Driver arriving</div><div class="text-[.66rem] text-[#8a97ab]">2 min away</div></div>
                </div>
                <div class="animate-floaty-rev absolute -right-6 bottom-[18%] z-[3] flex items-center gap-2.5 rounded-2xl border border-[#0d1c3a22] bg-white/70 px-4 py-2.5 shadow-card backdrop-blur-md dark:border-white/15 dark:bg-[#0c1322]/70">
                    <span class="flex h-[34px] w-[34px] items-center justify-center rounded-xl bg-[#2b6fff]/15"><svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="#2b6fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></span>
                    <div><div class="font-display text-[.82rem] font-bold">₹148</div><div class="text-[.66rem] text-[#8a97ab]">upfront fare</div></div>
                </div>

                <div class="animate-devfloat relative z-[2] w-[298px] rounded-[46px] border border-white/90 bg-[linear-gradient(165deg,#fff,#eef2fb)] p-2.5 shadow-lift [box-shadow:0_30px_70px_-30px_rgba(24,46,96,.28),0_0_0_1px_rgba(13,28,58,.08),0_0_0_11px_rgba(255,255,255,.55)] dark:border-white/15 dark:bg-[linear-gradient(165deg,#141d31,#0a1120)]">
                    <div class="absolute left-1/2 top-[11px] z-[5] h-[22px] w-[88px] -translate-x-1/2 rounded-[14px] bg-[#0b1220]"></div>
                    <div class="flex aspect-[300/612] flex-col overflow-hidden rounded-[36px] [background:radial-gradient(120%_70%_at_50%_0%,#eaf1ff,#f7faff)] dark:[background:radial-gradient(120%_70%_at_50%_0%,#16243d,#0a1120)]">
                        {{-- map --}}
                        <div class="relative flex-1 [background:linear-gradient(90deg,rgba(13,28,58,.05)_1px,transparent_1px)_0_0/38px_38px,linear-gradient(0deg,rgba(13,28,58,.05)_1px,transparent_1px)_0_0/38px_38px,radial-gradient(80%_60%_at_50%_28%,rgba(43,111,255,.14),transparent_72%)] dark:[background:linear-gradient(90deg,rgba(255,255,255,.045)_1px,transparent_1px)_0_0/38px_38px,linear-gradient(0deg,rgba(255,255,255,.045)_1px,transparent_1px)_0_0/38px_38px,radial-gradient(80%_60%_at_50%_28%,rgba(77,139,255,.2),transparent_72%)]">
                            <div class="absolute left-[24%] top-[22%] h-[46%] w-[52%] rounded-bl-[28px] border-b-[2.5px] border-l-[2.5px] border-dashed border-[#2b6fff] opacity-80"></div>
                            <span class="absolute left-[24%] top-[22%] h-[15px] w-[15px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-[#00c2a8] shadow-[0_0_0_6px_rgba(0,194,168,.18)]"></span>
                            <span class="absolute left-[76%] top-[68%] h-[15px] w-[15px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-[#ff5e7e] shadow-[0_0_0_6px_rgba(255,94,126,.18)]"></span>
                            <span class="animate-carfloat absolute left-1/2 top-[45%] flex h-[34px] w-[34px] -translate-x-1/2 -translate-y-1/2 items-center justify-center rounded-xl bg-brand-grad text-white shadow-[0_8px_22px_-4px_rgba(43,111,255,.6)]">
                                <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 17H3v-5l2-5h11l3 5h2v5h-2"/><circle cx="7.5" cy="17" r="2"/><circle cx="16.5" cy="17" r="2"/></svg>
                            </span>
                        </div>
                        {{-- sheet --}}
                        <div class="border-t border-[#0d1c3a14] bg-white px-4 pb-5 pt-4 dark:border-white/10 dark:bg-[#0c1322]">
                            <div class="mx-auto mb-3.5 h-1 w-[38px] rounded bg-[#0d1c3a22] dark:bg-white/15"></div>
                            <div class="mb-3 flex items-center gap-2.5">
                                <span class="flex h-[34px] w-[34px] items-center justify-center rounded-[10px] border border-[#0d1c3a14] bg-[#f6f8fd] dark:border-white/10 dark:bg-white/[.04]"><svg class="h-4 w-4 stroke-[#2b6fff]" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="10" r="3"/><path d="M12 2a8 8 0 0 0-8 8c0 5.25 8 12 8 12s8-6.75 8-12a8 8 0 0 0-8-8z"/></svg></span>
                                <div><div class="text-[.78rem] font-semibold">MG Road, Bengaluru</div><div class="text-[.66rem] text-[#8a97ab]">Pickup</div></div>
                            </div>
                            <div class="mb-3 flex items-center gap-2.5">
                                <span class="flex h-[34px] w-[34px] items-center justify-center rounded-[10px] border border-[#0d1c3a14] bg-[#f6f8fd] dark:border-white/10 dark:bg-white/[.04]"><svg class="h-4 w-4 stroke-[#2b6fff]" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></span>
                                <div><div class="text-[.78rem] font-semibold">Kempegowda Airport</div><div class="text-[.66rem] text-[#8a97ab]">Dropoff · 38 km</div></div>
                            </div>
                            <div class="mt-1.5 rounded-[13px] bg-brand-grad py-3 text-center font-display text-[.82rem] font-semibold text-white shadow-glow">Confirm ComingBro · ₹148</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ====================== MARQUEE STRIP ====================== --}}
    <div class="overflow-hidden border-y border-[#0d1c3a14] bg-white py-6 dark:border-white/10 dark:bg-[#0c1322]">
        <div class="mx-auto max-w-[1200px] px-7">
            <div class="mb-4 text-center text-[.74rem] font-semibold uppercase tracking-[.14em] text-[#8a97ab]">Trusted for every kind of journey across India</div>
        </div>
        <div class="animate-marquee flex w-max gap-14 [&>span]:inline-flex [&>span]:items-center [&>span]:gap-2.5 [&>span]:whitespace-nowrap [&>span]:font-display [&>span]:text-[1.05rem] [&>span]:font-semibold [&>span]:text-[#8a97ab] [&_svg]:h-5 [&_svg]:w-5 [&_svg]:stroke-[#8a97ab]">
            @php
                $marq = [
                    ['M5 17H3v-5l2-5h11l3 5h2v5h-2M7.5 17a2 2 0 104 0 2 2 0 00-4 0M16.5 17a2 2 0 104 0 2 2 0 00-4 0', 'City Rides'],
                    ['M3 12h18M3 12l4-7h10l4 7M3 12v6h2M21 12v6h-2M7 18h10', 'Intercity'],
                    ['M1 3h15v13H1zM16 8h4l3 3v5h-7', 'Freight'],
                    ['M12 2a10 10 0 100 20 10 10 0 000-20M12 6v6l4 2', 'Airport Transfers'],
                    ['M5 11h14a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2M7 11V7a5 5 0 0110 0v4', 'Safe Rides'],
                    ['M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5', 'Rentals'],
                ];
            @endphp
            @foreach(array_merge($marq, $marq) as $m)
                <span><svg viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $m[0] }}"/></svg> {{ $m[1] }}</span>
            @endforeach
        </div>
    </div>

    {{-- ====================== STATS ====================== --}}
    <section class="py-20">
        <div class="mx-auto grid max-w-[1200px] grid-cols-2 gap-5 px-7 md:grid-cols-5">
            @foreach([['50K+','Active riders'],['2.5K+','Verified drivers'],['120+','Cities served'],['4.8★','Average rating'],['99.2%','On-time pickups']] as $i => $s)
                <div data-reveal data-delay="{{ $i }}" class="rounded-[22px] border border-[#0d1c3a14] bg-white p-7 text-center shadow-card transition hover:-translate-y-1.5 hover:shadow-lift dark:border-white/10 dark:bg-white/[.035]">
                    <div class="brand-text font-display text-[2.3rem] font-bold leading-none">{{ $s[0] }}</div>
                    <div class="mt-2 text-[.84rem] text-[#4a5a72] dark:text-[#aab6cc]">{{ $s[1] }}</div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ====================== SERVICES (BENTO) ====================== --}}
    <section id="services" class="py-28">
        <div class="mx-auto max-w-[1200px] px-7">
            <div data-reveal class="mx-auto mb-16 max-w-[660px] text-center">
                <span class="inline-flex items-center gap-2 rounded-full border border-[#0d1c3a22] bg-white px-3.5 py-1.5 text-[.72rem] font-semibold uppercase tracking-[.13em] text-[#2b6fff] shadow-soft dark:border-white/15 dark:bg-white/[.05]"><span class="h-[7px] w-[7px] rounded-full bg-[#00c2a8]"></span> What we move</span>
                <h2 class="mt-5 font-display text-[clamp(2.05rem,4.5vw,3.15rem)] font-bold tracking-tight">One app for every journey</h2>
                <p class="mt-4 text-[1.08rem] leading-relaxed text-[#4a5a72] dark:text-[#aab6cc]">City hops, long-distance runs, or sending a package across town — ComingBro has a ride for it.</p>
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-6">
                {{-- large --}}
                <div data-reveal data-delay="0" class="group relative flex flex-col rounded-[22px] border border-[#0d1c3a14] bg-white p-8 shadow-card transition hover:-translate-y-1.5 hover:shadow-lift sm:col-span-2 lg:col-span-4 dark:border-white/10 dark:bg-white/[.035]">
                    <div class="mb-5 flex h-[54px] w-[54px] items-center justify-center rounded-2xl border border-[#0d1c3a14] bg-[linear-gradient(160deg,rgba(43,111,255,.12),rgba(109,75,255,.08))] dark:border-white/10"><svg class="h-[25px] w-[25px] stroke-[#2b6fff]" viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 17H3v-5l2-5h11l3 5h2v5h-2"/><circle cx="7.5" cy="17" r="2"/><circle cx="16.5" cy="17" r="2"/></svg></div>
                    <h3 class="mb-2.5 font-display text-[1.34rem] font-bold">City Rides</h3>
                    <p class="text-[.95rem] leading-relaxed text-[#4a5a72] dark:text-[#aab6cc]">Hatchbacks, sedans and SUVs at your doorstep in minutes. Upfront fares, live tracking and verified drivers — for the daily commute or the late-night ride home.</p>
                    <div class="mt-auto flex flex-wrap gap-2.5 pt-6">
                        @foreach(['Mini','Sedan','SUV','AC / Non-AC','Auto'] as $p)<span class="rounded-full border border-[#0d1c3a14] bg-[#f6f8fd] px-3.5 py-2 text-[.75rem] font-semibold text-[#4a5a72] dark:border-white/10 dark:bg-white/[.04] dark:text-[#aab6cc]">{{ $p }}</span>@endforeach
                    </div>
                </div>
                {{-- small x2 + md x2 --}}
                @php
                    $svc = [
                        ['lg:col-span-2','M3 12h18M3 12l4-7h10l4 7M3 12v6h2M21 12v6h-2M7 18h10','Intercity','One-way or round-trip travel between cities, at fixed, transparent prices.', '1'],
                        ['lg:col-span-2','M1 3h15v13H1zM16 8h4l3 3v5h-7M5.5 18.5a2 2 0 100 .01M18.5 18.5a2 2 0 100 .01','Freight','Move parcels and cargo with tempo and mini-truck options on demand.', '2'],
                        ['lg:col-span-3','M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10zM9 12l2 2 4-4','Safety, built in','Live trip sharing, an in-app SOS button and 24/7 support keep every journey accountable from pickup to drop.', '1'],
                        ['lg:col-span-3','M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 10-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 000-7.78z','Rewards & wallet','Top up once, ride anywhere. Earn referral credits and unlock loyalty perks the more you travel.', '2'],
                    ];
                @endphp
                @foreach($svc as $s)
                    <div data-reveal data-delay="{{ $s[4] }}" class="group relative flex flex-col rounded-[22px] border border-[#0d1c3a14] bg-white p-8 shadow-card transition hover:-translate-y-1.5 hover:shadow-lift sm:col-span-1 {{ $s[0] }} dark:border-white/10 dark:bg-white/[.035]">
                        <div class="mb-5 flex h-[54px] w-[54px] items-center justify-center rounded-2xl border border-[#0d1c3a14] bg-[linear-gradient(160deg,rgba(43,111,255,.12),rgba(109,75,255,.08))] dark:border-white/10"><svg class="h-[25px] w-[25px] stroke-[#2b6fff]" viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $s[1] }}"/></svg></div>
                        <h3 class="mb-2.5 font-display text-[1.34rem] font-bold">{{ $s[2] }}</h3>
                        <p class="text-[.95rem] leading-relaxed text-[#4a5a72] dark:text-[#aab6cc]">{{ $s[3] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ====================== PRICING / FARE TYPES ====================== --}}
    <section id="pricing" class="bg-[#f3f6fc] py-28 dark:bg-[#0a0f1c]">
        <div class="mx-auto max-w-[1200px] px-7">
            <div data-reveal class="mx-auto mb-16 max-w-[660px] text-center">
                <span class="inline-flex items-center gap-2 rounded-full border border-[#0d1c3a22] bg-white px-3.5 py-1.5 text-[.72rem] font-semibold uppercase tracking-[.13em] text-[#2b6fff] shadow-soft dark:border-white/15 dark:bg-white/[.05]"><span class="h-[7px] w-[7px] rounded-full bg-[#00c2a8]"></span> Transparent fares</span>
                <h2 class="mt-5 font-display text-[clamp(2.05rem,4.5vw,3.15rem)] font-bold tracking-tight">Pick a ride that fits your day</h2>
                <p class="mt-4 text-[1.08rem] leading-relaxed text-[#4a5a72] dark:text-[#aab6cc]">Upfront pricing on every category — no meters, no surprises. Sample base fares for a typical 5&nbsp;km city trip.</p>
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @php
                    $fares = [
                        ['Auto','Quick, pocket-friendly hops for short city distances.','₹49','~₹11 / km after base',['1–3 seats','Best for under 5 km'],false,'0'],
                        ['Mini','Compact hatchbacks — the everyday city favourite.','₹99','~₹14 / km after base',['Up to 4 seats','AC included','Live tracking'],true,'1'],
                        ['Sedan','Extra comfort and boot space for longer trips.','₹149','~₹18 / km after base',['Up to 4 seats','Premium comfort'],false,'2'],
                        ['SUV','Spacious rides for groups, luggage and family travel.','₹199','~₹22 / km after base',['Up to 6–7 seats','Extra luggage room'],false,'3'],
                    ];
                @endphp
                @foreach($fares as $f)
                    <div data-reveal data-delay="{{ $f[6] }}" class="flex flex-col rounded-[22px] border p-7 shadow-card transition hover:-translate-y-1.5 hover:shadow-lift {{ $f[5] ? 'border-[#2b6fff]/40 bg-[linear-gradient(165deg,rgba(43,111,255,.06),#fff)] dark:bg-[linear-gradient(165deg,rgba(77,139,255,.1),rgba(255,255,255,.035))]' : 'border-[#0d1c3a14] bg-white dark:bg-white/[.035]' }} dark:border-white/10">
                        @if($f[5])<span class="mb-4 self-start rounded-full bg-brand-grad px-3 py-1 text-[.68rem] font-semibold uppercase tracking-[.08em] text-white">Most popular</span>@endif
                        <div class="mb-1.5 flex items-center gap-2.5 font-display text-[1.18rem] font-bold">
                            <svg class="h-[22px] w-[22px] stroke-[#2b6fff]" viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 17H3v-5l2-5h11l3 5h2v5h-2"/><circle cx="7.5" cy="17" r="2"/><circle cx="16.5" cy="17" r="2"/></svg>
                            {{ $f[0] }}
                        </div>
                        <p class="mb-5 text-[.88rem] leading-snug text-[#4a5a72] dark:text-[#aab6cc]">{{ $f[1] }}</p>
                        <div class="font-display text-[1.9rem] font-bold leading-none">{{ $f[2] }}<small class="ml-1 text-[.82rem] font-medium text-[#8a97ab]">onwards</small></div>
                        <div class="mt-1.5 text-[.78rem] text-[#8a97ab]">{{ $f[3] }}</div>
                        <ul class="mt-5 flex flex-col gap-2.5">
                            @foreach($f[4] as $feat)
                                <li class="flex items-start gap-2 text-[.88rem] text-[#4a5a72] dark:text-[#aab6cc]"><svg class="mt-0.5 h-[17px] w-[17px] shrink-0 stroke-[#00c2a8]" viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg> {{ $feat }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
            <p class="mt-7 text-center text-[.85rem] text-[#8a97ab]">* Indicative fares. Final price varies by city, distance, time and demand — always shown upfront before you confirm.</p>
        </div>
    </section>

    {{-- ====================== HOW IT WORKS ====================== --}}
    <section id="how" class="py-28">
        <div class="mx-auto max-w-[1200px] px-7">
            <div data-reveal class="mx-auto mb-16 max-w-[660px] text-center">
                <span class="inline-flex items-center gap-2 rounded-full border border-[#0d1c3a22] bg-white px-3.5 py-1.5 text-[.72rem] font-semibold uppercase tracking-[.13em] text-[#2b6fff] shadow-soft dark:border-white/15 dark:bg-white/[.05]"><span class="h-[7px] w-[7px] rounded-full bg-[#00c2a8]"></span> How it works</span>
                <h2 class="mt-5 font-display text-[clamp(2.05rem,4.5vw,3.15rem)] font-bold tracking-tight">Your ride, in three taps</h2>
                <p class="mt-4 text-[1.08rem] leading-relaxed text-[#4a5a72] dark:text-[#aab6cc]">No haggling, no surprises. Just open the app and go.</p>
            </div>
            <div class="relative grid grid-cols-1 gap-6 md:grid-cols-3">
                <div aria-hidden="true" class="absolute left-[14%] right-[14%] top-14 hidden h-0.5 bg-[linear-gradient(90deg,transparent,#0d1c3a22,transparent)] md:block dark:bg-[linear-gradient(90deg,transparent,rgba(255,255,255,.16),transparent)]"></div>
                @php
                    $steps = [
                        ['01','Set your destination',"Enter where you're headed and instantly see an upfront fare for every ride type — before you book."],
                        ['02','Get matched instantly','We connect you with the nearest verified driver and share their live location, car and rating with you.'],
                        ['03','Ride & pay your way','Track the whole trip in real time, then pay by wallet, UPI, card or cash. Rate, and you\'re done.'],
                    ];
                @endphp
                @foreach($steps as $i => $st)
                    <div data-reveal data-delay="{{ $i }}" class="relative z-[1] rounded-[22px] border border-[#0d1c3a14] bg-white p-9 shadow-card transition hover:-translate-y-1.5 hover:shadow-lift dark:border-white/10 dark:bg-white/[.035]">
                        <div class="mb-5 flex h-[50px] w-[50px] items-center justify-center rounded-2xl bg-brand-grad font-display text-base font-bold text-white shadow-glow">{{ $st[0] }}</div>
                        <h3 class="mb-2 font-display text-[1.2rem] font-bold">{{ $st[1] }}</h3>
                        <p class="text-[.93rem] leading-relaxed text-[#4a5a72] dark:text-[#aab6cc]">{{ $st[2] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ====================== WHY CHOOSE (FEATURES SPLIT) ====================== --}}
    <section id="features" class="bg-[#f3f6fc] py-28 dark:bg-[#0a0f1c]">
        <div class="mx-auto grid max-w-[1200px] grid-cols-1 items-center gap-16 px-7 lg:grid-cols-2">
            <div data-reveal="left">
                <span class="inline-flex items-center gap-2 rounded-full border border-[#0d1c3a22] bg-white px-3.5 py-1.5 text-[.72rem] font-semibold uppercase tracking-[.13em] text-[#2b6fff] shadow-soft dark:border-white/15 dark:bg-white/[.05]"><span class="h-[7px] w-[7px] rounded-full bg-[#00c2a8]"></span> Why ComingBro</span>
                <h2 class="my-5 font-display text-[clamp(1.9rem,4vw,2.8rem)] font-bold tracking-tight">Built for trust, tuned for speed</h2>
                <div class="flex flex-col gap-2.5">
                    @php
                        $feats = [
                            ['M12 22a10 10 0 100-20 10 10 0 000 20M12 6v6l4 2','Fares you see first','Know the exact price before you confirm. No meter anxiety, no surge surprises.'],
                            ['M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z','Verified drivers only','Every driver is background-checked and document-verified before their first trip.'],
                            ['M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z','Support that answers','Real humans, available 24/7, plus an in-app SOS that loops in your trusted contacts.'],
                            ['M13 2L3 14h9l-1 8 10-12h-9l1-8z','Lightning-fast matching','Smart dispatch finds the closest driver in seconds, so you\'re moving sooner.'],
                        ];
                    @endphp
                    @foreach($feats as $ft)
                        <div class="flex gap-4 rounded-2xl p-4 transition hover:bg-white dark:hover:bg-white/[.04]">
                            <span class="flex h-12 w-12 min-w-12 items-center justify-center rounded-[14px] bg-brand-grad shadow-glow"><svg class="h-[22px] w-[22px] stroke-white" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $ft[0] }}"/></svg></span>
                            <div><h3 class="mb-1 font-display text-[1.12rem] font-semibold">{{ $ft[1] }}</h3><p class="text-[.93rem] leading-relaxed text-[#4a5a72] dark:text-[#aab6cc]">{{ $ft[2] }}</p></div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div data-reveal="right" class="relative aspect-square overflow-hidden rounded-[28px] border border-[#0d1c3a14] bg-white shadow-lift dark:border-white/10 dark:bg-[#0c1322]">
                <div class="absolute inset-0 [background:linear-gradient(90deg,rgba(13,28,58,.045)_1px,transparent_1px)_0_0/50px_50px,linear-gradient(0deg,rgba(13,28,58,.045)_1px,transparent_1px)_0_0/50px_50px,radial-gradient(60%_50%_at_60%_38%,rgba(43,111,255,.18),transparent_72%),radial-gradient(50%_40%_at_28%_82%,rgba(109,75,255,.16),transparent_72%)] dark:[background:linear-gradient(90deg,rgba(255,255,255,.04)_1px,transparent_1px)_0_0/50px_50px,linear-gradient(0deg,rgba(255,255,255,.04)_1px,transparent_1px)_0_0/50px_50px,radial-gradient(60%_50%_at_60%_38%,rgba(77,139,255,.22),transparent_72%),radial-gradient(50%_40%_at_28%_82%,rgba(155,123,255,.18),transparent_72%)]"></div>
                <div class="absolute left-6 top-6 flex items-center gap-2.5 rounded-[15px] border border-[#0d1c3a22] bg-white/70 px-4 py-3 shadow-card backdrop-blur-md dark:border-white/15 dark:bg-[#0c1322]/70">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="#00c2a8" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"/></svg>
                    <div><div class="font-display text-[.84rem] font-bold">Trip shared</div><div class="text-[.66rem] text-[#8a97ab]">with 2 contacts</div></div>
                </div>
                <div class="absolute bottom-6 right-6 flex items-center gap-2.5 rounded-[15px] border border-[#0d1c3a22] bg-white/70 px-4 py-3 shadow-card backdrop-blur-md dark:border-white/15 dark:bg-[#0c1322]/70">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="#2b6fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                    <div><div class="font-display text-[.84rem] font-bold">ETA 4 min</div><div class="text-[.66rem] text-[#8a97ab]">live tracking on</div></div>
                </div>
                <div class="absolute left-1/2 top-[46%] flex -translate-x-1/2 -translate-y-1/2 items-center gap-2.5 rounded-[15px] border border-[#0d1c3a22] bg-white/70 px-4 py-3 shadow-card backdrop-blur-md dark:border-white/15 dark:bg-[#0c1322]/70">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="#6d4bff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 17H3v-5l2-5h11l3 5h2v5h-2"/><circle cx="7.5" cy="17" r="2"/><circle cx="16.5" cy="17" r="2"/></svg>
                    <div><div class="font-display text-[.84rem] font-bold">Driver on the way</div><div class="text-[.66rem] text-[#8a97ab]">KA 01 · 4.9★</div></div>
                </div>
            </div>
        </div>
    </section>

    {{-- ====================== TESTIMONIALS ====================== --}}
    <section class="py-28">
        <div class="mx-auto max-w-[1200px] px-7">
            <div data-reveal class="mx-auto mb-16 max-w-[660px] text-center">
                <span class="inline-flex items-center gap-2 rounded-full border border-[#0d1c3a22] bg-white px-3.5 py-1.5 text-[.72rem] font-semibold uppercase tracking-[.13em] text-[#2b6fff] shadow-soft dark:border-white/15 dark:bg-white/[.05]"><span class="h-[7px] w-[7px] rounded-full bg-[#00c2a8]"></span> Loved by riders</span>
                <h2 class="mt-5 font-display text-[clamp(2.05rem,4.5vw,3.15rem)] font-bold tracking-tight">What India is saying</h2>
                <p class="mt-4 text-[1.08rem] leading-relaxed text-[#4a5a72] dark:text-[#aab6cc]">Real words from real riders moving across the country every day.</p>
            </div>
            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                @php
                    $testi = [
                        ['"Booked an airport cab at 4am and the driver was already waiting when I came down. Upfront fare, zero haggling — exactly what I needed."','Ananya K.','Bengaluru','AK','bg-brand-grad','0'],
                        ['"I send parcels to my shop across town twice a week. The freight option is cheaper than couriers and I can track it live. Brilliant."','Rohit S.','Pune','RS','bg-[linear-gradient(135deg,#6d4bff,#a26bff)]','1'],
                        ['"Did a Mumbai–Nashik intercity trip with my family. Fixed price, clean SUV, polite driver. We\'ll never go back to the old way."','Meera V.','Mumbai','MV','bg-[linear-gradient(135deg,#00c2a8,#2b9fff)]','2'],
                    ];
                @endphp
                @foreach($testi as $t)
                    <div data-reveal data-delay="{{ $t[5] }}" class="flex flex-col gap-5 rounded-[22px] border border-[#0d1c3a14] bg-white p-8 shadow-card transition hover:-translate-y-1.5 hover:shadow-lift dark:border-white/10 dark:bg-white/[.035]">
                        <div class="tracking-[1px] text-[#ffb547]">★★★★★</div>
                        <p class="flex-1 text-[1rem] leading-relaxed text-[#0b1220] dark:text-[#e6ecf6]">{{ $t[0] }}</p>
                        <div class="flex items-center gap-3">
                            <span class="flex h-11 w-11 items-center justify-center rounded-full font-display text-[.85rem] font-bold text-white {{ $t[4] }}">{{ $t[3] }}</span>
                            <div><strong class="block text-[.92rem] font-semibold">{{ $t[1] }}</strong><span class="text-[.8rem] text-[#8a97ab]">{{ $t[2] }}</span></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ====================== CITIES COVERAGE ====================== --}}
    <section class="bg-[#f3f6fc] py-28 dark:bg-[#0a0f1c]">
        <div class="mx-auto grid max-w-[1200px] grid-cols-1 items-center gap-16 px-7 lg:grid-cols-[1fr_1.1fr]">
            <div data-reveal="left">
                <span class="inline-flex items-center gap-2 rounded-full border border-[#0d1c3a22] bg-white px-3.5 py-1.5 text-[.72rem] font-semibold uppercase tracking-[.13em] text-[#2b6fff] shadow-soft dark:border-white/15 dark:bg-white/[.05]"><span class="h-[7px] w-[7px] rounded-full bg-[#00c2a8]"></span> 120+ cities & growing</span>
                <h2 class="my-5 font-display text-[clamp(1.9rem,4vw,2.8rem)] font-bold tracking-tight">From metros to small towns</h2>
                <p class="mb-7 text-[1.04rem] leading-relaxed text-[#4a5a72] dark:text-[#aab6cc]">Wherever you are in India, a ComingBro is minutes away. We're live across the country and adding new cities every month.</p>
                <div class="flex flex-wrap gap-3">
                    @foreach(['Bengaluru','Mumbai','Delhi NCR','Pune','Hyderabad','Chennai','Kolkata','Ahmedabad','Jaipur','Kochi'] as $city)
                        <span class="inline-flex items-center gap-2 rounded-full border border-[#0d1c3a14] bg-white px-4 py-2.5 text-[.92rem] font-medium shadow-soft transition hover:-translate-y-0.5 hover:border-[#2b6fff]/40 dark:border-white/10 dark:bg-white/[.04]">
                            <span class="h-2 w-2 rounded-full bg-[#00c2a8] shadow-[0_0_0_0_rgba(0,194,168,.5)]"></span>{{ $city }}
                        </span>
                    @endforeach
                </div>
            </div>
            <div data-reveal="right" class="relative aspect-[4/3] overflow-hidden rounded-[28px] border border-[#0d1c3a14] shadow-lift [background:linear-gradient(90deg,rgba(13,28,58,.045)_1px,transparent_1px)_0_0/44px_44px,linear-gradient(0deg,rgba(13,28,58,.045)_1px,transparent_1px)_0_0/44px_44px,radial-gradient(60%_60%_at_55%_45%,rgba(43,111,255,.14),transparent_72%)] dark:border-white/10 dark:[background:linear-gradient(90deg,rgba(255,255,255,.04)_1px,transparent_1px)_0_0/44px_44px,linear-gradient(0deg,rgba(255,255,255,.04)_1px,transparent_1px)_0_0/44px_44px,radial-gradient(60%_60%_at_55%_45%,rgba(77,139,255,.2),transparent_72%)]">
                @foreach([['28%','30%','Delhi'],['42%','58%','Mumbai'],['58%','70%','Bengaluru'],['70%','42%','Kolkata'],['50%','46%','Hyderabad']] as $d)
                    <span class="absolute h-3 w-3 -translate-x-1/2 -translate-y-1/2 rounded-full bg-[#2b6fff] shadow-[0_0_0_5px_rgba(43,111,255,.16)]" style="left:{{ $d[0] }};top:{{ $d[1] }}">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 whitespace-nowrap rounded-md border border-[#0d1c3a14] bg-white/70 px-2 py-0.5 text-[.72rem] font-semibold text-[#4a5a72] backdrop-blur-sm dark:border-white/10 dark:bg-[#0c1322]/70 dark:text-[#aab6cc]">{{ $d[2] }}</span>
                    </span>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ====================== DRIVER RECRUITMENT BAND ====================== --}}
    <section class="py-28">
        <div class="mx-auto max-w-[1200px] px-7">
            <div data-reveal class="relative grid grid-cols-1 items-center gap-9 overflow-hidden rounded-[28px] border border-[#0d1c3a14] bg-white p-11 shadow-card lg:grid-cols-[1.1fr_1fr] lg:gap-14 lg:p-14 dark:border-white/10 dark:bg-[#0c1322]">
                <div aria-hidden="true" class="absolute -right-[10%] -top-[30%] h-[380px] w-[380px] rounded-full blur-[20px] [background:radial-gradient(50%_50%_at_50%_50%,rgba(109,75,255,.14),transparent_70%)]"></div>
                <div class="relative z-[1]">
                    <span class="inline-flex items-center gap-2 rounded-full border border-[#0d1c3a22] bg-[#f6f8fd] px-3.5 py-1.5 text-[.72rem] font-semibold uppercase tracking-[.13em] text-[#2b6fff] dark:border-white/15 dark:bg-white/[.05]"><span class="h-[7px] w-[7px] rounded-full bg-[#00c2a8]"></span> Drive with us</span>
                    <h2 class="my-4 font-display text-[clamp(1.8rem,3.4vw,2.5rem)] font-bold tracking-tight">Earn on your own schedule</h2>
                    <p class="mb-7 text-[1.04rem] leading-relaxed text-[#4a5a72] dark:text-[#aab6cc]">Join thousands of verified ComingBro drivers. Flexible hours, fast weekly payouts, and a fair, transparent fare model. Sign up in minutes.</p>
                    <a href="/contact" class="group relative inline-flex items-center gap-2 overflow-hidden rounded-[14px] bg-brand-grad px-7 py-3.5 text-[.95rem] font-semibold text-white shadow-glow transition hover:-translate-y-0.5">
                        Become a driver
                        <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        <span class="absolute -left-3/4 top-0 h-full w-1/2 -skew-x-[20deg] bg-gradient-to-r from-transparent via-white/40 to-transparent transition-all duration-700 group-hover:left-[130%]"></span>
                    </a>
                </div>
                <div class="relative z-[1] grid grid-cols-2 gap-4">
                    @foreach([['₹35K+','Avg. monthly earnings'],['Weekly','Fast payouts'],['2.5K+','Active drivers'],['0%','Joining fee']] as $ds)
                        <div class="rounded-2xl border border-[#0d1c3a14] bg-[#f6f8fd] p-6 dark:border-white/10 dark:bg-white/[.04]">
                            <div class="font-display text-[1.8rem] font-bold">{{ $ds[0] }}</div>
                            <div class="mt-1.5 text-[.84rem] text-[#4a5a72] dark:text-[#aab6cc]">{{ $ds[1] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ====================== DOWNLOAD CTA ====================== --}}
    <section id="download" class="pb-28">
        <div class="mx-auto max-w-[1200px] px-7">
            <div data-reveal class="relative overflow-hidden rounded-[28px] bg-[linear-gradient(135deg,#1b4dd8,#6d4bff)] p-14 text-white shadow-lift sm:p-16">
                <div aria-hidden="true" class="absolute inset-0 opacity-50 [background:radial-gradient(40%_80%_at_88%_12%,rgba(255,255,255,.35),transparent_60%),radial-gradient(50%_90%_at_10%_100%,rgba(0,194,168,.5),transparent_60%)]"></div>
                <div aria-hidden="true" class="absolute inset-0 opacity-50 [background-image:linear-gradient(90deg,rgba(255,255,255,.07)_1px,transparent_1px),linear-gradient(0deg,rgba(255,255,255,.07)_1px,transparent_1px)] [background-size:46px_46px] [mask:radial-gradient(60%_70%_at_70%_30%,#000,transparent_75%)]"></div>
                <div class="relative z-[1] max-w-[600px]">
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/30 bg-white/15 px-3.5 py-1.5 text-[.72rem] font-semibold uppercase tracking-[.13em] text-white"><span class="h-[7px] w-[7px] rounded-full bg-white"></span> Get the app</span>
                    <h2 class="my-4 font-display text-[clamp(2.1rem,4vw,3rem)] font-bold tracking-tight text-white">Your next ride is one tap away</h2>
                    <p class="mb-8 text-[1.06rem] leading-relaxed text-white/90">Download ComingBro free and join 50,000+ riders moving smarter across India. Available on iOS and Android.</p>
                    <div class="flex flex-wrap gap-3.5">
                        <a href="#" class="inline-flex items-center gap-3 rounded-[15px] border border-white/30 bg-white/10 px-5 py-3 transition hover:-translate-y-0.5 hover:bg-white/20">
                            <svg class="h-6 w-6 fill-white" viewBox="0 0 24 24"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>
                            <span class="flex flex-col text-left leading-tight"><small class="text-[.66rem] text-white/70">Download on the</small><strong class="font-display text-base font-semibold">App Store</strong></span>
                        </a>
                        <a href="#" class="inline-flex items-center gap-3 rounded-[15px] border border-white/30 bg-white/10 px-5 py-3 transition hover:-translate-y-0.5 hover:bg-white/20">
                            <svg class="h-6 w-6 fill-white" viewBox="0 0 24 24"><path d="M3.609 1.814L13.792 12 3.61 22.186a.996.996 0 0 1-.61-.92V2.734a1 1 0 0 1 .609-.92zm10.89 10.893l2.302 2.302-10.937 6.333 8.635-8.635zm3.199-3.199l2.302 1.327a1 1 0 0 1 0 1.73l-2.302 1.327-2.53-2.53 2.53-2.854zM5.864 2.658L16.8 8.99l-2.302 2.302-8.635-8.635z"/></svg>
                            <span class="flex flex-col text-left leading-tight"><small class="text-[.66rem] text-white/70">Get it on</small><strong class="font-display text-base font-semibold">Google Play</strong></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ====================== FAQ ====================== --}}
    <section id="faq" class="pb-28">
        <div class="mx-auto max-w-[1200px] px-7">
            <div data-reveal class="mx-auto mb-16 max-w-[660px] text-center">
                <span class="inline-flex items-center gap-2 rounded-full border border-[#0d1c3a22] bg-white px-3.5 py-1.5 text-[.72rem] font-semibold uppercase tracking-[.13em] text-[#2b6fff] shadow-soft dark:border-white/15 dark:bg-white/[.05]"><span class="h-[7px] w-[7px] rounded-full bg-[#00c2a8]"></span> FAQ</span>
                <h2 class="mt-5 font-display text-[clamp(2.05rem,4.5vw,3.15rem)] font-bold tracking-tight">Good to know</h2>
                <p class="mt-4 text-[1.08rem] leading-relaxed text-[#4a5a72] dark:text-[#aab6cc]">Everything you might want to ask before your first ride.</p>
            </div>
            <div class="mx-auto flex max-w-[780px] flex-col gap-3.5">
                @php
                    $faqs = [
                        ['How do I book a ride?','Download the app, set your pickup and destination, choose a ride type and tap confirm. You\'ll be matched with the nearest driver in seconds and can track them all the way to you.'],
                        ['Are the fares fixed upfront?','Yes. You see the exact fare before you confirm your ride, calculated from distance, vehicle type and live demand — no hidden charges at the end of the trip.'],
                        ['How are drivers verified?','Every ComingBro driver completes document verification and a background check, and uploads valid licence, registration and insurance before they can accept their first trip.'],
                        ['What payment methods can I use?','Pay however suits you — in-app wallet, UPI, debit or credit card, or cash directly to the driver at the end of the ride.'],
                        ['Can I become a ComingBro driver?','Absolutely. Download the driver app, complete your profile and document verification, and start earning on your own schedule. Reach out via our contact page to learn more.'],
                    ];
                @endphp
                @foreach($faqs as $q)
                    <div class="faq overflow-hidden rounded-[15px] border border-[#0d1c3a14] bg-white shadow-soft transition dark:border-white/10 dark:bg-white/[.035]">
                        <button class="faq-q flex w-full items-center justify-between gap-4 px-6 py-5 text-left font-display text-[1.02rem] font-semibold">
                            {{ $q[0] }}
                            <svg class="chev h-[22px] w-[22px] shrink-0 stroke-[#2b6fff] transition-transform" viewBox="0 0 24 24" fill="none" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
                        </button>
                        <div class="faq-a max-h-0 overflow-hidden transition-[max-height] duration-300"><p class="px-6 pb-6 text-[.95rem] leading-relaxed text-[#4a5a72] dark:text-[#aab6cc]">{{ $q[1] }}</p></div>
                    </div>
                @endforeach
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
            var chev = btn.querySelector('.chev');
            var isOpen = ans.style.maxHeight && ans.style.maxHeight !== '0px';
            document.querySelectorAll('.faq-a').forEach(function(a){ a.style.maxHeight = null; });
            document.querySelectorAll('.faq-q .chev').forEach(function(c){ c.classList.remove('rotate-180'); });
            document.querySelectorAll('.faq').forEach(function(f){ f.classList.remove('!border-[#2b6fff]/40'); });
            if (!isOpen){
                ans.style.maxHeight = ans.scrollHeight + 'px';
                chev.classList.add('rotate-180');
                faq.classList.add('!border-[#2b6fff]/40');
            }
        });
    });
</script>
@endsection

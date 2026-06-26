<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ComingBro — Move through the city, effortlessly')</title>
    <meta name="description" content="@yield('meta_description', 'ComingBro is India\'s premium ride-hailing platform. Book city rides, intercity travel and freight in seconds — safe, fast and fairly priced.')">
    <link rel="icon" href="/assets/images/logo.png">

    {{-- Social / Open Graph --}}
    <meta property="og:title" content="@yield('title', 'ComingBro — Move through the city, effortlessly')">
    <meta property="og:description" content="@yield('meta_description', 'India\'s premium ride-hailing platform.')">
    <meta property="og:type" content="website">
    <meta property="og:image" content="/assets/images/logo.png">
    <meta name="theme-color" content="#ffffff">

    {{-- Apply persisted dark mode before paint to avoid flash --}}
    <script>(function(){try{if(localStorage.getItem('comingbro-theme')==='dark')document.documentElement.classList.add('dark');}catch(e){}})();</script>

    @vite('resources/css/app.css')

    @yield('styles')
</head>
<body class="min-h-screen overflow-x-hidden bg-[#fbfcfe] font-sans text-[#0b1220] antialiased dark:bg-[#070b15] dark:text-[#f3f6fc]">

    {{-- Ambient page glow --}}
    <div aria-hidden="true" class="pointer-events-none fixed inset-0 -z-10
        [background:radial-gradient(48%_42%_at_82%_-8%,rgba(109,75,255,.14),transparent_62%),radial-gradient(42%_38%_at_6%_2%,rgba(43,111,255,.16),transparent_60%),radial-gradient(40%_36%_at_50%_118%,rgba(0,194,168,.12),transparent_62%)]
        dark:[background:radial-gradient(48%_42%_at_82%_-8%,rgba(155,123,255,.2),transparent_62%),radial-gradient(42%_38%_at_6%_2%,rgba(77,139,255,.26),transparent_60%),radial-gradient(40%_36%_at_50%_118%,rgba(47,224,200,.16),transparent_62%)]">
    </div>

    {{-- ============================== NAVBAR ============================== --}}
    <nav id="navbar" class="fixed inset-x-0 top-0 z-[1000] border-b border-transparent py-4 transition-all duration-300">
        <div class="mx-auto flex max-w-[1200px] items-center justify-between px-7">
            <a href="/" class="flex items-center gap-3 font-display text-[1.24rem] font-bold tracking-tight">
                <img src="/assets/images/logo.png" alt="ComingBro" class="h-9 w-auto">
                <span>ComingBro</span>
            </a>

            {{-- Centered pill nav --}}
            <div class="hidden items-center gap-0.5 rounded-full border border-[#0d1c3a14] bg-white/70 p-1.5 shadow-soft backdrop-blur-md md:flex dark:border-white/10 dark:bg-white/[.04]">
                @php
                    $nav = [
                        ['/', 'Home', request()->is('/')],
                        ['/about', 'About', request()->is('about')],
                        ['/#services', 'Services', false],
                        ['/#how', 'How it works', false],
                        ['/contact', 'Contact', request()->is('contact')],
                    ];
                @endphp
                @foreach($nav as [$href, $label, $active])
                    <a href="{{ $href }}"
                       class="rounded-full px-4 py-2 text-[.9rem] font-medium transition
                       {{ $active ? 'bg-[#2b6fff]/10 text-[#2b6fff]' : 'text-[#4a5a72] hover:text-[#0b1220] dark:text-[#aab6cc] dark:hover:text-white' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <div class="flex items-center gap-3">
                <button id="themeToggle" aria-label="Toggle theme"
                    class="flex h-[42px] w-[42px] items-center justify-center rounded-full border border-[#0d1c3a14] bg-white text-[#0b1220] shadow-soft transition hover:rotate-[18deg] hover:border-[#2b6fff]/40 dark:border-white/10 dark:bg-white/[.04] dark:text-white">
                    <svg class="hidden h-[19px] w-[19px] dark:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                    <svg class="block h-[19px] w-[19px] dark:hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                </button>
                <a href="/#download" class="group relative hidden items-center gap-2 overflow-hidden rounded-[14px] bg-brand-grad px-6 py-3 text-[.95rem] font-semibold text-white shadow-glow transition hover:-translate-y-0.5 sm:inline-flex">
                    <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Get the app
                    <span class="absolute -left-3/4 top-0 h-full w-1/2 -skew-x-[20deg] bg-gradient-to-r from-transparent via-white/40 to-transparent transition-all duration-700 group-hover:left-[130%]"></span>
                </a>

                <button id="hamburger" aria-label="Menu" class="z-[1001] flex flex-col gap-[5px] p-2 md:hidden">
                    <span class="block h-0.5 w-6 rounded bg-current transition-all"></span>
                    <span class="block h-0.5 w-6 rounded bg-current transition-all"></span>
                    <span class="block h-0.5 w-6 rounded bg-current transition-all"></span>
                </button>
            </div>
        </div>
    </nav>

    {{-- Mobile menu --}}
    <div id="mobileMenu" class="fixed inset-0 z-[999] hidden flex-col items-center justify-center gap-4 bg-white/80 opacity-0 backdrop-blur-2xl transition-opacity duration-300 dark:bg-[#070b15]/85">
        <a href="/" class="mobile-link font-display text-[1.6rem] font-semibold tracking-tight hover:text-[#2b6fff]">Home</a>
        <a href="/about" class="mobile-link font-display text-[1.6rem] font-semibold tracking-tight hover:text-[#2b6fff]">About</a>
        <a href="/#services" class="mobile-link font-display text-[1.6rem] font-semibold tracking-tight hover:text-[#2b6fff]">Services</a>
        <a href="/#how" class="mobile-link font-display text-[1.6rem] font-semibold tracking-tight hover:text-[#2b6fff]">How it works</a>
        <a href="/contact" class="mobile-link font-display text-[1.6rem] font-semibold tracking-tight hover:text-[#2b6fff]">Contact</a>
        <a href="/#download" class="mobile-link mt-4 inline-flex items-center gap-2 rounded-[14px] bg-brand-grad px-8 py-4 text-base font-semibold text-white shadow-glow">Get the app</a>
    </div>

    {{-- ============================== CONTENT ============================== --}}
    <main class="min-h-[60vh]">
        @yield('content')
    </main>

    {{-- ============================== FOOTER ============================== --}}
    @php
        $cb = $contact ?? [];
        $cbPhone = $cb['phone'] ?? '+919028777184';
        $cbEmail = $cb['email'] ?? 'support@comingbro.in';
        $cbAddress = (!empty($cb['address']) && strtolower($cb['address']) !== 'your address') ? $cb['address'] : null;
    @endphp
    <footer class="relative border-t border-[#0d1c3a14] bg-white pt-[84px] dark:border-white/10 dark:bg-[#0c1322]">
        <div class="mx-auto max-w-[1200px] px-7">
            <div class="grid grid-cols-1 gap-12 border-b border-[#0d1c3a14] pb-12 dark:border-white/10 md:grid-cols-2 lg:grid-cols-[1.7fr_1fr_1fr_1.4fr]">
                <div>
                    <a href="/" class="mb-[18px] flex items-center gap-3 font-display text-[1.24rem] font-bold tracking-tight">
                        <img src="/assets/images/logo.png" alt="ComingBro" class="h-9 w-auto"><span>ComingBro</span>
                    </a>
                    <p class="max-w-[300px] text-[.93rem] leading-7 text-[#4a5a72] dark:text-[#aab6cc]">India's premium ride-hailing platform. City rides, intercity journeys and freight — booked in seconds, priced fairly, driven safely.</p>
                    <div class="mt-6 flex gap-2.5">
                        <a href="#" aria-label="Facebook" class="group flex h-[42px] w-[42px] items-center justify-center rounded-[13px] border border-[#0d1c3a14] bg-[#f6f8fd] transition hover:-translate-y-0.5 hover:border-transparent hover:bg-brand-grad hover:shadow-glow dark:border-white/10 dark:bg-white/[.04]">
                            <svg class="h-[17px] w-[17px] fill-[#4a5a72] transition group-hover:fill-white dark:fill-[#aab6cc]" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" aria-label="X" class="group flex h-[42px] w-[42px] items-center justify-center rounded-[13px] border border-[#0d1c3a14] bg-[#f6f8fd] transition hover:-translate-y-0.5 hover:border-transparent hover:bg-brand-grad hover:shadow-glow dark:border-white/10 dark:bg-white/[.04]">
                            <svg class="h-[17px] w-[17px] fill-[#4a5a72] transition group-hover:fill-white dark:fill-[#aab6cc]" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="#" aria-label="Instagram" class="group flex h-[42px] w-[42px] items-center justify-center rounded-[13px] border border-[#0d1c3a14] bg-[#f6f8fd] transition hover:-translate-y-0.5 hover:border-transparent hover:bg-brand-grad hover:shadow-glow dark:border-white/10 dark:bg-white/[.04]">
                            <svg class="h-[17px] w-[17px] fill-[#4a5a72] transition group-hover:fill-white dark:fill-[#aab6cc]" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </a>
                    </div>
                </div>
                <div>
                    <h4 class="mb-5 text-[.74rem] font-semibold uppercase tracking-[.11em] text-[#8a97ab]">Company</h4>
                    <div class="flex flex-col gap-3 text-[.93rem] text-[#4a5a72] dark:text-[#aab6cc]">
                        <a href="/about" class="w-fit hover:text-[#2b6fff]">About us</a>
                        <a href="/#how" class="w-fit hover:text-[#2b6fff]">How it works</a>
                        <a href="/#services" class="w-fit hover:text-[#2b6fff]">Services</a>
                        <a href="/contact" class="w-fit hover:text-[#2b6fff]">Contact</a>
                    </div>
                </div>
                <div>
                    <h4 class="mb-5 text-[.74rem] font-semibold uppercase tracking-[.11em] text-[#8a97ab]">Legal</h4>
                    <div class="flex flex-col gap-3 text-[.93rem] text-[#4a5a72] dark:text-[#aab6cc]">
                        <a href="/privacy-policy" class="w-fit hover:text-[#2b6fff]">Privacy Policy</a>
                        <a href="/terms-and-conditions" class="w-fit hover:text-[#2b6fff]">Terms of Service</a>
                    </div>
                </div>
                <div>
                    <h4 class="mb-5 text-[.74rem] font-semibold uppercase tracking-[.11em] text-[#8a97ab]">Get in touch</h4>
                    <div class="mb-3.5 flex items-start gap-3 text-[.93rem] text-[#4a5a72] dark:text-[#aab6cc]">
                        <svg class="mt-0.5 h-[18px] w-[18px] shrink-0 fill-[#2b6fff]" viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1H7.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
                        <a href="tel:{{ $cbPhone }}" class="hover:text-[#2b6fff]">{{ $cbPhone }}</a>
                    </div>
                    <div class="mb-3.5 flex items-start gap-3 text-[.93rem] text-[#4a5a72] dark:text-[#aab6cc]">
                        <svg class="mt-0.5 h-[18px] w-[18px] shrink-0 fill-[#2b6fff]" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                        <a href="mailto:{{ $cbEmail }}" class="hover:text-[#2b6fff]">{{ $cbEmail }}</a>
                    </div>
                    @if($cbAddress)
                    <div class="mb-3.5 flex items-start gap-3 text-[.93rem] text-[#4a5a72] dark:text-[#aab6cc]">
                        <svg class="mt-0.5 h-[18px] w-[18px] shrink-0 fill-[#2b6fff]" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 110-5 2.5 2.5 0 010 5z"/></svg>
                        <span>{{ $cbAddress }}</span>
                    </div>
                    @endif
                </div>
            </div>
            <div class="flex flex-col items-center justify-between gap-2.5 py-6 text-center text-[.85rem] text-[#8a97ab] sm:flex-row sm:text-left">
                <span>&copy; {{ date('Y') }} ComingBro. All rights reserved.</span>
                <span>Made with care in India 🇮🇳</span>
            </div>
        </div>
    </footer>

    <script>
        (function(){
            'use strict';
            var html = document.documentElement;

            /* Theme — default light; toggle the `dark` class (Tailwind darkMode:class) */
            var toggle = document.getElementById('themeToggle');
            if (toggle) toggle.addEventListener('click', function(){
                var isDark = html.classList.toggle('dark');
                localStorage.setItem('comingbro-theme', isDark ? 'dark' : 'light');
                var tc = document.querySelector('meta[name=theme-color]');
                if (tc) tc.setAttribute('content', isDark ? '#070b15' : '#ffffff');
            });

            /* Navbar background on scroll */
            var navbar = document.getElementById('navbar');
            function onScroll(){
                var s = (window.scrollY || window.pageYOffset) > 24;
                navbar.classList.toggle('py-2.5', s);
                navbar.classList.toggle('py-4', !s);
                navbar.classList.toggle('bg-white/70', s);
                navbar.classList.toggle('dark:bg-[#070b15]/70', s);
                navbar.classList.toggle('backdrop-blur-2xl', s);
                navbar.classList.toggle('border-[#0d1c3a14]', s);
                navbar.classList.toggle('dark:border-white/10', s);
                navbar.classList.toggle('shadow-soft', s);
            }
            window.addEventListener('scroll', onScroll, { passive:true }); onScroll();

            /* Mobile menu */
            var burger = document.getElementById('hamburger');
            var menu = document.getElementById('mobileMenu');
            function closeMenu(){
                burger.classList.remove('is-open');
                menu.classList.remove('flex','opacity-100'); menu.classList.add('hidden','opacity-0');
                document.body.style.overflow = '';
            }
            if (burger) burger.addEventListener('click', function(){
                var open = menu.classList.contains('hidden');
                if (open){
                    menu.classList.remove('hidden','opacity-0'); menu.classList.add('flex');
                    requestAnimationFrame(function(){ menu.classList.add('opacity-100'); });
                    document.body.style.overflow = 'hidden';
                } else { closeMenu(); }
            });
            if (menu) menu.querySelectorAll('a').forEach(function(a){ a.addEventListener('click', closeMenu); });

            /* Scroll reveal */
            var revealEls = document.querySelectorAll('[data-reveal]');
            if ('IntersectionObserver' in window) {
                var io = new IntersectionObserver(function(entries){
                    entries.forEach(function(e){
                        if (e.isIntersecting){ e.target.classList.add('in'); io.unobserve(e.target); }
                    });
                }, { threshold:0.12, rootMargin:'0px 0px -8% 0px' });
                revealEls.forEach(function(el){ io.observe(el); });
            } else {
                revealEls.forEach(function(el){ el.classList.add('in'); });
            }
        })();
    </script>
    @yield('scripts')
</body>
</html>

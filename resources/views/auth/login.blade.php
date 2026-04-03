<x-layout.auth>
    <style>
        @keyframes slideInLeft { from { opacity: 0; transform: translateX(-60px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes slideInRight { from { opacity: 0; transform: translateX(60px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes slideInUp { from { opacity: 0; transform: translateY(40px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes scaleIn { from { opacity: 0; transform: scale(0.85); } to { opacity: 1; transform: scale(1); } }
        @keyframes float { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-15px); } }
        @keyframes gradient-shift { 0%,100% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } }
        @keyframes dot-blink { 0%,100% { opacity: 0.3; } 50% { opacity: 1; } }
        @keyframes ripple { 0% { transform: scale(1); opacity: 0.4; } 100% { transform: scale(3); opacity: 0; } }
        @keyframes orbit { 0% { transform: rotate(0deg) translateX(120px) rotate(0deg); } 100% { transform: rotate(360deg) translateX(120px) rotate(-360deg); } }
        @keyframes car-drive {
            0%   { left: -20%; }
            100% { left: 110%; }
        }
        @keyframes wheel-spin {
            0%   { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes road-dash {
            0%   { transform: translateX(0); }
            100% { transform: translateX(-48px); }
        }
        @keyframes exhaust {
            0%   { opacity: 0.6; transform: scale(1) translateX(0); }
            100% { opacity: 0; transform: scale(2.5) translateX(-20px); }
        }

        .anim-slide-left { animation: slideInLeft 0.8s ease-out both; }
        .anim-slide-right { animation: slideInRight 0.8s ease-out both; }
        .anim-slide-up { animation: slideInUp 0.7s ease-out both; }
        .anim-fade { animation: fadeIn 1s ease-out both; }
        .anim-scale { animation: scaleIn 0.6s ease-out both; }
        .anim-float { animation: float 5s ease-in-out infinite; }
        .anim-gradient { background-size: 200% 200%; animation: gradient-shift 8s ease infinite; }
        .d1 { animation-delay: 0.1s; } .d2 { animation-delay: 0.2s; } .d3 { animation-delay: 0.3s; }
        .d4 { animation-delay: 0.4s; } .d5 { animation-delay: 0.5s; } .d6 { animation-delay: 0.6s; }
        .d7 { animation-delay: 0.7s; } .d8 { animation-delay: 0.8s; } .d9 { animation-delay: 0.9s; }
    </style>

    <div x-data="loginForm()" class="min-h-screen overflow-hidden bg-gradient-to-br from-[#f0f9fc] via-white to-[#e6f9f9] dark:from-[#060818] dark:via-[#0e1726] dark:to-[#0a1520]">

        <!-- Subtle Background Blobs -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute w-[500px] h-[500px] rounded-full bg-[#018DBD]/[0.04] -top-32 -left-32 blur-3xl anim-float"></div>
            <div class="absolute w-[400px] h-[400px] rounded-full bg-[#13C3C3]/[0.04] bottom-0 right-0 blur-3xl anim-float" style="animation-delay:3s"></div>
        </div>

        <!-- Main Container -->
        <div class="relative flex items-center justify-center min-h-screen p-4">
            <div class="w-full max-w-[1100px]">
                <div class="grid overflow-hidden bg-white/80 dark:bg-[#0e1726]/90 backdrop-blur-xl shadow-[0_25px_80px_-15px_rgba(1,141,189,0.2)] lg:grid-cols-5 rounded-[2rem] border border-white/30 dark:border-white/5 anim-scale">

                    <!-- ==================== LEFT PANEL (3 cols) ==================== -->
                    <div class="relative lg:col-span-3 bg-gradient-to-br from-[#015f80] via-[#018DBD] to-[#13C3C3] anim-gradient p-8 sm:p-12 flex flex-col justify-between min-h-[380px] lg:min-h-[700px] overflow-hidden">

                        <!-- Background Decorations -->
                        <div class="absolute inset-0 overflow-hidden">
                            <div class="absolute w-72 h-72 -top-20 -right-20 rounded-full bg-white/[0.06] anim-float"></div>
                            <div class="absolute w-56 h-56 bottom-16 -left-14 rounded-full bg-white/[0.04] anim-float" style="animation-delay:2.5s"></div>
                            <!-- Orbiting dot -->
                            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 hidden lg:block">
                                <div style="animation: orbit 20s linear infinite;">
                                    <div class="w-2 h-2 rounded-full bg-white/20"></div>
                                </div>
                            </div>
                            <!-- Dot grid -->
                            <div class="absolute inset-0 opacity-[0.035]" style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 28px 28px;"></div>

                            <!-- ===== ANIMATED CAR + ROAD ===== -->
                            <!-- Road -->
                            <div class="absolute bottom-[52px] left-0 right-0 h-[3px] bg-white/[0.12] rounded-full"></div>
                            <!-- Road dashes -->
                            <div class="absolute bottom-[53px] left-0 right-0 h-[1px] overflow-hidden">
                                <div class="flex gap-3 w-[200%]" style="animation: road-dash 0.6s linear infinite;">
                                    <div class="flex gap-3 shrink-0">
                                        <span class="block w-6 h-[1px] bg-white/20"></span><span class="block w-6 h-[1px] bg-white/20"></span>
                                        <span class="block w-6 h-[1px] bg-white/20"></span><span class="block w-6 h-[1px] bg-white/20"></span>
                                        <span class="block w-6 h-[1px] bg-white/20"></span><span class="block w-6 h-[1px] bg-white/20"></span>
                                        <span class="block w-6 h-[1px] bg-white/20"></span><span class="block w-6 h-[1px] bg-white/20"></span>
                                        <span class="block w-6 h-[1px] bg-white/20"></span><span class="block w-6 h-[1px] bg-white/20"></span>
                                        <span class="block w-6 h-[1px] bg-white/20"></span><span class="block w-6 h-[1px] bg-white/20"></span>
                                    </div>
                                    <div class="flex gap-3 shrink-0">
                                        <span class="block w-6 h-[1px] bg-white/20"></span><span class="block w-6 h-[1px] bg-white/20"></span>
                                        <span class="block w-6 h-[1px] bg-white/20"></span><span class="block w-6 h-[1px] bg-white/20"></span>
                                        <span class="block w-6 h-[1px] bg-white/20"></span><span class="block w-6 h-[1px] bg-white/20"></span>
                                        <span class="block w-6 h-[1px] bg-white/20"></span><span class="block w-6 h-[1px] bg-white/20"></span>
                                        <span class="block w-6 h-[1px] bg-white/20"></span><span class="block w-6 h-[1px] bg-white/20"></span>
                                        <span class="block w-6 h-[1px] bg-white/20"></span><span class="block w-6 h-[1px] bg-white/20"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Animated Supercar -->
                            <div class="absolute bottom-[56px]" style="animation: car-drive 7s ease-in-out infinite;">
                                <div class="relative">
                                    <!-- Exhaust Smoke -->
                                    <div class="absolute -left-3 bottom-[8px]">
                                        <div class="w-2.5 h-2.5 rounded-full bg-white/25" style="animation: exhaust 0.6s ease-out infinite;"></div>
                                        <div class="absolute top-0 left-0 w-2 h-2 rounded-full bg-white/15" style="animation: exhaust 0.6s ease-out infinite 0.2s;"></div>
                                        <div class="absolute -top-1 left-1 w-1.5 h-1.5 rounded-full bg-white/10" style="animation: exhaust 0.6s ease-out infinite 0.4s;"></div>
                                    </div>

                                    <!-- Speed Lines -->
                                    <div class="absolute top-[14px] -left-10 space-y-2 opacity-40">
                                        <div class="h-[1px] w-8 bg-gradient-to-l from-white/60 to-transparent"></div>
                                        <div class="h-[1px] w-6 bg-gradient-to-l from-white/40 to-transparent ml-1"></div>
                                        <div class="h-[1px] w-10 bg-gradient-to-l from-white/50 to-transparent -ml-1"></div>
                                    </div>

                                    <!-- Ferrari / Supercar SVG -->
                                    <svg width="130" height="50" viewBox="0 0 130 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <!-- Shadow under car -->
                                        <ellipse cx="65" cy="47" rx="50" ry="3" fill="black" opacity="0.15"/>

                                        <!-- Main body - low sleek profile -->
                                        <path d="M8 33 L12 30 L18 28 L28 24 L42 16 L58 12 L72 11 L88 12 L98 15 L108 20 L118 26 L122 30 L122 36 L8 36 Z" fill="white" opacity="0.92"/>

                                        <!-- Rear spoiler -->
                                        <path d="M6 30 L8 26 L12 28 L8 33 Z" fill="white" opacity="0.7"/>
                                        <rect x="4" y="25" width="10" height="2" rx="1" fill="white" opacity="0.5"/>

                                        <!-- Hood scoop / engine vent -->
                                        <path d="M60 14 L72 13 L72 16 L60 17 Z" fill="white" opacity="0.3"/>
                                        <rect x="78" y="15" width="8" height="2" rx="0.5" fill="white" opacity="0.2"/>
                                        <rect x="78" y="18" width="8" height="2" rx="0.5" fill="white" opacity="0.2"/>

                                        <!-- Windshield -->
                                        <path d="M48 18 L58 13 L72 12 L72 18 L48 22 Z" fill="#018DBD" opacity="0.45"/>
                                        <!-- Side window -->
                                        <path d="M42 20 L48 18 L48 24 L38 26 Z" fill="#018DBD" opacity="0.35"/>

                                        <!-- Side body line -->
                                        <path d="M18 30 L45 24 L90 20 L118 26" stroke="white" stroke-width="0.5" opacity="0.3" fill="none"/>

                                        <!-- Side air intake -->
                                        <path d="M36 27 L44 25 L44 30 L36 31 Z" fill="white" opacity="0.15"/>
                                        <line x1="38" y1="27" x2="38" y2="30" stroke="white" stroke-width="0.3" opacity="0.3"/>
                                        <line x1="40" y1="26.5" x2="40" y2="30.5" stroke="white" stroke-width="0.3" opacity="0.3"/>
                                        <line x1="42" y1="26" x2="42" y2="30" stroke="white" stroke-width="0.3" opacity="0.3"/>

                                        <!-- Front splitter -->
                                        <path d="M116 32 L126 30 L126 34 L122 36 L116 36 Z" fill="white" opacity="0.6"/>

                                        <!-- Headlights - aggressive angular -->
                                        <path d="M112 24 L120 27 L118 30 L110 28 Z" fill="#FFE066" opacity="0.9"/>
                                        <!-- Headlight glow beam -->
                                        <ellipse cx="128" cy="28" rx="10" ry="6" fill="#FFE066" opacity="0.06"/>
                                        <ellipse cx="126" cy="28" rx="5" ry="3" fill="#FFE066" opacity="0.1"/>

                                        <!-- DRL strip -->
                                        <path d="M108 22 L116 25" stroke="#FFE066" stroke-width="1" opacity="0.7" stroke-linecap="round"/>

                                        <!-- Taillights - wide strip -->
                                        <rect x="6" y="29" width="8" height="2" rx="1" fill="#FF3333" opacity="0.9"/>
                                        <rect x="6" y="32" width="6" height="1" rx="0.5" fill="#FF3333" opacity="0.5"/>

                                        <!-- Bottom diffuser -->
                                        <rect x="10" y="36" width="112" height="1.5" rx="0.75" fill="white" opacity="0.2"/>
                                        <!-- Diffuser fins -->
                                        <line x1="14" y1="36" x2="14" y2="38" stroke="white" stroke-width="0.5" opacity="0.15"/>
                                        <line x1="20" y1="36" x2="20" y2="38" stroke="white" stroke-width="0.5" opacity="0.15"/>
                                        <line x1="110" y1="36" x2="110" y2="38" stroke="white" stroke-width="0.5" opacity="0.15"/>
                                        <line x1="116" y1="36" x2="116" y2="38" stroke="white" stroke-width="0.5" opacity="0.15"/>

                                        <!-- Front Wheel (larger, sporty) -->
                                        <g style="animation: wheel-spin 0.35s linear infinite; transform-origin: 96px 40px;">
                                            <circle cx="96" cy="40" r="7.5" fill="#111827" stroke="white" stroke-width="1.2" opacity="0.95"/>
                                            <circle cx="96" cy="40" r="4" fill="none" stroke="white" stroke-width="0.5" opacity="0.2"/>
                                            <circle cx="96" cy="40" r="1.5" fill="white" opacity="0.4"/>
                                            <!-- 5-spoke pattern -->
                                            <line x1="96" y1="33" x2="96" y2="47" stroke="white" stroke-width="0.6" opacity="0.25"/>
                                            <line x1="89" y1="37.5" x2="103" y2="42.5" stroke="white" stroke-width="0.6" opacity="0.25"/>
                                            <line x1="89" y1="42.5" x2="103" y2="37.5" stroke="white" stroke-width="0.6" opacity="0.25"/>
                                        </g>
                                        <!-- Rear Wheel -->
                                        <g style="animation: wheel-spin 0.35s linear infinite; transform-origin: 30px 40px;">
                                            <circle cx="30" cy="40" r="7.5" fill="#111827" stroke="white" stroke-width="1.2" opacity="0.95"/>
                                            <circle cx="30" cy="40" r="4" fill="none" stroke="white" stroke-width="0.5" opacity="0.2"/>
                                            <circle cx="30" cy="40" r="1.5" fill="white" opacity="0.4"/>
                                            <!-- 5-spoke pattern -->
                                            <line x1="30" y1="33" x2="30" y2="47" stroke="white" stroke-width="0.6" opacity="0.25"/>
                                            <line x1="23" y1="37.5" x2="37" y2="42.5" stroke="white" stroke-width="0.6" opacity="0.25"/>
                                            <line x1="23" y1="42.5" x2="37" y2="37.5" stroke="white" stroke-width="0.6" opacity="0.25"/>
                                        </g>

                                        <!-- Wheel arches -->
                                        <path d="M84 36 Q90 30 96 30 Q102 30 108 36" fill="none" stroke="white" stroke-width="1" opacity="0.3"/>
                                        <path d="M18 36 Q24 30 30 30 Q36 30 42 36" fill="none" stroke="white" stroke-width="1" opacity="0.3"/>
                                    </svg>
                                </div>
                            </div>
                            <!-- ===== END CAR ===== -->
                        </div>

                        <!-- Logo -->
                        <div class="relative anim-slide-left">
                            <img src="/assets/images/logo.png" alt="ComingBro"
                                class="w-auto h-20 sm:h-28 brightness-0 invert drop-shadow-2xl" />
                        </div>

                        <!-- Hero Content -->
                        <div class="relative flex-1 flex flex-col justify-center py-6 space-y-5">
                            <h1 class="text-3xl sm:text-[2.8rem] font-extrabold text-white leading-[1.15] anim-slide-left d2">
                                Command Center<br/>
                                <span class="bg-gradient-to-r from-white to-white/70 bg-clip-text text-transparent">for Your Fleet</span>
                            </h1>
                            <p class="text-[15px] text-white/60 max-w-md leading-relaxed anim-slide-left d3">
                                Monitor rides in real-time, manage drivers & customers, track revenue, and scale your ride-hailing business from one powerful dashboard.
                            </p>

                            <!-- Metric Cards -->
                            <div class="grid grid-cols-3 gap-3 pt-3 anim-slide-up d5">
                                <div class="p-4 rounded-2xl bg-white/[0.08] backdrop-blur-sm border border-white/[0.08] text-center group hover:bg-white/[0.14] transition-all duration-300">
                                    <svg class="w-6 h-6 mx-auto mb-2 text-white/70" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                                    </svg>
                                    <p class="text-lg font-bold text-white">Live GPS</p>
                                    <p class="text-[11px] text-white/40 mt-0.5">Real-time Tracking</p>
                                </div>
                                <div class="p-4 rounded-2xl bg-white/[0.08] backdrop-blur-sm border border-white/[0.08] text-center group hover:bg-white/[0.14] transition-all duration-300">
                                    <svg class="w-6 h-6 mx-auto mb-2 text-white/70" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-lg font-bold text-white">Revenue</p>
                                    <p class="text-[11px] text-white/40 mt-0.5">Earnings & Payouts</p>
                                </div>
                                <div class="p-4 rounded-2xl bg-white/[0.08] backdrop-blur-sm border border-white/[0.08] text-center group hover:bg-white/[0.14] transition-all duration-300">
                                    <svg class="w-6 h-6 mx-auto mb-2 text-white/70" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                                    </svg>
                                    <p class="text-lg font-bold text-white">Secure</p>
                                    <p class="text-[11px] text-white/40 mt-0.5">Full Control</p>
                                </div>
                            </div>
                        </div>

                        <!-- Bottom Status Bar -->
                        <div class="relative flex flex-wrap items-center gap-5 pt-4 anim-fade d8">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-emerald-400 shadow-lg shadow-emerald-400/50" style="animation: dot-blink 2s infinite;"></div>
                                <span class="text-xs text-white/50 font-medium tracking-wide">PLATFORM ONLINE</span>
                            </div>
                            <div class="w-px h-3 bg-white/10"></div>
                            <span class="text-xs text-white/40">v4.3</span>
                            <div class="w-px h-3 bg-white/10"></div>
                            <span class="text-xs text-white/40">&copy; {{ date('Y') }} ComingBro</span>
                        </div>
                    </div>

                    <!-- ==================== RIGHT PANEL - FORM (2 cols) ==================== -->
                    <div class="lg:col-span-2 flex flex-col justify-center p-8 sm:p-10 lg:px-10 lg:py-12">
                        <div class="w-full max-w-[340px] mx-auto">

                            <!-- Mobile Logo -->
                            <div class="flex justify-center mb-6 lg:hidden anim-scale">
                                <img src="/assets/images/logo.png" alt="ComingBro" class="h-16" />
                            </div>

                            <!-- Header -->
                            <div class="mb-8 anim-slide-right d3">
                                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#018DBD]/10 dark:bg-[#018DBD]/20 text-[#018DBD] text-xs font-bold mb-4">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    ADMIN ACCESS
                                </div>
                                <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white">
                                    Welcome back, Admin
                                </h2>
                                <p class="mt-1.5 text-sm text-gray-400 dark:text-gray-500">
                                    Sign in to manage your platform
                                </p>
                            </div>

                            <!-- Error Messages -->
                            @if ($errors->any())
                                <div class="p-4 mb-6 border border-red-200 rounded-2xl bg-red-50/80 dark:bg-red-900/20 dark:border-red-800/30 anim-slide-up">
                                    <div class="flex items-center gap-2 mb-1">
                                        <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-sm font-semibold text-red-600 dark:text-red-400">Invalid credentials</span>
                                    </div>
                                    @foreach ($errors->all() as $error)
                                        <p class="text-xs text-red-500/80">{{ $error }}</p>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Form -->
                            <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-5">
                                @csrf

                                <!-- Username -->
                                <div class="space-y-1.5 anim-slide-right d4">
                                    <label for="Username" class="text-xs font-bold tracking-wider text-gray-400 dark:text-gray-500 uppercase">Username</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                            <svg class="w-[18px] h-[18px] text-gray-300 group-focus-within:text-[#018DBD] transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                            </svg>
                                        </div>
                                        <input id="Username" name="username" type="text" value="{{ old('username') }}" required autocomplete="username"
                                            class="w-full py-3.5 pl-12 pr-4 text-sm text-gray-900 bg-gray-50/50 dark:bg-white/[0.03] border border-gray-200 dark:border-white/10 rounded-xl dark:text-white focus:outline-none focus:ring-2 focus:ring-[#018DBD]/20 focus:border-[#018DBD] dark:focus:border-[#13C3C3] transition-all duration-300 placeholder:text-gray-300 dark:placeholder:text-gray-600"
                                            placeholder="Enter your username" />
                                    </div>
                                </div>

                                <!-- Password -->
                                <div class="space-y-1.5 anim-slide-right d5">
                                    <label for="Password" class="text-xs font-bold tracking-wider text-gray-400 dark:text-gray-500 uppercase">Password</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                            <svg class="w-[18px] h-[18px] text-gray-300 group-focus-within:text-[#018DBD] transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                                            </svg>
                                        </div>
                                        <input id="Password" name="password" x-bind:type="showPassword ? 'text' : 'password'" required
                                            class="w-full py-3.5 pl-12 pr-12 text-sm text-gray-900 bg-gray-50/50 dark:bg-white/[0.03] border border-gray-200 dark:border-white/10 rounded-xl dark:text-white focus:outline-none focus:ring-2 focus:ring-[#018DBD]/20 focus:border-[#018DBD] dark:focus:border-[#13C3C3] transition-all duration-300 placeholder:text-gray-300 dark:placeholder:text-gray-600"
                                            placeholder="Enter your password" />
                                        <button type="button" @click="showPassword = !showPassword"
                                            class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-300 hover:text-[#018DBD] transition-colors">
                                            <svg x-show="!showPassword" class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <svg x-show="showPassword" class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Remember -->
                                <div class="flex items-center anim-slide-right d6">
                                    <label class="flex items-center gap-2 cursor-pointer select-none">
                                        <input id="remember" name="remember" type="checkbox"
                                            class="w-4 h-4 rounded border-gray-300 text-[#018DBD] focus:ring-[#018DBD]/30 dark:border-gray-600 dark:bg-white/5" />
                                        <span class="text-sm text-gray-400">Remember me</span>
                                    </label>
                                </div>

                                <!-- Submit -->
                                <div class="anim-slide-right d7">
                                    <button type="submit"
                                        class="relative w-full py-3.5 font-bold text-white overflow-hidden rounded-xl bg-gradient-to-r from-[#018DBD] to-[#13C3C3] hover:from-[#016a8e] hover:to-[#0fa8a8] shadow-lg shadow-[#018DBD]/20 hover:shadow-xl hover:shadow-[#018DBD]/30 transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0 group">
                                        <span class="relative z-10 flex items-center justify-center gap-2 text-sm tracking-wide">
                                            SIGN IN
                                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                            </svg>
                                        </span>
                                        <div class="absolute inset-0 bg-gradient-to-r from-[#13C3C3] to-[#018DBD] opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                    </button>
                                </div>
                            </form>

                            <!-- Footer -->
                            <div class="pt-8 mt-6 border-t border-gray-100 dark:border-white/5 text-center anim-fade d9">
                                <p class="text-[11px] text-gray-300 dark:text-gray-600">
                                    ComingBro Technologies &middot; Admin Panel v4.3
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        function loginForm() {
            return { showPassword: false }
        }
    </script>
</x-layout.auth>

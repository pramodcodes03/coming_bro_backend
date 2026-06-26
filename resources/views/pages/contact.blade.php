@extends('layouts.frontend')

@section('title', 'Contact Us — ComingBro')
@section('meta_description', 'Get in touch with ComingBro. We\'re here to help with questions about rides, partnerships and driving with us.')

@section('content')
<section class="relative overflow-hidden px-7 pb-28 pt-44 lg:pt-48">
    <div aria-hidden="true" class="animate-spinslow pointer-events-none absolute -right-[6%] -top-[12%] -z-10 h-[620px] w-[620px] rounded-full opacity-80 blur-[70px]
        [background:conic-gradient(from_120deg_at_50%_50%,rgba(43,111,255,.12),rgba(109,75,255,.1),rgba(0,194,168,.08),rgba(43,111,255,.12))]"></div>
    <div class="mx-auto max-w-[1200px]">
        {{-- Hero --}}
        <div data-reveal class="mx-auto max-w-[680px] text-center">
            <span class="inline-flex items-center gap-2 rounded-full border border-[#0d1c3a22] bg-white px-3.5 py-1.5 text-[.72rem] font-semibold uppercase tracking-[.13em] text-[#2b6fff] shadow-soft dark:border-white/15 dark:bg-white/[.05]"><span class="h-[7px] w-[7px] rounded-full bg-[#00c2a8]"></span> Contact</span>
            <h1 class="mt-5 font-display text-[clamp(2.4rem,5vw,3.6rem)] font-extrabold leading-[1.05] tracking-tight">Let's <span class="brand-text">talk</span></h1>
            <p class="mx-auto mt-4 max-w-[560px] text-[1.1rem] leading-relaxed text-[#4a5a72] dark:text-[#aab6cc]">Have a question, partnership idea, or need help with a ride? We're always here for you.</p>
        </div>

        <div class="mt-16 grid grid-cols-1 items-start gap-7 lg:grid-cols-[.85fr_1.15fr]">
            {{-- Contact info --}}
            <div data-reveal="left" class="flex flex-col gap-4">
                @php
                    $infos = [
                        ['M2 4h20v16H2zM22 4L12 13 2 4','Email','mailto:'.($contact['email'] ?? 'support@comingbro.in'), $contact['email'] ?? 'support@comingbro.in'],
                        ['M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z','Phone','tel:'.($contact['phone'] ?? '+919028777184'), $contact['phone'] ?? '+919028777184'],
                    ];
                @endphp
                @foreach($infos as $info)
                    <div class="flex items-center gap-4 rounded-[22px] border border-[#0d1c3a14] bg-white p-6 shadow-card transition hover:-translate-y-1 hover:shadow-lift dark:border-white/10 dark:bg-white/[.035]">
                        <span class="flex h-[50px] w-[50px] min-w-[50px] items-center justify-center rounded-[14px] bg-brand-grad shadow-glow"><svg class="h-[22px] w-[22px] stroke-white" viewBox="0 0 24 24" fill="none" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $info[0] }}"/></svg></span>
                        <div>
                            <h3 class="mb-1 text-[.78rem] font-semibold uppercase tracking-[.1em] text-[#8a97ab]">{{ $info[1] }}</h3>
                            <a href="{{ $info[2] }}" class="text-[1.02rem] font-semibold hover:text-[#2b6fff]">{{ $info[3] }}</a>
                        </div>
                    </div>
                @endforeach
                @if(!empty($contact['address'] ?? '') && strtolower($contact['address']) !== 'your address')
                <div class="flex items-center gap-4 rounded-[22px] border border-[#0d1c3a14] bg-white p-6 shadow-card dark:border-white/10 dark:bg-white/[.035]">
                    <span class="flex h-[50px] w-[50px] min-w-[50px] items-center justify-center rounded-[14px] bg-brand-grad shadow-glow"><svg class="h-[22px] w-[22px] stroke-white" viewBox="0 0 24 24" fill="none" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg></span>
                    <div>
                        <h3 class="mb-1 text-[.78rem] font-semibold uppercase tracking-[.1em] text-[#8a97ab]">Address</h3>
                        <p class="text-[1.02rem] font-semibold">{{ $contact['address'] }}</p>
                    </div>
                </div>
                @endif
                <div class="flex items-center gap-4 rounded-[22px] border border-[#0d1c3a14] bg-white p-6 shadow-card dark:border-white/10 dark:bg-white/[.035]">
                    <span class="flex h-[50px] w-[50px] min-w-[50px] items-center justify-center rounded-[14px] bg-brand-grad shadow-glow"><svg class="h-[22px] w-[22px] stroke-white" viewBox="0 0 24 24" fill="none" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></span>
                    <div>
                        <h3 class="mb-1 text-[.78rem] font-semibold uppercase tracking-[.1em] text-[#8a97ab]">Support hours</h3>
                        <p class="text-[1.02rem] font-semibold">24 / 7 · Every day</p>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <div data-reveal="right" class="rounded-[28px] border border-[#0d1c3a14] bg-white p-8 shadow-card sm:p-10 dark:border-white/10 dark:bg-white/[.035]">
                <h2 class="mb-1.5 font-display text-[1.6rem] font-bold">Send us a message</h2>
                <p class="mb-7 text-[.95rem] text-[#4a5a72] dark:text-[#aab6cc]">Fill out the form and we'll get back to you as soon as possible.</p>
                <form action="#" method="POST" onsubmit="return false;">
                    @php $inp = 'w-full rounded-[13px] border border-[#0d1c3a22] bg-[#f6f8fd] px-4 py-3.5 text-[.95rem] text-[#0b1220] transition placeholder:text-[#8a97ab] focus:border-[#2b6fff] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#2b6fff]/25 dark:border-white/10 dark:bg-white/[.04] dark:text-white'; @endphp
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="mb-4">
                            <label for="name" class="mb-2 block text-[.84rem] font-semibold text-[#4a5a72] dark:text-[#aab6cc]">Full name</label>
                            <input type="text" id="name" name="name" placeholder="Your name" required class="{{ $inp }}">
                        </div>
                        <div class="mb-4">
                            <label for="email" class="mb-2 block text-[.84rem] font-semibold text-[#4a5a72] dark:text-[#aab6cc]">Email address</label>
                            <input type="email" id="email" name="email" placeholder="your@email.com" required class="{{ $inp }}">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="subject" class="mb-2 block text-[.84rem] font-semibold text-[#4a5a72] dark:text-[#aab6cc]">Subject</label>
                        <input type="text" id="subject" name="subject" placeholder="How can we help?" class="{{ $inp }}">
                    </div>
                    <div class="mb-4">
                        <label for="message" class="mb-2 block text-[.84rem] font-semibold text-[#4a5a72] dark:text-[#aab6cc]">Message</label>
                        <textarea id="message" name="message" rows="5" placeholder="Tell us a bit more about your query…" required class="{{ $inp }} min-h-[130px] resize-y"></textarea>
                    </div>
                    <button type="submit" class="group relative inline-flex w-full items-center justify-center gap-2 overflow-hidden rounded-[14px] bg-brand-grad px-7 py-3.5 text-[.95rem] font-semibold text-white shadow-glow transition hover:-translate-y-0.5">
                        <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                        Send message
                        <span class="absolute -left-3/4 top-0 h-full w-1/2 -skew-x-[20deg] bg-gradient-to-r from-transparent via-white/40 to-transparent transition-all duration-700 group-hover:left-[130%]"></span>
                    </button>
                    <p class="mt-3.5 text-[.82rem] text-[#8a97ab]">We typically reply within a few hours.</p>
                </form>
            </div>
        </div>

        {{-- Map --}}
        <div data-reveal class="mt-8 overflow-hidden rounded-[28px] border border-[#0d1c3a14] shadow-card dark:border-white/10">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1884.2837186356746!2d73.22505683967282!3d19.170402899999996!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be793006436dc1f%3A0xa8beb2bfc1e41e8e!2sA%20Wing!5e0!3m2!1sen!2sin!4v1729079433738!5m2!1sen!2sin"
                class="block h-[380px] w-full border-0 [filter:grayscale(.2)_contrast(1.05)] dark:[filter:invert(.9)_hue-rotate(180deg)_grayscale(.2)]"
                allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</section>
@endsection

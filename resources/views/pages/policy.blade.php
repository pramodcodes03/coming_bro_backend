@extends('layouts.frontend')

@section('title', ($title ?? 'Policy') . ' — ComingBro')
@section('meta_description', 'Read the ' . ($title ?? 'policy') . ' for ComingBro ride-hailing services.')

@section('content')
<section class="relative px-7 pb-28 pt-44 lg:pt-48">
    <div class="mx-auto max-w-[1200px]">
        <div data-reveal class="mb-12 text-center">
            <span class="inline-flex items-center gap-2 rounded-full border border-[#0d1c3a22] bg-white px-3.5 py-1.5 text-[.72rem] font-semibold uppercase tracking-[.13em] text-[#2b6fff] shadow-soft dark:border-white/15 dark:bg-white/[.05]"><span class="h-[7px] w-[7px] rounded-full bg-[#00c2a8]"></span> Legal</span>
            <h1 class="mt-5 font-display text-[clamp(2.2rem,5vw,3.2rem)] font-extrabold tracking-tight">{{ $title ?? 'Policy' }}</h1>
            <p class="mt-3 text-[.95rem] text-[#8a97ab]">Last updated {{ date('F Y') }}</p>
        </div>

        <div data-reveal class="mx-auto max-w-[860px] rounded-[28px] border border-[#0d1c3a14] bg-white p-8 shadow-card sm:p-12 dark:border-white/10 dark:bg-white/[.035]">
            @if(!empty(trim(strip_tags($content ?? ''))))
                <div class="prose prose-slate max-w-none prose-headings:font-display prose-a:text-[#2b6fff] prose-strong:text-[#0b1220] dark:prose-invert dark:prose-strong:text-white">
                    {!! $content !!}
                </div>
            @else
                <p class="py-10 text-center text-[#8a97ab]">This {{ strtolower($title ?? 'document') }} will be available here shortly. For any questions in the meantime, please <a href="/contact" class="text-[#2b6fff] hover:underline">contact us</a>.</p>
            @endif
        </div>
    </div>
</section>
@endsection

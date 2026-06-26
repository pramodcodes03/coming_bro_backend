{{--
    Admin pagination — pill style: First · Prev · 1 2 3 … · Next · Last.
    Markup matches the project theme (border-[#e0e6ed] / dark:border-[#191e3a],
    text-primary active state). Lives under resources/ so Tailwind compiles the
    classes (the framework's vendor view gets purged, which is why only
    Previous/Next showed before).
--}}
@if ($paginator->hasPages())
    @php($disabled = 'opacity-50 cursor-not-allowed')
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}">
        <ul class="inline-flex items-center space-x-1 rtl:space-x-reverse m-auto mb-4">

            {{-- First --}}
            <li>
                @if ($paginator->onFirstPage())
                    <span aria-disabled="true" class="flex justify-center font-semibold px-3.5 py-2 rounded transition text-dark border-2 border-[#e0e6ed] dark:border-[#191e3a] dark:text-white-light {{ $disabled }}">{{ __('First') }}</span>
                @else
                    <a href="{{ $paginator->url(1) }}" class="flex justify-center font-semibold px-3.5 py-2 rounded transition text-dark hover:text-primary border-2 border-[#e0e6ed] dark:border-[#191e3a] hover:border-primary dark:hover:border-primary dark:text-white-light">{{ __('First') }}</a>
                @endif
            </li>

            {{-- Prev --}}
            <li>
                @if ($paginator->onFirstPage())
                    <span aria-disabled="true" class="flex justify-center font-semibold px-3.5 py-2 rounded transition text-dark border-2 border-[#e0e6ed] dark:border-[#191e3a] dark:text-white-light {{ $disabled }}">{{ __('Prev') }}</span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="flex justify-center font-semibold px-3.5 py-2 rounded transition text-dark hover:text-primary border-2 border-[#e0e6ed] dark:border-[#191e3a] hover:border-primary dark:hover:border-primary dark:text-white-light">{{ __('Prev') }}</a>
                @endif
            </li>

            {{-- Page numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li><span aria-disabled="true" class="flex justify-center font-semibold px-3.5 py-2 rounded transition text-dark border-2 border-[#e0e6ed] dark:border-[#191e3a] dark:text-white-light">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li aria-current="page"><span class="flex justify-center font-semibold px-3.5 py-2 rounded transition text-primary border-2 border-primary dark:border-primary dark:text-white-light">{{ $page }}</span></li>
                        @else
                            <li><a href="{{ $url }}" class="flex justify-center font-semibold px-3.5 py-2 rounded transition text-dark hover:text-primary border-2 border-[#e0e6ed] dark:border-[#191e3a] hover:border-primary dark:hover:border-primary dark:text-white-light">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            <li>
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="flex justify-center font-semibold px-3.5 py-2 rounded transition text-dark hover:text-primary border-2 border-[#e0e6ed] dark:border-[#191e3a] hover:border-primary dark:hover:border-primary dark:text-white-light">{{ __('Next') }}</a>
                @else
                    <span aria-disabled="true" class="flex justify-center font-semibold px-3.5 py-2 rounded transition text-dark border-2 border-[#e0e6ed] dark:border-[#191e3a] dark:text-white-light {{ $disabled }}">{{ __('Next') }}</span>
                @endif
            </li>

            {{-- Last --}}
            <li>
                @if ($paginator->currentPage() === $paginator->lastPage())
                    <span aria-disabled="true" class="flex justify-center font-semibold px-3.5 py-2 rounded transition text-dark border-2 border-[#e0e6ed] dark:border-[#191e3a] dark:text-white-light {{ $disabled }}">{{ __('Last') }}</span>
                @else
                    <a href="{{ $paginator->url($paginator->lastPage()) }}" class="flex justify-center font-semibold px-3.5 py-2 rounded transition text-dark hover:text-primary border-2 border-[#e0e6ed] dark:border-[#191e3a] hover:border-primary dark:hover:border-primary dark:text-white-light">{{ __('Last') }}</a>
                @endif
            </li>
        </ul>
    </nav>
@endif

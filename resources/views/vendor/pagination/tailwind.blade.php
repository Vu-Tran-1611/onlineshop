@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-center items-center space-x-1 text-sm">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="px-3 py-1 text-sky-900 bg-gray-100 rounded">←</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
                class="px-3 py-1 bg-slate-100 hover:bg-slate-200 rounded text-slate-800">←</a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-3 py-1 text-sky-900">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-3 py-1 bg-slate-800 text-white rounded">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}"
                            class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
                class="px-3 py-1 bg-slate-100 hover:bg-slate-200 rounded text-slate-800">→</a>
        @else
            <span class="px-3 py-1 text-sky-900 bg-gray-100 rounded">→</span>
        @endif
    </nav>
@endif

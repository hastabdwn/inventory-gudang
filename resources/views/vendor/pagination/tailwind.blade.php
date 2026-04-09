@if ($paginator->hasPages())
<nav class="flex items-center gap-1 text-sm">
    {{-- Previous --}}
    @if ($paginator->onFirstPage())
        <span class="px-2.5 py-1.5 text-gray-300 border border-gray-200 rounded-lg cursor-not-allowed">
            &lsaquo;
        </span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}"
           class="px-2.5 py-1.5 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
            &lsaquo;
        </a>
    @endif

    {{-- Pages --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="px-2.5 py-1.5 text-gray-400">{{ $element }}</span>
        @endif
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="px-2.5 py-1.5 bg-blue-600 text-white border border-blue-600 rounded-lg font-medium">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}"
                       class="px-2.5 py-1.5 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                        {{ $page }}
                    </a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}"
           class="px-2.5 py-1.5 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
            &rsaquo;
        </a>
    @else
        <span class="px-2.5 py-1.5 text-gray-300 border border-gray-200 rounded-lg cursor-not-allowed">
            &rsaquo;
        </span>
    @endif
</nav>
@endif
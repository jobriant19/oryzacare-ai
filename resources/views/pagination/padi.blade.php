@if ($paginator->hasPages())
    <nav class="pager" role="navigation" aria-label="Navigasi halaman">
        @if ($paginator->onFirstPage())
            <span class="pg disabled" aria-disabled="true">‹</span>
        @else
            <a class="pg" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Sebelumnya">‹</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="pg disabled">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="pg active" aria-current="page">{{ $page }}</span>
                    @else
                        <a class="pg" href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a class="pg" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Berikutnya">›</a>
        @else
            <span class="pg disabled" aria-disabled="true">›</span>
        @endif
    </nav>
@endif

@if ($paginator->hasPages())
<nav>
    @if ($paginator->onFirstPage())<span class="pg off">‹</span>@else<a href="{{ $paginator->previousPageUrl() }}">‹</a>@endif
    @foreach ($elements as $element)
        @if (is_string($element))<span class="pg off">…</span>@endif
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())<span class="pg on">{{ $page }}</span>@else<a href="{{ $url }}">{{ $page }}</a>@endif
            @endforeach
        @endif
    @endforeach
    @if ($paginator->hasMorePages())<a href="{{ $paginator->nextPageUrl() }}">›</a>@else<span class="pg off">›</span>@endif
</nav>
@endif

@props(['paginator'])

@if ($paginator->hasPages())
    <nav class="pager" role="navigation" aria-label="Navigasi halaman">
        @if ($paginator->onFirstPage())
            <span class="pager-btn is-disabled" aria-disabled="true">Sebelumnya</span>
        @else
            <a class="pager-btn" href="{{ $paginator->previousPageUrl() }}" rel="prev">Sebelumnya</a>
        @endif

        @php
            $start = max(1, $paginator->currentPage() - 2);
            $end = min($paginator->lastPage(), $paginator->currentPage() + 2);
        @endphp

        @if ($start > 1)
            <a class="pager-num" href="{{ $paginator->url(1) }}">1</a>
            @if ($start > 2)<span class="pager-gap">…</span>@endif
        @endif

        @for ($page = $start; $page <= $end; $page++)
            @if ($page === $paginator->currentPage())
                <span class="pager-num is-current" aria-current="page">{{ $page }}</span>
            @else
                <a class="pager-num" href="{{ $paginator->url($page) }}">{{ $page }}</a>
            @endif
        @endfor

        @if ($end < $paginator->lastPage())
            @if ($end < $paginator->lastPage() - 1)<span class="pager-gap">…</span>@endif
            <a class="pager-num" href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a>
        @endif

        @if ($paginator->hasMorePages())
            <a class="pager-btn" href="{{ $paginator->nextPageUrl() }}" rel="next">Berikutnya</a>
        @else
            <span class="pager-btn is-disabled" aria-disabled="true">Berikutnya</span>
        @endif
    </nav>
@endif

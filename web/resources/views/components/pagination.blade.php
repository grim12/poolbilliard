{{--
    <x-pagination :paginator="$articles" />
    - Real numbered pager bound to a Laravel LengthAwarePaginator (unlike ui/'s
    macros/pagination.njk, which only fakes ?page=N links over static mock data — we have real
    paginated data, so this wires up the genuine thing). Same BEM classes/markup shape as ui/'s
    macro, so no CSS changes needed.
--}}
@props(['paginator'])

@if ($paginator->hasPages())
    <nav class="c-pagination" aria-label="Stránkování">
        @if ($paginator->onFirstPage())
            <span class="c-pagination__nav c-pagination__nav--is-disabled" aria-hidden="true">
                <x-heroicon-m-chevron-left width="18" height="18" />
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="c-pagination__nav" aria-label="Předchozí stránka">
                <x-heroicon-m-chevron-left width="18" height="18" />
            </a>
        @endif

        <ul class="c-pagination__list">
            @for ($page = 1; $page <= $paginator->lastPage(); $page++)
                <li>
                    @if ($page === $paginator->currentPage())
                        <span class="c-pagination__page c-pagination__page--is-current" aria-current="page">{{ $page }}</span>
                    @else
                        <a href="{{ $paginator->url($page) }}" class="c-pagination__page">{{ $page }}</a>
                    @endif
                </li>
            @endfor
        </ul>

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="c-pagination__nav" aria-label="Další stránka">
                <x-heroicon-m-chevron-right width="18" height="18" />
            </a>
        @else
            <span class="c-pagination__nav c-pagination__nav--is-disabled" aria-hidden="true">
                <x-heroicon-m-chevron-right width="18" height="18" />
            </span>
        @endif
    </nav>
@endif

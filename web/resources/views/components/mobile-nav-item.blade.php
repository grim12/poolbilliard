{{--
    <x-mobile-nav-item :text :url :children :is-active />
    - Mobile nav link; when children are given, renders text as a real link + a separate
    toggle button for the tap-to-expand sublist (text must stay navigable, only the chevron
    toggles — see the earlier UI fix this mirrors). Mirrors
    ui/src/_includes/layouts/header.njk's mobileNavItem() macro.
    - children: array of ['text' => string, 'url' => string]
--}}
@props([
    'text',
    'url',
    'children' => null,
    'isActive' => false,
])

@if ($children)
    <div class="c-header__mobile-nav-item" data-accordion>
        <div class="c-header__mobile-nav-row">
            <a @class(['c-header__nav-link', 'c-header__nav-link--is-active' => $isActive]) href="{{ $url }}">{{ $text }}</a>
            <button type="button" class="c-header__mobile-nav-toggle" data-accordion-toggle aria-expanded="false" aria-label="Rozbalit podnabídku {{ $text }}">
                <x-heroicon-m-chevron-down class="c-header__nav-chevron" width="16" height="16" />
            </button>
        </div>
        <div class="c-header__mobile-nav-sublist" data-accordion-panel>
            @foreach ($children as $child)
                <a class="c-header__mobile-nav-sublink" href="{{ $child['url'] }}">{{ $child['text'] }}</a>
            @endforeach
        </div>
    </div>
@else
    <a @class(['c-header__nav-link', 'c-header__nav-link--is-active' => $isActive]) href="{{ $url }}">{{ $text }}</a>
@endif

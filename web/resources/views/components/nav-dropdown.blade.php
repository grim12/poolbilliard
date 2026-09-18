{{--
    <x-nav-dropdown :text :url :items :is-active />
    - Desktop nav link with a chevron + hover/focus dropdown panel. Mirrors
    ui/src/_includes/layouts/header.njk's navDropdown() macro.
    - items: array of ['text' => string, 'url' => string]
--}}
@props([
    'text',
    'url',
    'items' => [],
    'isActive' => false,
])

<div class="c-header__nav-item">
    <a @class(['c-header__nav-link', 'c-header__nav-link--is-active' => $isActive]) href="{{ $url }}" aria-haspopup="true">
        {{ $text }}
        <x-heroicon-m-chevron-down class="c-header__nav-chevron" width="16" height="16" />
    </a>
    <div class="c-header__dropdown">
        @foreach ($items as $item)
            <a class="c-header__dropdown-link" href="{{ $item['url'] }}">{{ $item['text'] }}</a>
        @endforeach
    </div>
</div>

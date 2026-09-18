{{--
    <x-no-results :title :text :icon />
    - Generic empty-state block: dashed-border card with an icon + message. Used anywhere a
      filtered/searched list can come back empty.
    Mirrors ui/src/_includes/macros/no-results.njk.
--}}
@props([
    'title' => '',
    'text' => '',
    'icon' => 'magnifying-glass',
])

<div {{ $attributes->merge(['class' => 'c-no-results']) }}>
    <span class="c-no-results__icon" aria-hidden="true">
        <x-dynamic-component :component="'heroicon-m-'.$icon" width="28" height="28" />
    </span>
    <p class="c-no-results__title">{{ $title }}</p>
    @if ($text)
        <p class="c-no-results__text">{{ $text }}</p>
    @endif
</div>

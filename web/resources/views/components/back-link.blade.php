{{--
    <x-back-link :text :url />
    - Generic "back to listing" link, shown above a page's H1 on detail/sub-listing pages.
    Mirrors ui/src/_includes/macros/back-link.njk.
--}}
@props([
    'text' => null,
    'url' => '#',
])

@php
    $text ??= __('Zpět');
@endphp

<a href="{{ $url }}" {{ $attributes->merge(['class' => 'c-back-link']) }}>
    <x-heroicon-m-arrow-left width="18" height="18" />
    {{ $text }}
</a>

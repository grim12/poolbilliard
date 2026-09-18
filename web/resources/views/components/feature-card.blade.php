{{--
    <x-feature-card :icon :title :text :link-text :link-url />
    - Small icon-badge + title + text + link card — e.g. the "Kde začít" quick-select grid on
      /jak-zacit. Mirrors ui/src/_includes/macros/feature-card.njk.
--}}
@props([
    'icon' => '',
    'title' => '',
    'text' => '',
    'linkText' => '',
    'linkUrl' => '#',
])

<div {{ $attributes->merge(['class' => 'c-feature-card']) }}>
    @if ($icon)
        <span class="c-feature-card__icon" aria-hidden="true">
            <x-dynamic-component :component="'heroicon-m-'.$icon" width="22" height="22" />
        </span>
    @endif
    <p class="c-feature-card__title">{{ $title }}</p>
    @if ($text)
        <p class="c-feature-card__text">{{ $text }}</p>
    @endif
    @if ($linkText)
        <x-button :text="$linkText" :url="$linkUrl" variant="link" size="sm" class="mt-auto" />
    @endif
</div>

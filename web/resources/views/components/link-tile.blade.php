{{--
    <x-link-tile :title :url :image :image-alt :heading-level />
    - Whole card is one link, image is decorative background (aria-label carries the title).
    Mirrors ui/src/_includes/macros/card/tile.njk (card_tile).
--}}
@props([
    'title',
    'url' => '#',
    'image' => '',
    'imageAlt' => '',
    'headingLevel' => 'h3',
])

<article {{ $attributes->merge(['class' => 'c-card c-card--tile']) }}>
    <a href="{{ $url }}" class="c-card__link" aria-label="{{ $title }}">
        @if ($image)
            <div class="c-card__media">
                <img src="{{ $image }}" alt="{{ $imageAlt }}" loading="lazy" />
            </div>
        @endif
        <div class="c-card__overlay"></div>
        <div class="c-card__body">
            <{{ $headingLevel }} class="c-card__title">{{ $title }}</{{ $headingLevel }}>
            <x-heroicon-m-chevron-right width="20" height="20" class="c-card__arrow" />
        </div>
    </a>
</article>

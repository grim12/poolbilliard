{{--
    <x-tournament-card :title :url :tag-text :tag-color :tag-variant :date-text :location-text
                       :badge :soon :heading-level />
    - Mirrors ui/src/_includes/macros/card/tournament.njk.
    - tagColor: "primary" (default) | "accent" | "gold" | "dark" | "gray"
    - badge: true -> gold medal badge (ranking-counting tournament)
    - soon: true -> highlights dateText as bold red (upcoming)
--}}
@props([
    'title',
    'url' => '#',
    'tagText' => '',
    'tagColor' => 'primary',
    'tagVariant' => 'plain',
    'dateText' => '',
    'locationText' => '',
    'badge' => false,
    'soon' => false,
    'headingLevel' => 'h3',
])

<article {{ $attributes->merge(['class' => 'c-card c-card--tournament']) }}>
    <a href="{{ $url }}" class="c-card__link" aria-label="{{ $title }}">
        <div class="c-card__header">
            @if ($tagText)
                <x-tag :text="$tagText" :color="$tagColor" :variant="$tagVariant" size="sm" />
            @endif
            @if ($badge)
                <span class="c-card__badge" aria-hidden="true">
                    <x-heroicon-m-trophy width="22" height="22" />
                </span>
            @endif
        </div>
        <div class="c-card__row">
            <{{ $headingLevel }} class="c-card__title">{{ $title }}</{{ $headingLevel }}>
            <x-heroicon-m-chevron-right class="c-card__arrow" width="24" height="24" />
        </div>
        @if ($dateText || $locationText)
            <div class="c-card__info">
                @if ($dateText)
                    <p @class(['c-card__date', 'c-card__date--soon' => $soon])>{{ $dateText }}</p>
                @endif
                @if ($locationText)
                    <p class="c-card__location">{{ $locationText }}</p>
                @endif
            </div>
        @endif
    </a>
</article>

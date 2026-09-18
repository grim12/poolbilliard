{{--
    <x-tournament-content :back-text :back-url :tag-text :tag-color :title :date-text
                          :location-text>
        {{ $slot }}
    </x-tournament-content>
    - Narrow reading-column detail page for a single tournament — icon date/location instead of
    a hero image, freeform body slot meant for boxed info/link cards (.c-tournament-detail__card)
    + a CTA button. Mirrors ui/src/_includes/widgets/tournament-content.njk.
    - tagColor: "primary" (Svaz) | "accent" (Klub) | "gold" (Zahraniční) — matches tournament-card.
--}}
@props([
    'backText' => null,
    'backUrl' => '#',
    'tagText' => '',
    'tagColor' => 'primary',
    'title' => '',
    'dateText' => '',
    'locationText' => '',
])

@php
    $backText ??= __('Zpět na kalendář');
@endphp

<section {{ $attributes->merge(['class' => 'c-tournament-detail']) }}>
    <div class="c-container">
        <x-back-link :text="$backText" :url="$backUrl" />
    </div>

    <div class="c-container c-tournament-detail__inner">
        <header class="c-tournament-detail__header">
            @if ($tagText)
                <x-tag :text="$tagText" :color="$tagColor" variant="plain" size="sm" class="c-tournament-detail__tag" />
            @endif
            <h1 class="c-tournament-detail__title">{{ $title }}</h1>
            @if ($dateText || $locationText)
                <div class="c-tournament-detail__meta">
                    @if ($dateText)
                        <p class="c-tournament-detail__meta-item">
                            <x-heroicon-m-calendar-days width="18" height="18" />
                            {{ $dateText }}
                        </p>
                    @endif
                    @if ($locationText)
                        <p class="c-tournament-detail__meta-item">
                            <x-heroicon-m-map-pin width="18" height="18" />
                            {{ $locationText }}
                        </p>
                    @endif
                </div>
            @endif
        </header>

        <div class="c-tournament-detail__body">
            {{ $slot }}
        </div>
    </div>
</section>

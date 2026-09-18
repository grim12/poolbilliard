{{--
    <x-recurring-tournaments :tag-text :title :text :items :buttons />
    - Dark sidebar card for regular/periodic (weekly) amateur tournaments — distinct from the
      one-off Svaz/Mezinárodní calendar next to it, since these repeat every week and don't have
      a single date to put in the month grid.
    - items: array of ['frequency', 'title', 'location', 'url']
    - buttons: array of ['text', 'url'], rendered as white outline buttons on the dark card.
    Mirrors ui/src/_includes/macros/recurring-tournaments.njk.
--}}
@props([
    'tagText' => null,
    'title' => null,
    'text' => null,
    'items' => [],
    'buttons' => [],
])

@php
    $tagText ??= __('Amatérské turnaje');
    $title ??= __('Chceš si zahrát?');
    $text ??= __('Vyzkoušej si sportovní atmosféru a šanci uhrát výsledek, i jako začátečník.');
@endphp

<div {{ $attributes->merge(['class' => 'c-recurring t-dark']) }}>
    @if ($tagText)
        <x-tag :text="$tagText" color="gold-light" variant="subtle" size="sm" class="c-recurring__tag" />
    @endif
    <h3 class="c-recurring__title">{{ $title }}</h3>
    @if ($text)
        <p class="c-recurring__text">{{ $text }}</p>
    @endif

    <div class="c-recurring__list">
        @foreach ($items as $item)
            <a href="{{ $item['url'] }}" class="c-recurring__item" aria-label="{{ $item['title'] }}">
                <span class="c-recurring__item-body">
                    <span class="c-recurring__item-freq">{{ $item['frequency'] }}</span>
                    <span class="c-recurring__item-title">{{ $item['title'] }}</span>
                    <span class="c-recurring__item-location">
                        <x-heroicon-m-map-pin width="14" height="14" />
                        {{ $item['location'] }}
                    </span>
                </span>
                <span class="c-recurring__item-link">
                    {{ __('Více') }}
                    <x-heroicon-m-chevron-right width="16" height="16" />
                </span>
            </a>
        @endforeach
    </div>

    @if (count($buttons))
        <div class="c-recurring__actions">
            @foreach ($buttons as $button)
                <x-button :text="$button['text']" :url="$button['url']" variant="outline" size="sm" class="w-full xs:w-auto" />
            @endforeach
        </div>
    @endif
</div>

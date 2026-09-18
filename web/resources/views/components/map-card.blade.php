{{--
    <x-map-card :items :label :full-name :address :maps-url :pin-color />
    - "Kde nás najdeš" aside card — dark header (label + address + "Navigovat") over a Leaflet
      map. Shared by club-detail and herna-detail. Mirrors the `c-map-card` markup inlined in
      ui/src/_includes/widgets/{club-detail,herna-detail}.njk (kept as one component here since
      both call sites render byte-identical markup).
    - items: array of ['name' => string, 'fullName' => string, 'address' => string,
      'lat' => float, 'lng' => float] — always exactly one entry here (the record's own
      location), passed as an array because that's the shape [data-club-map] (see
      resources/js/app.js) expects for both the list-page overview map and this single-record
      one.
    - pinColor: "" (default, red — clubs) | "primary" (blue — herny), matches main.js's
      data-pin-color convention.
--}}
@props([
    'items' => [],
    'label' => null,
    'fullName' => '',
    'address' => '',
    'mapsUrl' => '',
    'pinColor' => '',
])

@php
    $label ??= __('Kde nás najdeš');
    $item = $items[0] ?? null;
    $hasCoords = $item && isset($item['lat'], $item['lng']);
@endphp

<div class="c-map-card">
    <div class="c-map-card__header">
        <x-tag :text="$label" color="gold-light" variant="subtle" size="sm" class="mb-2" />
        <p class="c-map-card__address">
            <span class="c-map-card__icon" aria-hidden="true">
                <x-heroicon-m-map-pin width="16" height="16" />
            </span>
            {{ collect([$fullName, $address])->filter()->implode(', ') }}
        </p>
        @if ($mapsUrl)
            <a class="c-map-card__link" href="{{ $mapsUrl }}" target="_blank" rel="noopener noreferrer">
                {{ __('Navigovat') }}
                <x-heroicon-m-chevron-right width="16" height="16" />
            </a>
        @endif
    </div>
    @if ($hasCoords)
        <div
            class="c-map-card__map"
            data-club-map='{{ json_encode($items) }}'
            @if ($pinColor) data-pin-color="{{ $pinColor }}" @endif
            role="application"
            aria-label="{{ __('Mapa – :place', ['place' => $fullName ?: $address]) }}"
        ></div>
    @endif
</div>

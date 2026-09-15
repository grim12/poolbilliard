{{--
    <x-herna-detail :herna />
    - Two-column body for the herna detail page: "O herně" + "Nabízené sporty" + "Fotogalerie" on
      the left; "Kde nás najdeš" (map, same x-map-card as club-detail) + "Otevírací doba" +
      "Kontakt" on the right. Every block is independently optional.
    - Fotogalerie: GLightbox isn't ported yet (same gap as components/gallery.blade.php) — tiles
      open the full image in a new tab instead of a lightbox.
    Mirrors ui/src/_includes/widgets/herna-detail.njk.
--}}
@props([
    'herna',
])

@php
    $hasMapCard = (bool) $herna->address;
    $hasContactCard = $herna->phone || $herna->email || $herna->website;
    $hasAside = $hasMapCard || count($herna->hours ?? []) || $hasContactCard;
    $mapsUrl = ($herna->lat && $herna->lng)
        ? 'https://www.google.com/maps/search/?api=1&query='.$herna->lat.','.$herna->lng
        : '';
@endphp

<section class="c-section c-section--herna-detail">
    <div class="c-container">
        <div @class(['c-section__grid', 'lg:grid-cols-1' => ! $hasAside])>
            <div class="c-section__body">
                @if ($herna->about_text)
                    <div>
                        <h2>O herně</h2>
                        <p>{{ $herna->about_text }}</p>
                    </div>
                @endif

                @if (count($herna->sports ?? []))
                    <div>
                        <h2>Nabízené sporty</h2>
                        <div class="c-herna-tags">
                            @foreach ($herna->sports as $sport)
                                <x-tag :text="$sport" color="primary" variant="subtle" />
                            @endforeach
                        </div>
                    </div>
                @endif

                @if (count($herna->gallery_urls))
                    <div>
                        <h2>Fotogalerie</h2>
                        <div class="c-herna-gallery">
                            @foreach ($herna->gallery_urls as $image)
                                <a href="{{ $image }}" target="_blank" rel="noopener noreferrer" class="c-herna-gallery__item" aria-label="{{ $herna->name }}">
                                    <img src="{{ $image }}" alt="" loading="lazy" />
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            @if ($hasAside)
                <aside class="c-section__aside">
                    @if ($hasMapCard)
                        <x-map-card
                            :items="($herna->lat && $herna->lng) ? [[
                                'name' => $herna->name,
                                'fullName' => $herna->name,
                                'address' => $herna->address,
                                'lat' => (float) $herna->lat,
                                'lng' => (float) $herna->lng,
                            ]] : []"
                            :full-name="$herna->name"
                            :address="$herna->address"
                            :maps-url="$mapsUrl"
                            pin-color="primary"
                        />
                    @endif

                    @if (count($herna->hours ?? []))
                        <div class="c-herna-aside-card">
                            <p class="c-section__eyebrow">Otevírací doba</p>
                            <div class="c-herna-hours">
                                @foreach ($herna->hours as $row)
                                    <div class="c-herna-hours__row">
                                        <span class="c-herna-hours__day">{{ $row['day'] }}</span>
                                        <span class="c-herna-hours__dots" aria-hidden="true"></span>
                                        <span class="c-herna-hours__time">{{ $row['text'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($hasContactCard)
                        <div class="c-herna-aside-card">
                            <p class="c-section__eyebrow">Kontakt</p>
                            <div class="c-herna-aside-card__contact">
                                @if ($herna->phone)
                                    <a class="c-herna-aside-card__contact-link" href="tel:{{ $herna->phone }}"><x-heroicon-m-phone width="18" height="18" /> {{ $herna->phone }}</a>
                                @endif
                                @if ($herna->email)
                                    <a class="c-herna-aside-card__contact-link" href="mailto:{{ $herna->email }}"><x-heroicon-m-envelope width="18" height="18" /> {{ $herna->email }}</a>
                                @endif
                                @if ($herna->website)
                                    <a class="c-herna-aside-card__contact-link" href="{{ $herna->website }}" target="_blank" rel="noopener noreferrer"><x-heroicon-m-globe-alt width="18" height="18" /> Web herny</a>
                                @endif
                            </div>
                        </div>
                    @endif
                </aside>
            @endif
        </div>
    </div>
</section>

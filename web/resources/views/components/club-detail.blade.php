{{--
    <x-club-detail :club />
    - Two-column body for the club detail page: "O klubu" + "Členové klubu" + recruitment notice
      on the left; "Kde nás najdeš" (map) + "Ambasador klubu" on the right. Every block is
      independently optional — only what this club actually has is shown. If the whole aside
      ends up empty (no address AND no ambassador/website), the grid collapses back to a single
      column instead of leaving a blank gap.
    Mirrors ui/src/_includes/widgets/club-detail.njk — there `notices` is an array so a page
    (e.g. a showcase) can stack more than one; a real club only ever has one recruitment state
    (Club::recruitmentMessage() already picks it), so this renders at most one alert().
--}}
@props([
    'club',
])

@php
    $hasMapCard = (bool) $club->address;
    $hasAmbassadorCard = $club->ambassador_name || $club->ambassador_website;
    $hasAside = $hasMapCard || $hasAmbassadorCard;
    $mapsUrl = ($club->lat && $club->lng)
        ? 'https://www.google.com/maps/search/?api=1&query='.$club->lat.','.$club->lng
        : '';
@endphp

<section class="c-section c-section--club-detail">
    <div class="c-container">
        <div @class(['c-section__grid c-club-detail__grid', 'lg:grid-cols-1' => ! $hasAside])>
            <div class="c-club-detail__main">
                @if ($club->about_text)
                    <div>
                        <h2>O klubu</h2>
                        <div class="c-club-detail__text">{!! $club->about_text !!}</div>
                    </div>
                @endif

                @if ($club->members->isNotEmpty())
                    <div>
                        <h2>Členové klubu</h2>
                        <div class="c-club-members">
                            @foreach ($club->members as $member)
                                <div class="c-club-member">
                                    <div class="c-club-member__photo">
                                        @if ($member->photo)
                                            <img src="{{ $member->photo_url }}" alt="" loading="lazy" />
                                        @else
                                            <x-heroicon-m-user-circle width="32" height="32" class="c-club-member__placeholder" />
                                        @endif
                                    </div>
                                    <p class="c-club-member__name">{{ $member->name }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="c-club-detail__notices">
                    <x-alert
                        :icon="$club->recruitment_open ? 'lock-open' : 'lock-closed'"
                        :title="$club->recruitment_open ? 'Nábor otevřen' : 'Nábor uzavřen'"
                        :text="$club->recruitment_message"
                        :color="$club->recruitment_open ? 'primary' : 'gold'"
                        size="lg"
                    />
                </div>
            </div>

            @if ($hasAside)
                <aside class="c-club-detail__aside">
                    @if ($hasMapCard)
                        <x-map-card
                            :items="($club->lat && $club->lng) ? [[
                                'name' => $club->name,
                                'fullName' => $club->full_name,
                                'address' => $club->address,
                                'lat' => (float) $club->lat,
                                'lng' => (float) $club->lng,
                            ]] : []"
                            :full-name="$club->full_name"
                            :address="$club->address"
                            :maps-url="$mapsUrl"
                        />
                    @endif

                    @if ($hasAmbassadorCard)
                        <div class="c-club-ambassador">
                            @if ($club->ambassador_name)
                                <div><x-tag text="Ambasador klubu" color="gold" size="sm" class="mb-3" /></div>
                                <p class="c-club-ambassador__name">
                                    <x-heroicon-m-user-circle width="20" height="20" />
                                    {{ $club->ambassador_name }}
                                </p>
                            @endif
                            @if ($club->ambassador_name && $club->ambassador_website)
                                <div class="c-club-ambassador__divider"></div>
                            @endif
                            @if ($club->ambassador_website)
                                <a class="c-club-ambassador__link" href="{{ $club->ambassador_website }}" target="_blank" rel="noopener noreferrer">
                                    <x-heroicon-m-globe-alt width="18" height="18" />
                                    Web klubu
                                </a>
                            @endif
                        </div>
                    @endif
                </aside>
            @endif
        </div>
    </div>
</section>

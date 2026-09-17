{{--
    Mirrors ui/src/kluby.njk — map + info panel hero, then the region-grouped club directory.
    $clubs: Collection grouped by region value (see ClubController::index()).
    $settings: App\Settings\KlubySettings — editorial text for the heading/info panel.
--}}
@php
    $mapClubs = $clubs->flatten()->map(fn ($club) => [
        'name' => $club->name,
        'fullName' => $club->full_name,
        'address' => $club->address,
        'lat' => $club->lat ? (float) $club->lat : null,
        'lng' => $club->lng ? (float) $club->lng : null,
        'url' => route('klub.show', $club),
    ])->filter(fn ($club) => $club['lat'] && $club['lng'])->values();
@endphp

<x-layouts.app
    :title="$settings->title.' — Poolbilliard'"
    description="Přehled klubů Českého svazu poolbilliardu — najděte klub ve svém okolí a připojte se k hráčské komunitě."
>
    <div class="bg-gradient-light">
        <x-news-header
            :title="$settings->title"
            :show-search="false"
            class="pb-none"
        />

        <section class="c-section pt-none">
            <div class="c-container">
                <div class="c-section__grid c-kluby-hero">
                    <div class="c-club-map" data-club-map='{{ json_encode($mapClubs) }}' role="application" aria-label="Mapa klubů v České republice"></div>

                    <x-info-panel
                        :tag-text="$settings->info_tag_text"
                        :title="$settings->info_title"
                        :text="$settings->info_text"
                        :items="[
                            ['icon' => 'user-group', 'text' => 'Přátelská komunita všech úrovní'],
                            ['icon' => 'trophy', 'text' => 'Tréninky, ligy i turnaje pro každého'],
                            ['icon' => 'map-pin', 'text' => 'Kluby po celé České republice'],
                        ]"
                        :foot-text="$settings->info_foot_text"
                        :button-text="$settings->info_button_text"
                        button-url="/jak-zacit"
                    />
                </div>
            </div>
        </section>
    </div>

    <x-club-directory :clubs="$clubs" class="pt-none" />

    <x-newsletter />
</x-layouts.app>
